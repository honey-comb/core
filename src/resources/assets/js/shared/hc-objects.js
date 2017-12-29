/**
 * HCObjects
 * 
 * @type {HCObjects}
 */
HCObjects = new function ()
{
    /**
     * Event dispatcher module
     * @class HCEventDispatcher
     */
    this.HCEventDispatcher = function ()
    {
        /**
         * EventHolder contains all events, functions and scopes
         * @type Object
         */
        var eventHolder = {};

        /**
         * Binding function to an event
         *
         * @method bind
         * @param {object} scope of the callback function
         * @param {string} event name
         * @param {Function} handler callback
         */
        this.bind = function (scope, event, handler)
        {
            if (!eventHolder[event])
                eventHolder[event] = [];

            eventHolder[event].push({"scope": scope, "handler": handler});
        };

        /**
         * Triggering event, launching functions which are bind to event
         *
         * @method trigger
         * @param {string} value event name
         * @param {object} data data
         */
        this.trigger = function (value, data)
        {
            if (eventHolder[value])
                for (var i = 0; i < eventHolder[value].length; i++)
                    eventHolder[value][i].handler.call(eventHolder[value][i].scope, data);
        };

        /**
         * Removing event from event handler
         *
         * @method unbind
         * @param {string} value name
         */
        this.unbind = function (value)
        {
            delete eventHolder[value];
        };

        /**
         * Checking if dispatcher has bind event
         *
         * @method hasListener
         * @param {string} value name
         */
        this.hasListener = function (value)
        {
            return eventHolder[value];
        }
    };
};