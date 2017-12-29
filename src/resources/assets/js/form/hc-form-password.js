HCService.FormManager.Objects.Password = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'password';

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        this.innerHTML = this.inputField = $('<input class="form-control" id="' + this.uniqueFieldID + '" type="password" placeholder="' + this.getPlaceHolder() + '">' + this.getAnnotation());
    };
};