HCService.PopUp = new function ()
{
    this.Pop = function (data)
    {
        /**
         * Generating unique form id
         * @type {string}
         */

        var id = HCFunctions.createUUID();
        var modal;

        var body;

        function initialize()
        {
            if (!data.label)
                data.label = '';

            createContentHolders();
        }

        /**
         * Creating content
         *
         * @method createContent
         */
        function createContent()
        {
            var _content;

            switch (data.type)
            {
                case 'form':

                    data.config.divID = '#' + id + ' .modal-dialog .modal-content .modal-body';
                    data.config.buttonsDivID = '#' + id + ' .modal-dialog .modal-content .modal-footer';

                    var form = HCService.FormManager.createForm(data.config);
                    form.successCallBack = closePopUp;
                    break;

                default:
                    _content = data.content.getHTML();
            }

            return _content;
        }

        /**
         * Creating required content holders and pop up environment
         *
         * @method createContentHolders
         */
        function createContentHolders()
        {
            body = $('body');

            modal = $('<div class="modal fade" id="' + id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">' +
                '<div class="modal-dialog" role="document">' +
                '<div class="modal-content"></div>' +
                '</div>' +
                '</div>');

            var modalContent = $(modal.find('.modal-content'));

            var modalHeader = $('<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                '<h4 class="modal-title">' + createTitle() + ' </h4>' +
                '</div>');

            var modalBody = $('<div class="modal-body"><div class="hc-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div></div>');
            modalBody.append(createContent());

            var modalFooter = $('<div class="modal-footer"></div>');

            modalContent.append(modalHeader);
            modalContent.append(modalBody);
            modalContent.append(modalFooter);

            body.append(modal);

            modal.modal();

            modal.on('hidden.bs.modal', function () {
                modal.remove();

                if ($('.modal-backdrop').length > 0)
                    $('body').addClass('modal-open');
            });
        }

        /**
         * Creating Title
         *
         * @method createTitle
         */
        function createTitle()
        {
            return '<div class="is-popup-title">' + data.label + '</div>';
        }

        /**
         * Closing pop up and forwarding response if there is where to forward data
         *
         * @param response
         */
        function closePopUp(response)
        {
            if (data.callBack)
                data.callBack(response);

            $(modal.find('.close')).click();
        }

        initialize();
    };
};