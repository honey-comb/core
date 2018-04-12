HCService.FormManager.Objects.UploadFile = function ()
{
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    var scope = this;

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'resource';

    var inputNode;
    var totalFiles = 0;
    var label;

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function ()
    {
        label = $('<label class="hc-file-upload-label"></label>');

        var multiple = 'multiple';
        var accept   = '';
        var disabled = '';

        if (this.getFieldData().readonly)
        {
            disabled = 'disabled';
            label.addClass('disabled');
            label.addClass('hc-field-disabled')
        }

        if (this.getFieldData())
            if (this.getFieldData().uploadDataTypes)
                accept = 'accept="' + this.getFieldData().uploadDataTypes.toString() + '"';

        if (this.getFieldData().fileCount === 1)
            multiple = '';

        this.innerHTML = $('<span></span>');
        this.innerHTML.append(label);
        this.innerHTML.append(this.getAnnotation());

        inputNode = $('<input ' + disabled + ' type="file" ' + multiple + ' id="' + this.uniqueFieldID + '"' + accept + '/><span>Upload file</span></label>');

        label.append(inputNode);

        this.checkForMultiLanguage();

        this.images = $('<div id="hc-file-upload-thumb-holder" class="ui-sortable"></div>');
        this.innerHTML.append(this.images);
    };

    /**
     * updating input label
     *
     * @method updateWhenOnStageLocal
     */
    this.updateWhenOnStageLocal = function ()
    {
        if (!this.getFieldData().readonly)
        {
            HCFunctions.bind(this, this.inputField, 'change', this.contentChange);
            this.eventDispatcher.bind(this, 'remove', this.handleThumbnailRemoved);

            if (this.getFieldData().sortable)
                this.images.sortable();
        }
    };

    /**
     * Appending multi language changes
     * @param multiLanguageSelect
     */
    this.appendMultiLanguage = function (multiLanguageSelect)
    {
        $(this.innerHTML).find('label').addClass('col-xs-10');
        this.innerHTML.addClass('row form-group');
        this.innerHTML.css({'margin-right': 0, 'margin-left': 0});

        var $multi = $('<div style="width:70px; float: right"></div>');
        $multi.append(multiLanguageSelect);

        this.innerHTML.append($multi);
    };

    /**
     * Getting all of the resource ids
     *
     * @method getContentData
     * @returns {Array}
     */
    this.getContentData = function ()
    {
        var data = [];

        $.each(this.images.children(), function (i, val)
        {
            data.push(val.id);
        });

        if (this.getFieldData().fileCount == 1)
            return data.toString();

        return data;
    };

    /**
     * Rewriting parent functionality
     *
     * @method setContentData
     */
    this.setContentData = function (data)
    {

        totalFiles = 0;
        this.images.html('');
        checkFileCount();

        if (!data)
            return;

        if (HCFunctions.isArray(data))
        {
            var length = data.length;
            for (var i = 0; i < length; i++)
                scope.checkThumbnail(data[i]);
        }
        else
            scope.checkThumbnail(data);
    };

    /**
     * Checking where get thumbnail information from
     * @param data
     */
    this.checkThumbnail = function (data)
    {
        var thumbnail;
        var remove = !(this.getFieldData().editType > 0);

        if (HCFunctions.isObject(data))
        {
            thumbnail = new scope.ThumbHolder({url: this.getFieldData().viewURL + '/' + data.id, id: data.id}, remove);
            scope.images.append(thumbnail.getHTML());
        }
        else
        {
            thumbnail = new scope.ThumbHolder({url: this.getFieldData().viewURL + '/' + data, id: data}, remove);
            scope.images.append(thumbnail.getHTML());
        }

        totalFiles++;
        checkFileCount();
        this.triggerContentChange();
    };

    /**
     * Thumbnail is removed, clearing it from array holder
     * @param id
     */
    this.handleThumbnailRemoved = function (id)
    {
        delete(imageHolder[id]);
        totalFiles--;
        checkFileCount();
        scope.triggerContentChange();
    };

    var imageHolder = [];

    /**
     * When file is added upload it to server
     *
     * @method contentChange
     * @param e
     */
    this.contentChange = function (e)
    {
        var files  = e.currentTarget.files;
        var length = files.length + totalFiles;
        var thumbnail;

        if (this.getFieldData().fileCount != 0 && length > this.getFieldData().fileCount)
            HCFunctions.notify('warning', 'To many files selected. Maximum: ' + this.getFieldData().fileCount);
        else
        {
            for (var i = 0; i < length; i++)
            {
                if (isValid(files[i]))
                {
                    thumbnail                  = new this.ThumbHolder(files[i], true);
                    imageHolder[thumbnail._id] = thumbnail;
                    this.images.append(thumbnail.getHTML());
                    totalFiles++;
                }
            }
        }

        // reset input field
        $('#' + this.uniqueFieldID).val('');
        checkFileCount();

        function isValid(file)
        {
            var success = true;

            if (file)
            {
                if (scope.getFieldData())
                {
                    var dataTypes = scope.getFieldData().uploadDataTypes;

                    //checking the dataTypes
                    if (dataTypes && dataTypes.length > 0)
                        if (dataTypes.indexOf(file.type) == -1)
                        {
                            HCFunctions.notify('warning', 'Resources type is not allowed: ' + file.type + '. Only: ' + dataTypes);
                            success = false;
                        }

                    if (scope.getFieldData().uploadSize)
                        if (file.size > scope.getFieldData().uploadSize)
                        {
                            HCFunctions.notify('warning', 'Resources is to big: ' + HCFunctions.FileSize.toYB(file.size) + '. Maximum is ' + HCFunctions.FileSize.toMB(scope.getFieldData().uploadSize));
                            success = false;
                        }
                }
            }
            else
                success = false;

            return success;
        }
    };

    /**
     * Checking the file count
     */
    function checkFileCount()
    {
        if (scope.getFieldData().fileCount <= totalFiles)
        {
            label.css('opacity', '0.3');
            inputNode.attr('disabled', true);
        }
        else
        {
            label.css('opacity', '1');
            inputNode.removeAttr('disabled');
        }
    }

    /**
     * Thumbnail holder
     *
     * @param data information about thumbnail
     * @param remove can thumbnail be removed
     * @constructor
     */
    this.ThumbHolder = function (data, remove)
    {
        if (!data)
            return;

        this._id = HCFunctions.createUUID();

        var thumbScope  = this;
        var html;
        var image;
        var _isSelected = false, name, progressLine, progressText;

        /**
         * Initializing
         */
        function initialize()
        {
            if (data.id)
                placeOnStage();
            else
                uploadView();
        }

        /**
         * Place uploaded thumbnail on stage
         */
        function placeOnStage()
        {
            var sortable = '';
            var existingHTML;

            if (scope.getFieldData().sortable)
                sortable = 'ui-sortable';

            if (html)
            {
                existingHTML = html;
                existingHTML.attr('id', data.id);
                existingHTML.html('');
            }
            else
                html = existingHTML = $('<div class="hc-image-holder ' + sortable + '" id="' + data.id + ' "></div>');

            image = $('<div class="hc-form-input-image" style="background-image:url(' + data.url.replace(' ', '') + '/100/100)"></div>');

            existingHTML.append(image);

            if (remove)
            {
                var closeButton = $('<div class="hc-remove-resource"><i class="fa fa-trash" aria-hidden="true"></i></div>');
                existingHTML.append(closeButton);
                closeButton.unbind().bind('click', removeThumbnail)
            }

            scope.triggerContentChange();
        }

        /**
         * Remove thumbnail
         */
        function removeThumbnail()
        {
            html.remove();

            if (scope.eventDispatcher)
                scope.eventDispatcher.trigger('remove', thumbScope._id);
        }

        /**
         * Create Upload view
         */
        function uploadView()
        {
            html  = $('<div class="hc-image-holder"></div>');
            image = $('<div class="image "></div>');

            name = $('<div class="wordwrap"></div>');

            var progressHolder = $('<div class="hc-file-upload-holder"></div>');

            progressLine = $('<div class="hc-file-upload-progress"></div>');
            progressText = $('<div class="hc-file-upload-text"></div>');
            progressHolder.append(progressLine);
            progressHolder.append(progressText);

            image.append(name);
            html.append(image);
            html.append(progressHolder);

            uploadFile();
        }

        /**
         * Upload file to server
         */
        function uploadFile()
        {
            var uploader        = new HCLoader.FileUploader();
            uploader.updateType = ['new', 'object'];
            uploader.eventDispatcher.bind(scope, 'complete', uploadDone);
            uploader.eventDispatcher.bind(scope, 'progress', loadProgress);

            var url  = scope.getFieldData().uploadURL ? scope.getFieldData().uploadURL : data.uploadURL;
            var file = data.file ? data.file : data;

            uploader.upload(url, file);
        }

        /**
         * Showing load progress
         *
         * @param e
         */
        function loadProgress(e)
        {
            e = parseFloat(e);

            if (e >= 100)
                progressText.html('');
            else
                progressText.html(e);

            if (e <= 98)
                progressLine.width(e);

        }

        /**
         * Upload has beed completed
         *
         * @param e
         */
        function uploadDone(e)
        {
            progressLine.hide();

            if (e)
            {
                try
                {
                    e = JSON.parse(e);
                }
                catch (error)
                {
                    HCFunctions.notify('error', error);
                }

                if (e.success == false)
                {
                    HCFunctions.notify('error', e.message);
                    removeThumbnail();
                }
                else
                {
                    data.id  = e.id;
                    data.url = e.url;
                    placeOnStage();
                }
            }
        }

        /**
         * Selecting thumbnail
         */
        function select()
        {
            if (_isSelected)
            {
                image.removeClass('hc-thumb-selected');
            }
            else
            {
                image.addClass('hc-thumb-selected');
            }

            _isSelected = !_isSelected;
        }

        /**
         * Is thumbnail selected
         *
         * @returns {boolean}
         */
        this.isSelected = function ()
        {
            return _isSelected;
        };

        /**
         * Returning ID of the thumbnail resource
         *
         * @returns {string}
         */
        this.id = function ()
        {
            return data.id;
        };

        /**
         * Getting HTML of the thumbnail content
         * @returns {*}
         */
        this.getHTML = function ()
        {
            return html;
        };

        initialize();
    };

};