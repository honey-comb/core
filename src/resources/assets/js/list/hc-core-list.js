HCService.List = {};

HCService.List.Core = function ()
{
    /**
     * List configuration
     */
    var configuration;

    /**
     *
     * Local scope
     *
     * @type {HCService.List.Core}
     */
    var localScope = this;
    
    /**
     * Main container where all content will be
     */
    this.mainContainer = undefined;
    
    /**
     * All action items in one place
     */
    this.actionListItems = undefined;
    
    /**
     * parameter which defines should the full data be returned to handleSuccessCreation or URL
     */
    this.returnData = undefined;

    /**
     * Filter list parameters
     */
    this.filterParameters = {};
    
    this.initializeCore = function (config)
    {
        configuration = config;
        
        if (!configuration.div || configuration.div == '')
        {
            HCFunctions.notify('error', "No div", "No div specified!", "error");
            return false;
        }
        
        this.mainContainer = $(configuration.div);

        createActionList();
        createFiltersList();
        this.mainContainer.append('<div class="clear"></div>');
        
        this.createContentList();
    };
    
    /**
     * Creating content
     */
    this.createContentList = function ()
    {
        
    };
    
    /**
     * Creating action list
     */
    function createActionList()
    {
        localScope.actionListItems = [];
        
        var actions = $('<div class="hc-action-list flex"></div>');

        $.each(configuration.actions, function (key, value)
        {
            switch (value)
            {
                case 'delete':
                    
                    actions.append(createActionDelete());
                    break;
                
                case 'new':
                    
                    actions.append(createActionNew());
                    break;
                /* TODO merge button and it's action
                case 'merge':
                    
                    actions.append(createActionMerge());
                    break;*/
                
                case 'restore':
                    
                    actions.append(createActionRestore());
                    break;
                
                case 'filter':
                    
                    actions.append(createActionFilter());
                    break;
                
                case 'search':
                    
                    actions.append(createActionSearch());
                    break;
            }
            
            if (configuration.customActions)
            {
                if (configuration.customActions[value])
                {
                    actions.append(localScope.createCustomAction(configuration.customActions[value]));
                }
            }
        });
        
        localScope.mainContainer.append(actions);
    }
    
    /**
     * Creating custom actions
     *
     * @param data
     */
    this.createCustomAction = function (data)
    {
        console.log('createCustomAction must be overridden')
    };
    
    /**
     * Creating new functionality
     *
     * @returns {{context, element}|*|jQuery|HTMLElement}
     */
    function createActionNew()
    {
        localScope.actionListItems.new = $('<div class="btn btn-success hc-action-list-button"><i class="fa fa-fw fa-plus"></i></div>');
        
        localScope.actionListItems.new.bind('click', function ()
        {
            if (configuration.forms.new)
            {
                HCService.PopUp.Pop({
                    label: 'New Record',
                    type: 'form',
                    config: {
                        structureURL: configuration.forms.new
                    },
                    callBack: function (response)
                    {
                        if (localScope.returnData)
                            localScope.handleSuccessCreation(response);
                        else
                            localScope.handleSuccessCreation(localScope.getDataURL());
                    }
                });
            }
            else if (configuration.forms.newRecord)
            {
                window.location = configuration.forms.newRecord;
            }
        });
        
        return localScope.actionListItems.new;
    }
    
    /**
     * Creating a Delete functionality
     *
     * @returns {{context, element}|*|jQuery|HTMLElement}
     */
    function createActionDelete()
    {
        localScope.actionListItems.delete = $('<div class="btn btn-danger hc-action-list-button" style="display: flex"><i class="fa fa-fw fa-trash"></i><div class="counter"></div></div>');
        localScope.actionListItems.delete.bind('click', localScope.handleDeleteButtonClick);
        
        return localScope.actionListItems.delete;
    }
    
    /**
     * Creating restore functionality
     *
     * @returns {{context, element}|*|jQuery|HTMLElement}
     */
    function createActionRestore()
    {
        return '';
        
        //localScope.actionListItems.restore = $('<div class="btn btn-success hc-action-list-button"><i class="fa fa-fw fa-refresh"></i></div>');
        //return localScope.actionListItems.restore;
    }
    
    /**
     * Creating search functionality
     *
     * @returns {Array}
     */
    function createActionSearch()
    {
        var elements = [];
        if (!localScope.actionListItems.searchF)
            elements.push(createActionFilter());
        
        localScope.actionListItems.searchB = $('<div class="btn btn-warning hc-action-list-button"><i class="fa fa-fw fa-search-plus"></i></div>');
        elements.push(localScope.actionListItems.searchB);
        
        localScope.actionListItems.searchF.keyup(function (e)
        {
            if (e.keyCode == 13)
                startSearch();
        });
        
        localScope.actionListItems.searchB.bind('click', function ()
        {
            startSearch();
        });
        
        if (!localScope.actionListItems.searchC)
            elements.push(createActionSearchClose());
        
        return elements;
    }
    
    /**
     * Creating action filter, a search box with filter possibilities
     *
     * @returns {*}
     */
    function createActionFilter()
    {
        localScope.actionListItems.searchF = $('<input class="hc-action-list-search form-control" type="text" placeholder="Search">');
        
        localScope.actionListItems.searchF.bind('keyup', function (e)
        {
            localScope.handleFilterButtonActionClick(e.currentTarget.value);
        });
        
        return localScope.actionListItems.searchF;
    }
    
    /**
     * Creating search end button
     *
     * @returns {*|jQuery|HTMLElement}
     */
    function createActionSearchClose()
    {
        localScope.actionListItems.searchC = $('<div class="btn btn-danger hc-action-list-button"><i class="fa fa-fw fa-close"></i></div>');
        
        localScope.actionListItems.searchC.bind('click', function ()
        {
            localScope.actionListItems.searchF.val('');
            localScope.actionListItems.searchC.hide();
            localScope.handleReloadAction(localScope.getDataURL());
        });
        
        localScope.actionListItems.searchC.hide();
        
        return localScope.actionListItems.searchC;
    }
    
    /**
     *
     * After successful record creation handle the result
     *
     * @param params
     */
    this.handleSuccessCreation = function (params)
    {
        console.log('handleSuccessCreation: Must be overriden by parent');
    };
    
    /**
     * Delete button click handler
     */
    this.handleDeleteButtonClick = function ()
    {
        console.log('handleDeleteButtonClick: Must be overriden by parent');
    };
    
    /**
     * Handle search button click
     */
    this.handleFilterButtonActionClick = function (value)
    {
        console.log('handleFilterButtonActionClick: Must be overriden by parent');
    };
    
    /**
     * Handle Reloading page action
     */
    this.handleReloadAction = function (value)
    {
        console.log('handleReloadAction: Must be overriden by parent');
    };
    
    var filterListItems;
    
    /**
     * Returning filter list items
     *
     * @returns {*}
     */
    this.getFilterListItems = function ()
    {
        return filterListItems;
    };
    
    /**
     * Creating filter list
     */
    function createFiltersList()
    {
        filterListItems = {};
        
        var actions = $('<div class="hc-filter-list flex"></div>');
        var field;
        
        $.each(configuration.filters, function (key, value)
        {
            switch (value.type)
            {
                case 'dropDownList':
                    
                    field = createFilterDropDown(value);
                    break;
                
                case 'dateTimePicker':
                    
                    //field = createFilterDateTimePicker(value);
                    break;
            }
            
            field = field.getHTML();
            actions.append(field);
        });
        
        localScope.mainContainer.append(actions);
        
        $.each(filterListItems, function (key, value)
        {
            value.eventDispatcher.bind(this, 'contentDataChange', handleFilterChange);
            value.updateWhenOnStage();
            value.updateWhenOnStageLocal();
            value.setDefaultValue();

            localScope.filterParameters[value.getFieldID()] = value;

            if (value.fieldName == 'dateTimePicker')
                $('#' + value.uniqueFieldID).css('width', 130);
        });
    }
    
    /**
     * Gathering Data
     *
     * @param e
     */
    function handleFilterChange(e)
    {
        var id = e.getFieldID();

        if (e.getFieldData().customURL)
        {
            var loader = new HCLoader.BasicLoader();
            loader.methodPOST();
            loader.addVariable(e.getFieldID(), e.getContentData());
            loader.load(null, null, null, e.getFieldData().customURL);
        }
        else
        {
            localScope.filterParameters[id] = e;
            
            if (e.getContentData() == null)
                delete localScope.filterParameters[id];
            
            startSearch();
        }
    }
    
    /**
     * Creating drop down element
     * @param data
     * @returns {HCService.FormManager.Objects.DropDownList|*}
     */
    function createFilterDropDown(data)
    {
        filterListItems[data.fieldId] = new HCService.FormManager.Objects.DropDownList();
        filterListItems[data.fieldId].setFieldData(data);
        
        return filterListItems[data.fieldId];
    }
    
    
    /**
     * Creating DateTimePicker element
     * @param data
     * @returns {HCService.FormManager.Objects.DateTimePicker|*}
     */
    function createFilterDateTimePicker(data)
    {
        filterListItems[data.fieldId] = new HCService.FormManager.Objects.DateTimePicker();
        filterListItems[data.fieldId].setFieldData(data);
        
        return filterListItems[data.fieldId];
    }
    
    /**
     * Start search
     */
    function startSearch()
    {
        var value = localScope.actionListItems.searchF.val();
        
        if (value != '')
        {
            localScope.handleReloadAction(localScope.getDataURL(value));
            if (localScope.actionListItems.searchC)
                localScope.actionListItems.searchC.show();
        }
        else if (Object.size(localScope.filterParameters) > 0)
        {
            localScope.handleReloadAction(localScope.getDataURL());
            if (localScope.actionListItems.searchC)
                localScope.actionListItems.searchC.show();
        }
        else
        {
            localScope.handleReloadAction(localScope.getDataURL());
            if (localScope.actionListItems.searchC)
                localScope.actionListItems.searchC.hide();
        }
    }
    
    /**
     * Constructing URL to get data from
     *
     * @param search
     * @returns {*}
     */
    this.getDataURL = function (search)
    {
        var url = configuration.contentURL;
        var cValue;
        
        if (search && search != '')
            url += '?q=' + search;
        
        if (Object.size(localScope.filterParameters) > 0)
        {
            if (!search)
                url += '?';
            
            $.each(localScope.filterParameters, function (key, value)
            {
                if (!value.getFieldData().customURL)
                {
                    cValue = value.getContentData();
                    
                    if (value.getFieldData().ignoreFieldID)
                        url += cValue;
                    else
                        if (cValue)
                            url += '&' + key + '=' + cValue;
                }
            });
        }
        
        return url;
    };
    
    /**
     * Handle Error
     * @param e
     */
    this.handleError = function (e)
    {
        //TODO: check if all beeing displayed good
        HCFunctions.notify('error', e);
    }
};