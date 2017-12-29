HCService.FormManager.Objects.TextArea = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'textArea';

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        this.innerHTML = $ ('<div></div>');
        this.inputField  = $ ('<textarea class="form-control" rows="' + this.getFieldData().rows + '" id="' + this.uniqueFieldID + '"></textarea>');
        this.innerHTML.append (this.inputField);
        this.checkForMultiLanguage ();

        this.innerHTML.append (this.getAnnotation());
    };
};