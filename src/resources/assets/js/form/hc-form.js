HCService.FormManager.HCForm = function (data, availableFields)
{
    /**
     * OnComplete
     *
     * type{function}
     */
    this.onFormCreation = undefined;

    /**
     * Event dispatcher
     *
     * @type {HCObjects.HCEventDispatcher}
     */
    this.eventDispatcher = new HCObjects.HCEventDispatcher ();

    /**
     * Currently selected language
     */
    this.currentLanguage = undefined;

    /**
     * Form content data stored in one place for field to use it
     *
     * @type Object
     */
    this.content = {};

    /**
     *  Function to call after successful data commit
     *
     * @type {function}
     */
    this.successCallBack = undefined;

    /**
     *  Function to call when submitting data
     * @type {undefined}
     */
    this.submitData = undefined;

    /**
     *  Function to call when data has been loaded
     * @type {undefined}
     */
    this.dataLoaded = undefined;

    /**
     * Generating unique form id
     * @type {string}
     */

    var id = HCFunctions.createUUID ();

    /**
     * Form fields storage
     *
     * @type {Object}
     */
    var formFields = {};

    /**
     * Submit button
     *
     */
    var submitButton;

    /**
     * Form structure information
     */
    var formConfiguration;

    /**
     * Form Available languages for all multi language fields
     * @type {Array}
     */
    var availableLanguages = [];

    /**
     * Submit buttons reasons, why it should or should not be disabled
     *
     * @type Object
     */
    var disableSubmitButtonReasons = {};

    /**
     * Tab holder, contains all information about form tabs
     */
    var tabHolder;

    /**
     * Currently selected tab
     */
    var currentTab;

    /**
     * Total tabs count
     */
    var totalTabs;

    /**
     * setting scope for functions
     * @type {Object}
     */
    var scope = this;

    /**
     * Where to the data should be submitted
     */
    var storageUrl;

    /**
     * Initializing FORM
     */
    function initialize ()
    {
        if (data.structure)
            structureLoaded (data);
        else if (data.structureURL)
            loadStructure ();
        else
            console.log ('structureURL is missing.');
    }

    /**
     * Returning available languages
     *
     * @returns Array
     */
    this.getAvailableLanguages = function ()
    {
        return availableLanguages;
    };

    /**
     * Returning form all available form fields
     *
     * @returns {Object}
     */
    this.getFormFields = function ()
    {
        return formFields;
    };

    /**
     * Loading structure
     */
    function loadStructure ()
    {
        var loader;
        loader = new HCLoader.BasicLoader ();
        loader.dataTypeJSON ();
        loader.load (structureLoaded, null, this, data.structureURL);
    }

    /**
     * Create form structure
     *
     * @method structureLoaded
     * @param response form structure
     */
    function structureLoaded (response)
    {
        formConfiguration = response;

        if (formConfiguration.availableLanguages)
        {
            availableLanguages    = formConfiguration.availableLanguages;
            scope.currentLanguage = availableLanguages[0];
            scope.content.translations = {};
        }

        createForm ();
    }

    /**
     * Actual creation of form
     */
    function createForm ()
    {
        storageUrl = formConfiguration.storageUrl;

        createFormDiv ();
        createFormFields (formConfiguration.structure);
        createFormButtons (formConfiguration.buttons);

        if (data.contentID)
            loadContent ();
    }

    /**
     * After loading form structure
     * @method loadContent
     */
    function loadContent ()
    {
        scope.disableSubmit ('data-management');

        var loader;
        loader = new HCLoader.BasicLoader ();
        loader.dataTypeJSON ();
        loader.load (contentLoaded, handleError, this, storageUrl + '/' + data.contentID);
    }

    /**
     * Fill form
     *
     * @method contentLoaded
     * @param response
     */
    function contentLoaded (response)
    {
        if (data.labelFromData)
        {
            $('.is-popup-title').html(HCFunctions.pathIndex(response, data.labelFromData));
        }

        scope.content = response;

        $.each (formFields, function (key, value)
        {
            value.populateContent ();
        });

        if (scope.dataLoaded)
            scope.dataLoaded();

        scope.enableSubmit ('data-management');
    }

    /**
     * Disable submit button
     */
    this.disableSubmit = function (key)
    {
        return;

        if (!key)
            return;

        disableSubmitButtonReasons[key] = 1;

        if (submitButton)
            submitButton.disable ();
    };

    /**
     * Enable submit button
     */
    this.enableSubmit = function  (key)
    {
        if (!key)
            return;

        delete disableSubmitButtonReasons[key];

        if (Object.size (disableSubmitButtonReasons) === 0)
            if (submitButton)
                submitButton.enable ();
    };

    /**
     * Creating DIV DOM object for the form to be stored
     * @method createFormDiv
     */
    function createFormDiv ()
    {
        if ($ (data.divID).length === 0)
            $ ('body').append ('<div id=" ' + data.divID.substring (1) + '"></div>');

        $ (data.divID).html ('<form class="formContent" id="' + id + '"></form>');
    }

    /**
     * Creating form fields
     * @method createFormFields
     */
    function createFormFields (structure)
    {
        var _class;
        tabHolder = {};

        $.each (structure, function (i, fieldData)
        {
            if (availableFields[fieldData.type])
            {
                _class = '';

                if (fieldData.tabID)
                {
                    if (!tabHolder[fieldData.tabID])
                        tabHolder[fieldData.tabID] = 'tab_' + HCFunctions.createUUID ();

                    _class += tabHolder[fieldData.tabID];
                }
                else
                {
                    if (!fieldData.hidden)
                    {
                        if (!tabHolder['undefined'])
                            tabHolder['undefined'] = 'tab_' + HCFunctions.createUUID();

                        _class += tabHolder['undefined'];
                        fieldData.tabID = 'undefined';
                    }
                }

                var field         = new availableFields[fieldData.type] ();
                field.form        = scope;
                field.formWrapper = data.divID;
                field.setFieldData (fieldData);

                if (fieldData.hidden)
                    _class += ' hidden';

                var html       = $ ('<div class="' + _class + '"></div>').append (field.getHTML ());
                var finalField = {field: field, html: html, destination: $ (data.divID + ' .formContent')};

                placeFieldOnStage (finalField);

                // saving form fields into a array
                if (!formFields[field.getFieldID ()])
                    formFields[field.getFieldID ()] = field;
                else if (HCFunctions.isArray (formFields[field.getFieldID ()]))
                    formFields[field.getFieldID ()].push (field);
                else
                {
                    var list = [];
                    list.push (formFields[field.getFieldID ()]);
                    list.push (field);

                    formFields[field.getFieldID ()] = list;
                }
            }
            else
                HCFunctions.notify ('warning', 'No form field with type: ' + fieldData.type + ', is not registered.');
        });

        scope.DependencyManager.setFields (formFields);

        $.each (formFields, function (key, value)
        {
            value.formFields = formFields;
            value.setDefaultValue ();
        });

        createTabs (tabHolder);

        if (scope.onFormCreation)
            scope.onFormCreation();
    }

    /**
     * Dependency manager for showing / hiding form fields based on their dependencies
     *
     * @type {DependencyManager}
     */
    this.DependencyManager = new function ()
    {
        var fullList;
        var dependencyList;

        this.setFields = function (list)
        {
            fullList = list;

            var dependency;
            var field_id;
            dependencyList = [];

            $.each (list, function (key, field)
            {
                dependency = field.getFieldData ().dependencies;

                if (dependency)
                {
                    $.each (dependency, function (key, data)
                    {
                        field_id = data.field_id;

                        if (!dependencyList[field_id])
                        {
                            dependencyList[field_id] = [];
                            list[field_id].eventDispatcher.bind (scope, 'contentDataChange', updateDependencies);
                        }

                        dependencyList[field_id].push (field);
                        field.hideParent ();
                    });
                }
            });
        };

        /**
         * Updating dependencies based on field that has changed.
         *
         * @param value
         */
        function updateDependencies (value)
        {
            $.each (dependencyList[value.getFieldID()], function (i, field)
            {
                if (field.updateDependencies (value))
                {
                    if (totalTabs > 1)
                    {
                        if (currentTab === tabHolder[field.getFieldData ().tabID])
                            field.showParent ();
                    }
                    else
                        field.showParent ();
                }
                else
                    field.hideParent ();

                field.updateDependenciesLocal (value);
            });
        }
    };


    /**
     *
     * Creating tabs
     *
     * @param tabInfo
     */
    function createTabs (tabInfo)
    {
        totalTabs = Object.size (tabInfo);

        if (totalTabs <= 1)
            return;

        var html = $ ('<div class="form-tabs"></div>');
        var menu = $ ('<ul class="nav nav-pills"></ul>');
        var li;
        var firstLi;

        $.each (tabInfo, function (key, value)
        {
            li = $ ('<li class="nav-item">' + '<a class="nav-link" data-toggle="tab" href="' + value + '">' + key + '</a>' + '</li>');

            if (!firstLi)
            {
                firstLi = li;
                firstLi.find('a').addClass('active');
            }

            li.bind ('click', function (e)
            {
                e.preventDefault();
                changeTabContent ($ (this).find ('a').attr ('href'));
            });

            menu.append (li);
        });

        $ (data.divID).prepend (html.append(menu));

        firstLi.trigger ('click');
    }

    /**
     * Changing tab content
     *
     * @param id
     */
    function changeTabContent (id)
    {
        $.each (tabHolder, function (key, value)
        {
            if (value === id)
            {
                $.each (formFields, function (form_key, form_value)
                {
                    if (totalTabs > 1)
                    {
                        if (form_value.getFieldData ().tabID === key)
                            form_value.showParent ();

                    }
                    else
                        form_value.showParent ();
                });

                currentTab = value;
            }
            else
                $ ('.' + value).hide ();
        });
    }

    /**
     * Placing single field on stage
     *
     * @param fieldData
     */
    function placeFieldOnStage (fieldData)
    {
        //TODO: check the destination setting with ExtendedField
        fieldData.destination.first ().append (fieldData.html);
        fieldData.field.setParent (fieldData.html);
        fieldData.field.updateWhenOnStage ();
        fieldData.field.updateWhenOnStageLocal ();
    }

    /**
     * Creating form buttons
     */
    function createFormButtons (buttons)
    {
        //TODO multi language
        if (!buttons)
            buttons = [{class: "col-centered", label: "Submit", type: "submit"}];

        var buttonsHolder = $ ('<div class="hc-form-buttons-holder"></div>');
        var length        = buttons.length;
        var button;

        for (var i = 0; i < length; i++)
        {
            button = new HCService.FormManager.Objects.Button ();
            button.setFieldData (buttons[i]);
            buttonsHolder.append (button.getHTML ());

            if (buttons[i].type === 'submit')
            {
                submitButton             = button;
                submitButton.handleClick = submitData;
            }
        }

        if (!data.buttonsDivID)
            data.buttonsDivID = data.divID;

        $ (data.buttonsDivID).append (buttonsHolder);
    }

    /**
     * Submitting data
     */
    function submitData ()
    {
        if (!scope.content)
            return;

        // adjusting translations values
        if (scope.content.translations)
            $.each(scope.content.translations, function(key, value)
            {
                $.each(value, function (translation_key, translation_value)
                {
                    if(translation_key.indexOf('translations.') >= 0)
                    {
                        var keys = translation_key.split('.');
                        scope.content.translations[key][keys[1]] = translation_value;
                        delete(scope.content.translations[key][translation_key]);
                    }
                });
            });

        var valid = true;

        $.each(formFields, function (key, value){
            var _valid = value.validateContentData();

            if (valid)
                valid = _valid;
        });

        if (!valid)
            return;

        if (scope.submitData)
        {
            scope.submitData(scope.content);
            return;
        }

        scope.disableSubmit ('data-management');

        var dataLoader = new HCLoader.BasicLoader ();
        dataLoader.dataTypeJSON ();

        $.each (scope.content, function (key, value)
        {
            if (value !== null || value !== {})
                dataLoader.addVariable (key, value)
        });

        var headers = 'new';
        var url     = storageUrl;

        if (data.contentID)
        {
            headers = 'update';
            dataLoader.methodPUT ();
            url += '/' + data.contentID;
        }
        else
            dataLoader.methodPOST ();

        dataLoader.load (handleSubmitSuccess, handleError, scope, url, headers);
    }

    /**
     *
     * Handling successful form data submition
     *
     * @param response
     */
    function handleSubmitSuccess (response)
    {
        if (response.success === false)
            handleError (response);
        else if (response.redirectURL)
            window.location.href = response.redirectURL;

        // response is from backend and from dd() method
        else if (HCFunctions.isString (response) && response.indexOf ("<script> Sfdump = window.Sfdump || (function (doc)") > -1)
            handleError (response);
        else if (scope.successCallBack)
            scope.successCallBack (response);
        else if (response.success === true)
            HCFunctions.notify ('success', response);

        scope.enableSubmit('data-management');
    }

    /**
     * Loading has failed
     *
     * @method handleError
     * @param e error information
     */
    function handleError (e)
    {
        HCFunctions.notify ('error', e);
        scope.enableSubmit ('data-management');
    }

    initialize ();
};