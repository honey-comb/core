HCService.List.Endless = function (coreData)
{
    /**
     * Endless scroll for laravel pagination
     *
     * @param coreData
     * @constructor
     */

    var next = true;
    var pageCount = 0;
    var next_page_url, prev_page_url, loadedData;
    var existingListIDs = [];

    /**
     * Initializing scroll
     */
    function initialize()
    {
        if (!coreData.url)
        {
            HCFunctions.notify('error', 'URL not provided');
            return;
        }


        if (coreData.loadMore)
            coreData.loadMore.bind('click', function ()
            {
                loadPage();
            });

        loadPage();
    }

    /**
     * Reloading content data
     *
     * @param url
     */
    this.reload = function (url)
    {
        existingListIDs = [];
        next = true;
        next_page_url = prev_page_url = loadedData = undefined;

        pageCount = 0;

        coreData.url = url;
        loadPage();
    };

    /**
     * Enabling scroll follow
     * TODO: Follow not the windows but component
     */
    function enableScrollFollow()
    {
        HCFunctions.bindStrict($(window), 'scroll', followScroll);
        followScroll();
    }

    /**
     *  Following scroll of the page and when at the bottom trying to load next page
     */
    function followScroll()
    {
        //TODO: follow only one div not whole page
        if ($(window).scrollTop() == $(document).height() - $(window).height())
            loadPage();
    }

    /**
     * Loading page
     */
    function loadPage()
    {
        var url;
        HCFunctions.unbindStrict($(window), 'scroll', followScroll);

        if (coreData.loadMore)
            coreData.loadMore.hide();

        if (coreData.loader)
            coreData.loader.show();

        if (pageCount > 0)
        {
            if (next)
                url = next_page_url;
            else
                url = prev_page_url;
        }
        else
            url = coreData.url;

        var loader;
        loader = new HCLoader.BasicLoader();
        loader.dataTypeJSON();
        loader.load(dataLoaded, handleError, this, url);
    }

    /**
     * Loading has failed
     *
     * @method handleError
     * @param e error information
     */
    function handleError(e)
    {
        HCFunctions.notify('error', e.statusText);
    }

    /**
     * Data has been loaded
     * @param data
     */
    function dataLoaded(data)
    {
        if (coreData.loader)
            coreData.loader.hide();

        if (!data.current_page)
        {
            var paginationStructure = {
                data: [],
                from: null,
                last_page: 0,
                next_page_url: null,
                per_page: 20,
                prev_page_url: null,
                to: null,
                total: 0
            };

            paginationStructure.data = data;
            paginationStructure.total = data.size;
            paginationStructure.per_page = data.size;

            data = paginationStructure;
        }

        loadedData = data;

        if (pageCount == 0)
        {
            if (!data.next_page_url)
                next = false;

            if (coreData.onLoadComplete)
                coreData.onLoadComplete();

            coreData.onLoadComplete = undefined;
        }

        next_page_url = data.next_page_url;
        prev_page_url = data.prev_page_url;

        $.each(data.data, function (key, value)
        {
            if (existingListIDs.indexOf(value.id) == -1)
            {
                coreData.createElement(value);
                existingListIDs.push(value.id);
            }
        });

        if (coreData.loadMore && pageCount == 0)
        {
            if (next)
                coreData.loadMore.show();
        }
        else
        {
            if (next)
            {
                if (data.current_page != data.last_page)
                    enableScrollFollow();
            }
            else if (data.current_page != 1)
                enableScrollFollow();
        }

        if (coreData.onComplete)
            coreData.onComplete();

        pageCount++;

        if (next_page_url != null || prev_page_url != null)
            followScroll();
    }

    /**
     *
     * Getting loaded data
     *
     * @returns {*}
     */
    this.getLoadedData = function ()
    {
        return loadedData;
    };

    /**
     * Reset the page
     *
     * @param data
     */
    this.reset = function (data)
    {
        if (data.parent && data.parent.length > 0)
        {
            data.parent.html('');
            coreData.onLoadComplete = data.onLoadComplete;
            coreData.createElement = data.createElement;
            this.reload(data.url);
        }
        else
            console.log('no parent specified, not removing anything');
    };

    initialize();
};