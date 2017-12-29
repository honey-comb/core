HCService.FormManager.Objects.Email = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'email';

    var scope = this;

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        this.innerHTML = this.inputField = $('<input class="form-control" id="' + this.uniqueFieldID + '" type="text" placeholder="' + this.getPlaceHolder() + '">' + this.getAnnotation());
    };

    /**
     * This function validates the data.
     * This function might be overridden, else it will show a Warning
     *
     * @method validateContentData
     * @return {Boolean} is content valid
     */
    this.validateContentData = function ()
    {
        var valid = true;
        if (this.getContentData() == null && scope.isRequired())
            valid = false;

        if(this.getContentData() != null && !HCFunctions.validateEmail(this.getContentData()))
            valid = false;

        if (!valid)
        {
            scope.showFieldError(scope.getLabel() + ' is not valid!');
            return false;
        }

        this.hideFieldError();
        return true;
    };
};