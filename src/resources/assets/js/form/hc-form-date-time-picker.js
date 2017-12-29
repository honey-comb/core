HCService.FormManager.Objects.DateTimePicker = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'dateTimePicker';

    var scope = this;

    var input;

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        var disabled = '';

        if (this.getFieldData().editType === 1)
            disabled = 'readonly';

        this.innerHTML = $('<div class="input-group date" id="' + this.uniqueFieldID + '"></div>');
        input = $('<input type="text" class="form-control" ' + disabled + '/>');
        this.inputField = input;

        var calendarIcon = $(
            '<span class="input-group-addon">' +
            '<span class="fa fa-calendar"></span>' +
            '</span>');

        if (this.getFieldData().leftIcon)
            this.innerHTML.append([calendarIcon, this.inputField]);
        else
            this.innerHTML.append([this.inputField, calendarIcon]);
    };

    /**
     * Initializing bootstrap dateTimePicker
     */
    this.updateWhenOnStageLocal = function ()
    {
        var field = $('#' + this.uniqueFieldID);
        field.datetimepicker(this.getFieldData().properties).on('dp.change', function (ev)
        {
            scope.triggerContentChange();
        });

        if (this.getFieldData().properties.defaultDate)
            scope.triggerContentChange();
    };

    /**
     * Setting content data
     *
     * @param value
     */
    this.setContentData = function (value)
    {
        $('#' + this.uniqueFieldID).datetimepicker().children('input').val(value);
        this.triggerContentChange();
    };

    /**
     * Getting content data
     *
     * @returns {*|jQuery}
     */
    this.getContentData = function ()
    {
        return $('#' + this.uniqueFieldID).datetimepicker().children('input').val();
    };

    this.disable = function ()
    {
        input.attr('readonly', true);
    };

    this.enable = function ()
    {
        input.attr('readonly', false);
    }
};