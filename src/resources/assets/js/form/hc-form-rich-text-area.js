HCService.FormManager.Objects.RichTextArea = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'richTextArea';

    /**
     * Parent scope
     *
     * @type {HCService.FormManager.Objects.BasicField}
     */
    var parentScope = this.parentScrope;

    /**
     * This scope
     * @type {HCService.FormManager.Objects.RichTextArea}
     */
    var scope = this;

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

    /**
     *
     * @method updateWhenOnStageLocal
     */
    this.updateWhenOnStageLocal = function ()
    {
        var plugins = this.getFieldData().plugins || ["advlist autolink lists link image media fullscreen wordcount preview table paste textcolor colorpicker textpattern hr"];
        var toolbar = this.getFieldData().toolbar || 'undo redo | bold italic underline | forecolor backcolor | styleselect fullscreen preview | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | media image | link';

        tinymce.init(
            {
                selector: '#' + this.uniqueFieldID,
                plugins: plugins,
                toolbar: toolbar,
                height: this.getFieldData().height || 200,
                setup : function(rt) {
                    rt.on('blur', function(e) {
                        parentScope.validateContentData();
                    });

                    rt.on('change', function (){
                        parentScope.triggerContentChange();
                    });

                    rt.on('init', function (){
                        scope.innerHTML.find('.mce-tinymce').addClass('col-xs-10');
                        scope.innerHTML.find('.mce-tinymce').css({"box-sizing": "border-box", "-moz-box-sizing": "border-box", "-webkit-box-sizing": "border-box"});
                    });
                },
                readonly : this.getFieldData().readonly
            });
    };

    /**
     * Getting content
     *
     * @returns {*}
     */
    this.getContentData = function ()
    {
        if (tinymce.editors[this.uniqueFieldID])
        {
            var data = tinymce.editors[this.uniqueFieldID].getContent();

            if (data == '')
                return null;

            return data;
        }
    };

    /**
     * Setting content
     *
     * @param data
     */
    this.setContentData = function (data)
    {
        //TODO figure out when tinymce is fully on stage
        setTimeout(function (){
            if (tinymce.editors[scope.uniqueFieldID]) {
                data = data ? data : '';
                tinymce.editors[scope.uniqueFieldID].setContent(data);
            }

            scope.triggerContentChange();
        }, 100);
    }
};


/**
 * this workaround makes magic happen
 *
 * @link http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
 */
$(document).on('focusin', function(e) {
    if ($(event.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});