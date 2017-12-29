HCService.FormManager.Objects.CheckBoxList = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'checkBoxList';

    /**
     * This scope
     * @type {HCService.FormManager.Objects.CheckBoxList}
     */
    var scope = this;

    var idsHolder = [];

    var limit;

    var contentData;

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        this.innerHTML = this.inputField = $('<div class="form-check" id="' + this.uniqueFieldID + '"></div>');
    };
    /**
     * Handling options of the input field
     *
     * @method handleOptions
     */
    this.handleOptions = function ()
    {
        idsHolder = [];
        this.innerHTML.html('');

        // If no options available ending function
        if (!this.getOptions())
            return;

        var horizontal = '', disabled = '';

       /* if (this.getFieldData().limit)
            limit = this.getFieldData().limit;*/

        if (this.getFieldData().horizontal)
            horizontal = '-inline';

        if (this.getFieldData().readonly)
            disabled = "disabled";

        fillOptions(this.getOptions(), 0);

        function fillOptions(options, level)
        {
            var length = options.length;
            var padding = 'style="padding-left:' + 15 * level + 'px"';

            for (var i = 0; i < length; i++)
            {
                var option = options[i];

                idsHolder[option.id] = HCFunctions.createUUID();

                scope.innerHTML.append('<div ' + padding + ' class="form-check' + horizontal + ' ' + disabled + '"><label class="form-check-label"><input ' + disabled + ' id="' + idsHolder[option.id] + '" type="checkbox" class="form-check-input" value="' + option.id + '">' + option.label +'</label></div>');

                if (option.children)
                    fillOptions(option.children, level + 1);
            }
        }

        if (contentData)
            this.setContentData(contentData);
    };

    /**
     * Getting the list of all selected id's
     *
     * @returns {Array}
     */
    this.getContentData = function ()
    {
        var selected = $("input:checkbox:checked", this.innerHTML);
        var length = selected.length;
        var result;

        if (length > 0)
        {
            result = [];

            for (var i = 0; i < length; i++)
                result.push($(selected[i]).val());
        }

        return result;
    };

    /**
     * Updating when on stage
     */
    this.updateWhenOnStageLocal = function ()
    {
        addBindsToLabel();
    };

    /**
     * Adding binds to label, so it could select / deselect all
     */
    function addBindsToLabel()
    {
        if ($('input', scope.innerHTML).filter(':not(:checked)').length === 0)
            scope.labelWrapper.unbind().bind('click', deselectAllOptions);
        else
            scope.labelWrapper.unbind().bind('click', selectAllOptions);
    }

    /**
     * Setting new data
     *
     * @method setContentData
     * @param {String} value for SingleLine
     */
    this.setContentData = function (value)
    {
        if (!value)
        {
            deselectAllOptions();
            return;
        }
        
        if (Object.keys(idsHolder).length === 0)
        {
            contentData = value;
            return;
        }
        
        if (value && !HCFunctions.isArray(value))
            value = [value];
        
        for (var i = 0; i < value.length; i++)
            if (HCFunctions.isObject(value[i]))
                $('#' + idsHolder[value[i]['id']], this.innerHTML).prop('checked', true);
            else
                $('#' + idsHolder[value[i]], this.innerHTML).prop('checked', true);

        if (this.getProperties())
            addBindsToLabel();
    
        this.triggerContentChange();
    };

    /**
     * Selecting all items
     */
    function selectAllOptions()
    {
        $('input', scope.innerHTML).prop('checked', true);
        scope.labelWrapper.unbind().bind('click', deselectAllOptions);
        scope.triggerContentChange();
    }

    /**
     * Deselecting all items
     */
    function deselectAllOptions()
    {
        $('input', scope.innerHTML).prop('checked', false);
        scope.labelWrapper.unbind().bind('click', selectAllOptions);
        scope.triggerContentChange();
    }
};