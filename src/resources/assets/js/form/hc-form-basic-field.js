HCService.FormManager.Objects.BasicField = function ()
{
    /**
     * BasicField scope instance
     *
     * @type {HCService.FormManager.Objects.BasicField}
     */
    var localScope = this;

    /**
     * Parent scope
     *
     * @type {HCService.FormManager.Objects.BasicField}
     */
    this.parentScrope = this;

    /**
     * Events dispatcher
     * @type jQuery Object
     */
    this.eventDispatcher = new HCObjects.HCEventDispatcher();

    /**
     * Unique identification number for a field
     * @type String
     */
    this.uniqueFieldID = HCFunctions.createUUID();

    /**
     * Label wrapper used for checkbox list
     *
     * @type {undefined}
     */
    this.labelWrapper = undefined;

    /**
     * Field metadata
     * @type Object
     */
    var fieldData = null;

    /**
     * Field Properties
     * @type Object
     */
    var fieldProperties = null;

    /**
     * Field Options (used by lists)
     * @type Object
     */
    var fieldOptions = null;

    /**
     * Used in dependencies to restore its previous state
     */
    var innerRequired;

    /**
     * Parent DIV field holder
     */
    var parent;

    /**
     * When field has multiple dependencies, storing validation for each of the field
     *
     * @type {Object}
     */
    this.dependencyArray = {};

    /**
     * indicating field data availability in case of dependencies
     * @type Object
     */
    this.available = true;

    /**
     * Multi Language select options
     */
    var multiLanguageSelect;

    /**
     * Is field readonly
     * @type Boolean
     */
    var readonly;

    /**
     * Dependency values which might be used in other fields
     */
    this.dependencyValues = {};

    /**
     * jQuery object of input field;
     * @type Object
     */
    this.inputField = null;

    /**
     * Setting field metadata and field properties
     *
     * @method setFieldData
     * @param {object} data object which contains metadata and properties.
     */
    this.setFieldData = function (data)
    {
        fieldData       = data;
        fieldProperties = data.properties;
        fieldOptions    = data.options;
        innerRequired   = data.required;
        readonly        = data.readonly;

        this.updateDependencyArray();
        this.handleProperties();
        this.handleOptions();

        if (this.fieldName != 'button')
            this.handleReadonly();
    };

    /**
     * Returning field data
     *
     * @returns {Object}
     */
    this.getFieldData = function ()
    {
        return fieldData;
    };

    /**
     * Returns Field id
     *
     * @method getFieldID
     * @return {string} id
     */
    this.getFieldID = function ()
    {
        return fieldData.fieldId;
    };

    /**
     * Returning field properties
     *
     * @returns {Object}
     */
    this.getProperties = function ()
    {
        return fieldProperties;
    };

    /**
     * Returning field options
     *
     * @returns {Object}
     */
    this.getOptions = function ()
    {
        return fieldOptions;
    };

    /**
     * Initial dependency, set all to false
     */
    this.updateDependencyArray = function ()
    {
        $.each(fieldData.dependencies, function (key, value)
        {
            localScope.dependencyArray[value.field_id] = false;
        })
    };

    /**
     * This function should format the field, based on provided properties.
     * This function might be overridden
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
    };

    /**
     * This function should add selection options, based on provided options.
     * This function might be overridden
     *
     * @method handleProperties
     */
    this.handleOptions = function ()
    {
    };

    /**
     * Checking if field can be edited
     */
    this.handleReadonly = function ()
    {
        if (readonly)
            this.disable();
    };

    /**
     * enabling input field
     */
    this.enable = function ()
    {
        this.inputField.attr('readonly', false);
    };

    /**
     * disabling input field
     */
    this.disable = function ()
    {
        this.inputField.attr('readonly', true);
    };

    /**
     * Get the placeholder name
     *
     * @returns {String}
     * @method getPlaceHolder
     */
    this.getPlaceHolder = function ()
    {
        if (fieldData.placeholder)
            return this.getLabel();

        return '';
    };

    /**
     * Returns Label string
     *
     * @method getLabel
     * @return {string} Label
     */
    this.getLabel = function ()
    {
        return fieldData.label;
    };

    /**
     * Getting annotation
     *
     * @method getAnnotation
     * @returns {String}
     */
    this.getAnnotation = function ()
    {
        if (fieldData.note)
            return '<div style="width: 100%; padding-bottom:10px;"><small class="text-muted pull-right">' + fieldData.note + '</small></div>';

        return '';
    };

    /**
     * Returns generated Field HTML div
     *
     * @method getHTML
     * @return {Array} HTML div
     */
    this.getHTML = function ()
    {
        var array = [this.getLabelHTML()];

        if (this.innerHTML instanceof Array)
        {
            for (var i = 0; i < this.innerHTML.length; i++)
                array.push(this.innerHTML[i]);
        }
        else
            array.push(this.innerHTML);

        return array;
    };

    /**
     * Returns generated Field label HTML div
     *
     * @method getLabelHTML
     * @return {string} HTML div
     */
    this.getLabelHTML = function ()
    {
        //TODO annotations on click / rollover
        var placeholder = '';

        if (!this.getLabel())
            return '';

        if (fieldData.placeholder)
            placeholder = 'hidden';

        return this.labelWrapper = $('<label class="hc-fo-field-label ' + placeholder + '">' + this.getLabel() + ' ' + getRequiredHTML() + '</label>');
    };

    /**
     * Getting required html
     *
     * @returns {*}
     */
    function getRequiredHTML()
    {
        if (fieldData.required && fieldData.requiredVisible)
            return '<span class="text-danger">*</span>';

        return '';
    }

    /**
     * Returns value if the field required or not
     *
     * @method isRequired
     * @return {Boolean} required
     */
    this.isRequired = function ()
    {
        return fieldData.required;
    };

    /**
     * Set field parent
     *
     * @method setParent
     * @param {object} value returns parent div.
     */
    this.setParent = function (value)
    {
        parent = value;
    };

    /**
     * Getting parent
     * @returns {Object|*|Window}
     */
    this.getParent = function ()
    {
        return parent;
    };

    /**
     * When field needs to be on stage, to be created
     * this function will be called.
     * This applies mostly to fields which are using external libraries
     * This function might be overridden
     *
     * @method updateWhenOnStage
     */
    this.updateWhenOnStage = function ()
    {
        this.inputField = $('#' + this.uniqueFieldID);

        if (fieldData.class)
            this.getParent().addClass(fieldData.class);

        this.addEvents();
    };

    /**
     * Adding event listeners for field content to change
     *
     * @method addEvents
     */
    this.addEvents = function ()
    {
        $(this.inputField).unbind();

        HCFunctions.bind(this, this.inputField, 'blur', this.validateContentData);
        HCFunctions.bind(this, this.inputField, 'change', this.triggerContentChange);
    };

    /**
     * Function which triggers event for ContentChange
     *
     * @method triggerContentChange
     */
    this.triggerContentChange = function ()
    {
        if (fieldData.multiLanguage)
        {
            var index = this.getContentLanguageElementIndex();

            if (index >= 0)
            {
                validateIndex(index);
                if (localScope.form)
                    localScope.form.content.translations[index][localScope.getFieldID()] = this.getContentData();
            }
        }
        else if (this.form)
            this.form.content[this.getFieldID()] = this.getContentData();

        this.eventDispatcher.trigger('contentDataChange', this);
    };

    /**
     * Checking if multi language index exists, if not creating one
     * @param index
     */
    function validateIndex(index)
    {
        if (!localScope.form.content.translations[index])
        {
            localScope.form.content.translations[index]                  = {};
            localScope.form.content.translations[index]['language_code'] = localScope.form.currentLanguage;
        }
    }

    /**
     * This function validates the data.
     * This function might be overridden, else it will show a Warning
     *
     * @method validateContentData
     * @return {Boolean} is content valid
     */

    this.validateContentData = function ()
    {
        if (!this.form)
            return true;

        if (fieldData.requiredLanguages)
        {
            var missingLanguages = HCFunctions.clone(fieldData.requiredLanguages);
            var languagePosition;

            $.each(this.form.content.translations, function (key, value)
            {
                languagePosition = missingLanguages.indexOf(value.language_code);

                if (languagePosition >= 0 && value[localScope.getFieldID()])
                    missingLanguages.splice(languagePosition, 1);
            });

            if (missingLanguages.length > 0)
            {
                this.showFieldError(this.getLabel() + ' missing languages: ' + missingLanguages.toString());
                return false;
            }
        }
        else if (this.isRequired() && (this.getContentData() === null || this.getContentData() === ''))
        {
            this.showFieldError(this.getLabel() + ' is empty!');
            return false;
        }

        this.hideFieldError();
        return true;
    };

    /**
     * Method will be called after a field is on stage and field
     * needs to be updated when updateWhenOnStage is not enough.
     *
     * @method updateWhenOnStageLocal
     */
    this.updateWhenOnStageLocal = function ()
    {
    };

    /**
     * Adding default value
     *
     * @method setDefaultValue
     */
    this.setDefaultValue = function ()
    {
        if (fieldData.value)
            this.setContentData(fieldData.value);
    };

    /**
     * Setting data for the field
     *
     * @method setContentData
     * @param {object} data The available data for Field.
     */
    this.setContentData = function (data)
    {
        if (!this.available && data)
            this.invisibleValue = data;
        else
            this.inputField.val(data);

        this.triggerContentChange();

    };

    /**
     * Getting content data
     *
     * @method getContentData
     * @returns {*}
     */
    this.getContentData = function ()
    {
        var data = this.inputField.val();

        if (!data && this.invisibleValue)
            data = this.invisibleValue;

        if (data === "")
            return null;

        return data;
    };

    /**
     * When field is required and entered after the Error has been showed, this function will remove highlight
     * This function might be overridden
     *
     * @method hideFieldError
     */
    this.hideFieldError = function ()
    {
        //TODO make possibility for fields to have warning and danger types
        parent.removeClass('has-danger');

        if (fieldData.required)
            parent.addClass('has-success');

        this.inputField.addClass('form-control-success');
        this.form.enableSubmit('field_' + this.uniqueFieldID);
    };

    /**
     * When field is required but not entered, this function will highlight it
     * This function might be overridden.
     *
     * @method showFieldError
     * @param {string} value Error message
     */
    this.showFieldError = function (value)
    {
        parent.addClass('has-danger');
        this.inputField.removeClass('form-control-success');
        this.inputField.removeClass('has-success');
        this.showErrorMessage(value);

        this.form.disableSubmit('field_' + this.uniqueFieldID);
    };

    /**
     * This function will show ERROR message inside the field or FORM
     * This function might be overridden
     *
     * @method showErrorMessage
     * @param {string} value Error message
     */
    this.showErrorMessage = function (value)
    {
        HCFunctions.notify('warning', value);
    };

    /**
     * Checking for multi language
     */
    this.checkForMultiLanguage = function ()
    {
        var availableLanguages = this.form.getAvailableLanguages();

        if (availableLanguages.length === 0 || !fieldData.multiLanguage)
            return;

        multiLanguageSelect = $('<select id="multi-language-selector" class="form-control col-xs-2"></select>');

        if (readonly)
            multiLanguageSelect.attr('disabled', true);

        $.each(availableLanguages, function (key, value)
        {
            multiLanguageSelect.append('<option>' + value + '</option>');
        });

        multiLanguageSelect.change(function (e)
        {
            if (e.originalEvent)
            {
                localScope.form.currentLanguage = e.currentTarget.value;
                localScope.form.eventDispatcher.trigger('languageChanged');
            }
        });

        localScope.form.eventDispatcher.bind(localScope, 'languageChanged', this.languageSelectionChanged);

        this.appendMultiLanguage(multiLanguageSelect);
    };

    /**
     * Appending multi language changes
     * @param multiLanguageSelect
     */
    this.appendMultiLanguage = function (multiLanguageSelect)
    {
        this.inputField.addClass('col-xs-10');
        this.innerHTML.addClass('row form-group');
        this.innerHTML.css({'margin-right': 0, 'margin-left': 0});

        var $multi = $('<div style="width:70px; float: right"></div>');
        $multi.append(multiLanguageSelect);

        this.innerHTML.append($multi);
    };

    /**
     * MultiLanguage option changed
     */
    this.languageSelectionChanged = function ()
    {
        multiLanguageSelect.val(localScope.form.currentLanguage);
        this.populateContent();
    };

    /**
     * Populating content, retrieving data from form content
     */
    this.populateContent = function ()
    {
        if (fieldData.ignoreContent)
        {
            this.triggerContentChange();
            return;
        }

        if (fieldData.multiLanguage)
        {
            var index = this.getContentLanguageElementIndex();

            if (index >= 0)
            {
                validateIndex(index);

                //transforming values for multi language purposes
                if (localScope.form.content.translations[index][localScope.getFieldID()] === undefined)
                    localScope.form.content.translations[index][localScope.getFieldID()] = localScope.form.content.translations[index][localScope.getFieldID().replace('translations.', '')];

                localScope.setContentData(localScope.form.content.translations[index][localScope.getFieldID()]);
            }
        }
        else
        {
            if (localScope.getFieldData().key !== undefined) {
                var value = HCFunctions.pathIndex(localScope.form.content, localScope.getFieldData().key);

                if (value === false)
                    value = undefined;

                localScope.setContentData(value);
            }
            else
                localScope.setContentData(localScope.form.content[localScope.getFieldID()]);
        }
    };

    /**
     * Finding index of translations array, based on currentLanguage
     *
     * @returns {string}
     */
    this.getContentLanguageElementIndex = function ()
    {
        return HCFunctions.getTranslationsLanguageElementIndex(localScope.form.currentLanguage, localScope.form.content.translations);
    };

    /**
     * hide field parent
     *
     * @method hideParent
     */
    this.hideParent = function ()
    {
        if (this.getFieldData().disableHiding)
            return;

        this.available = false;

        fieldOptions = null;
        parent.addClass('hidden');

        if (innerRequired === 1)
            this.getFieldData().required = false;
    };

    /**
     * show field parent
     *
     * @method showParent
     */
    this.showParent = function ()
    {
        if (this.getFieldData().dependencies && !this.dependencyUpdated)
            return;
        else if (this.getFieldData().dependencies && !this.dependencyAllow)
            return;

        this.available = true;
        // display table for design purpose
        parent.removeClass('hidden');
        parent.removeAttr('style');

        if (innerRequired === 1)
            this.getFieldData().required = true;

        if (this.invisibleValue)
            this.setContentData(this.invisibleValue);

        this.invisibleValue = null;
    };

    /**
     *
     * Updating dependencies
     *
     * @param value
     * @returns {boolean}
     */
    this.updateDependencies = function (value)
    {
        this.dependencyUpdated = true;

        var contentData  = value.getContentData();
        var dependencies = this.getFieldData().dependencies;
        var success      = true;

        if (HCFunctions.isArray(dependencies))
        {
            $.each(dependencies, function (key, dependency)
            {
                if (dependency.field_id === value.getFieldID())
                    localScope.dependencyArray[value.getFieldID()] = validateDependency(dependency);
            });

            $.each(localScope.dependencyArray, function (successKey, successValue)
            {
                if (!successValue)
                    success = false;
            });
        }
        else
            success = validateDependency(dependencies);

        this.dependencyAllow = success;
        return success;

        /**
         * Validating dependency
         *
         * @param dependency
         * @returns {boolean}
         */
        function validateDependency(dependency)
        {
            var success = false;

            if (dependency.field_value)
            {
                if (HCFunctions.isArray(dependency.field_value))
                    $.each(dependency.field_value, function (key, value)
                    {
                        if (value === contentData)
                            success = true;
                    });
                else if (dependency.field_value === contentData)
                    success = true;
            }
            else if (contentData !== null && contentData !== '')
                success = true;

            if (success && dependency.options_url)
                localScope.loadOptions(dependency, value);

            if (success)
                localScope.dependencyValues[value.getFieldID()] = value.getContentData();
            else
                delete localScope.dependencyValues[value.getFieldID()];

            return success;
        }
    };

    /**
     * Loading options
     */
    this.loadOptions = function (dependency, sourceField)
    {
        var variable = sourceField.getContentData();
        var url      = dependency.options_url;
        var variableID;

        if (variable && !HCFunctions.isString(variable))
            variable = variable.toString();

        if (dependency.send_as)
            variableID = dependency.send_as;
        else
            variableID = sourceField.getFieldID();

        var loader;
        loader = new HCLoader.BasicLoader();
        loader.dataTypeJSON();

        if (variable)
            loader.addVariable(variableID, variable);

        loader.load(optionsLoaded, null, this, url);
    };

    /**
     * Options has been loaded
     *
     * @param value
     */
    function optionsLoaded(value)
    {
        fieldOptions = value;
        localScope.handleOptions();
    }

    /**
     * Adding loaded data to the options array
     *
     * @param data
     */
    this.addCoreOption = function (data)
    {
        fieldOptions.push (data);
    };
    /**
     * Adding loaded data to the options array
     *
     * @param data
     */
    this.addCoreOption = function (data)
    {
        if (!fieldOptions)
            fieldOptions = [];

        fieldOptions.push (data);
    };

    /**
     * Clearing options
     */
    this.clearOptions = function (data)
    {
        fieldOptions = null;

        if (data)
            fieldOptions = data;
    };

    /**
     * Returning Selected item data
     */
    this.getSelectedOptionData = function ()
    {
        var id = this.getContentData();
        var result = null;

        if (id === "")
            return result;

        if (HCFunctions.isObject(fieldOptions))
            return fieldOptions;

        $.each(fieldOptions, function (key, value)
        {
            if (value.id === id)
                result = value;
        });

        return result;
    };

    /**
     * Executing additional tasks in main field
     */
    this.updateDependenciesLocal = function (value)
    {
    };
};