HCService.FormManager.Objects.DropDownList = function () {
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'dropDownList';

    /**
     * This scope
     *
     * @type {HCService.FormManager.Objects.DropDownList}
     */
    var scope = this;

    /**
     * Options for selectize
     */
    var selectizeOptions = {plugins: [], allowEmptyOption: true};

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function () {
        if (this.getFieldData().search) {
            selectizeOptions.plugins.push('remove_button');

            if (this.getFieldData().search.maximumSelectionLength > 0)
                selectizeOptions.maxItems = this.getFieldData().search.maximumSelectionLength;
            else if (!this.getFieldData().search.maximumSelectionLength)
                selectizeOptions.maxItems = 10000;

            if (this.getFieldData().search.allowTags)
                selectizeOptions.create = function (input) {
                    return {
                        value: input,
                        text: input
                    }
                }
        }

        this.innerHTML = this.inputField = $('<br/><select id="' + this.uniqueFieldID + '" class="form-control" style="width:auto;"></select>');
    };

    /**
     * Handling options of the input field
     *
     * @method handleOptions
     */
    this.handleOptions = function (data, focus) {
        if (!data && !scope.getOptions())
            return;

        if (!data)
            data = scope.getOptions();

        var selectItem = $('#' + scope.uniqueFieldID);
        var fieldOptions = formatData(data);
        var existingValue = filledValue ? filledValue : scope.getFieldData().value;

        if (theSelectItem) {
            theSelectItem.clearOptions();
            scope.clearOptions(data);

            $.each(fieldOptions, function (key, value) {
                theSelectItem.addOption({value: value.id, text: value.text});
            });

            theSelectItem.refreshOptions(false);

            if (existingValue) {
                if (HCFunctions.isArray(existingValue)) {
                    $.each(existingValue, function (key, value) {
                        theSelectItem.addItem(value);
                    });
                }
                else
                    theSelectItem.addItem(existingValue);
            }
        }
        else {
            var length_i = fieldOptions.length;
            var length_j;
            var option;

            scope.inputField.html('');

            for (var i = 0; i < length_i; i++) {
                if (fieldOptions[i].children) {
                    length_j = fieldOptions[i].children.length;
                    option = '<optgroup label="' + fieldOptions[i].text + '">';

                    for (var j = 0; j < length_j; j++)
                        option += '<option value="' + fieldOptions[i].children[j].value + '">' + fieldOptions[i].children[j].text + '&nbsp;&nbsp;</option>';

                    option += '</optgroup>';
                }
                else
                    option = '<option value="' + fieldOptions[i].id + '">' + fieldOptions[i].text + '&nbsp;&nbsp;</option>';

                scope.inputField.append(option);
            }

            if (existingValue)
                selectItem.val(existingValue).trigger("change");
        }

        if (data && data.length > 0 && focus)
            theSelectItem.focus();

        scope.triggerContentChange();
    };

    /**
     * Updating when item is on stage
     */
    this.updateWhenOnStageLocal = function () {
        if (this.getFieldData().search) {
            addAjax();
            this.updateSelectComponent();

            this.getParent().append(this.getAnnotation());
        }

        if (disabled)
            this.disable();
    };

    function addAjax() {
        if (!scope.getFieldData().search.url)
            return;

        selectizeOptions.preload = true;
        selectizeOptions.load = function (query, callback) {

            if (query === '')
                return;

            callback = scope.handleOptions;

            if (!scope.dependencyValues)
                scope.dependencyValues = {};

            var params = scope.dependencyValues;
            params.q = query;

            $.ajax({
                url: scope.getFieldData().search.url,
                type: 'GET',
                delay: 250,
                dataType: 'json',
                data: params,
                error: function () {
                    callback();
                },
                success: function (data) {
                    callback(data, true);
                }
            });
        }
    }

    var filledValue;

    /**
     * Setting new data
     *
     * @method setContentData
     * @param {String} data for SingleLine
     */
    this.setContentData = function (data) {

        if (!data)
            return;

        if (this.getFieldData().search) {
            var selectItem = $('#' + this.uniqueFieldID);

            if (this.getFieldData().search.maximumSelectionLength === 1) {
                if (theSelectItem) {
                    if (HCFunctions.isObject(data)) {
                        this.handleOptions(data);
                        filledValue = data.id;
                    }
                    else
                        filledValue = data;

                    theSelectItem.addItem(filledValue);
                }
            }
            else {
                data = formatData(data);
                filledValue = [];

                $.each(data, function (key, value) {
                    var $option = $('#' + scope.uniqueFieldID + " option[value='" + value.id + "']");

                    if ($option.length) {
                        if (value.text === '' || value.text !== $option.text())
                            value.text = $option.text();

                        $option.remove();
                    }

                    selectItem.append($('<option/>', {value: value.id, text: value.text}));

                    theSelectItem.refreshOptions();
                    theSelectItem.addItem(value.id);
                    filledValue.push(value.id);
                });
            }
        }
        else
            $(this.inputField).val(data);

        if (this.getContentData() === null && data)
            this.getFieldData().value = data;

        this.triggerContentChange();
    };

    /**
     * Formatting data to fit the select2 component
     *
     * @param data
     * @returns {*}
     */
    function formatData(data, nodeNames) {
        if (!data)
            return '';

        var text;
        var multiLevel = scope.getFieldData().multiLevel;

        if (!nodeNames)
            nodeNames = scope.getFieldData().search ? scope.getFieldData().search.showNodes : scope.getFieldData().showNodes;

        if (!HCFunctions.isArray(data))
            data = [data];

        return $.map(data, function (obj) {
            text = '';

            if (HCFunctions.isString(obj))
                obj = {id: obj, text: obj};

            if (multiLevel) {
                function format(data) {
                    var formatted = {};
                    var children = data[multiLevel['field_children']];

                    formatted.id = data.id;
                    formatted.text = data[multiLevel['field_name']];

                    if (children && children.length > 0) {
                        formatted.children = [];
                        $.each(children, function (child_key, child_value) {
                            formatted.children.push(format(child_value));
                        });
                    }

                    return formatted;
                }

                return format(obj);
            }
            else {
                $.each(obj, function (key, value) {
                    if (Object.size(obj) === 1 || key !== 'id' && value !== '')
                        if (nodeNames) {
                            if (nodeNames.indexOf(key) >= 0)
                                text += value + ' | ';
                            else if (HCFunctions.isArray(value)) {
                                $.each(nodeNames, function (node_key, node_value) {
                                    if (node_value.indexOf('{lang}') !== -1) {
                                        node_value = HCFunctions.replaceBrackets(node_value, {'lang': HCFunctions.getTranslationsLanguageElementIndex(HCService.FRONTENDLanguage, value)});
                                        text += HCFunctions.pathIndex(obj, node_value) + ' | ';
                                        return false;
                                    }
                                    else if (value[node_value] && value[node_value] !== '' && value[node_value] !== null)
                                        text += value[node_value] + ' | ';
                                });
                            }
                        }
                        else
                            text += value + ' | ';
                });

                text = text.substr(0, text.length - 3);
                return {id: obj.id, text: text};
            }
        });
    }

    var theSelectItem;

    /**
     * Enabling or updating select2 field
     */
    this.updateSelectComponent = function () {
        theSelectItem = $('#' + this.uniqueFieldID);
        var options = this.getFieldData().search;

        if (this.getFieldData().editType !== 1)
            if (this.getFieldData().sortable)
                selectizeOptions.plugins.push('drag_drop');

        $.extend(true, options, selectizeOptions);

        theSelectItem = theSelectItem.selectize(options)[0].selectize;
        theSelectItem.addOption({value: null, text: ''});
        theSelectItem.setValue(null);
        theSelectItem.on('change', function (){
            scope.triggerContentChange();
        });

        if (this.getFieldData().new)
            this.addNewOption(this.getFieldData().new, this.newOptionCreated);
        else
            this.getParent().find('.select2-container').removeAttr('style');
    };

    /**
     * Adding new option which was just created to the list
     * @param response
     */
    this.newOptionCreated = function (response) {

        scope.addCoreOption(response);

        response = formatData([response], scope.getFieldData().new.showNodes)[0];
        theSelectItem.addOption({value: response.id, text: response.text});

        if (scope.getFieldData().sortable) {
            theSelectItem.addItem(response.id);
        }
        else
            theSelectItem.setValue(response.id);

        scope.triggerContentChange();
    };

    /**
     * Adding new Option button
     */
    this.addNewOption = function (options, callBack) {
        var newOption = $('<div class="btn btn-success btn-add-new-option" style="float:right"><i class="fa fa-fw fa-plus"></i></div>');
        newOption.bind('click', handleNewOption);

        this.getParent().find('.form-control.selectize-control').css('width', 'calc(100% - 60px)');
        this.getParent().find('.form-control.selectize-control').css('margin-right', '5px');
        this.getParent().find('.form-control.selectize-control').css('float', 'left');
        this.getParent().append(newOption);
        this.getParent().append('<div style="clear:both"></div>');

        function handleNewOption() {
            HCService.PopUp.Pop({
                label: 'New Record',
                type: 'form',
                config: {
                    structureURL: getStructureURL()
                },
                callBack: callBack
            });
        }

        function getStructureURL() {
            var url = options.url;

            if (options.require)
                $.each(options.require, function (key, value) {
                    if (url.indexOf('?') === -1)
                        url += '?' + value + '=' + scope.form.content[value];
                    else
                        url += '&' + value + '=' + scope.form.content[value];
                });

            return url;
        }
    };

    /**
     * Returning field value
     *
     * @returns {*}
     */
    this.getValue = function () {
        var context = $('.is-fo-dropdown-list option[value=' + this.contentData + ']', this.innerHTML).context;
        var selectedIndex = context.selectedIndex;

        if (selectedIndex === -1)
            selectedIndex = 0;

        return context[selectedIndex].text;
    };

    /**
     * Getting content data
     *
     * @method getContentData
     * @returns {*}
     */
    this.getContentData = function () {
        if (!this.inputField)
            return null;

        if (theSelectItem)
            return theSelectItem.getValue();

        var data = this.inputField.val();

        if (data && this.getFieldData().search && this.getFieldData().search.maximumSelectionLength === 1)
            data = data.toString();

        if (data === "")
            data = null;

        return data;
    };

    var disabled = false;

    this.disable = function ()
    {
        disabled = true;

        if (theSelectItem)
            theSelectItem.disable();
    };

    this.enable = function ()
    {
        if (theSelectItem)
            theSelectItem.enable();
    }
};