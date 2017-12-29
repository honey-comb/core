HCService.FormManager.Objects.Button = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    var scope = this;

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'button';

    /**
     * Call function when button is clicked
     *
     * @type {function}
     */
    this.handleClick = undefined;

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        this.innerHTML = this.inputField = $('<button tabindex="0" class="btn-primary" type="button" id="' + this.uniqueFieldID + '"></button>');

        if (this.getFieldData().attributes)
        {
            $.each(this.getFieldData().attributes, function (key, value)
            {
                scope.inputField.attr(key, value);
            });
        }

        this.inputField.addClass('btn');
        this.inputField.addClass(this.getFieldData().class);
        this.enable();
    };

    /**
     * Returning label html as empty string
     *
     * @returns {string}
     */
    this.getLabelHTML = function ()
    {
        return '';
    };

    /**
     * Handle button click
     */
    function handleClickLocal()
    {
        switch (scope.fieldData.type)
        {
            case 'submit':
                break;
        }

        scope.handleClick();
    }

    /**
     * Disable button
     */
    this.disable = function ()
    {
        this.inputField.unbind();
        this.inputField.removeClass('is-button');
        this.inputField.addClass('disabled hc-button-disabled');
    };

    /**
     * Enable button
     */
    this.enable = function ()
    {
        this.inputField.removeClass('disabled hc-button-disabled');
        this.inputField.addClass('is-button');
        this.inputField.html(this.getLabel());

        this.inputField.unbind();
        this.inputField.bind('click', handleLocalClick);
    };

    /**
     * Handling button click
     */
    function handleLocalClick()
    {
        if (scope.handleClick)
            scope.handleClick();
    }
};