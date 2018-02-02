let uuid = require("uuid/v4");

HC.helpers = new function () {

    let scope = this;

    /**
     * Creating a UUID
     * @returns {*}
     */
    this.uuid = function ()
    {
        return (uuid());
    };

    /**
     * Calculating object size
     *
     * @param obj
     * @returns {number}
     */
    this.objectSize = function (obj) {

        let size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    /**
     * Checking if arrays are equal
     *
     * @param arr1
     * @param arr2
     * @returns {boolean}
     */
    this.arraysEqual = function (arr1, arr2) {

        if (scope.objectSize(arr1) !== scope.objectSize(arr2))
            return false;

        let keys = Object.keys(arr1);

        for (let i = keys.length; i--;) {
            if (arr1[keys[i]] !== arr2[keys[i]]) {
                if (typeof (arr1[keys[i]]) === "object" && typeof (arr2[keys[i]]) === "object")
                    return scope.arraysEqual(arr1[keys[i]], arr2[keys[i]]);

                return false;
            }
        }

        return true;
    };

    /**
     * Creating FA button class
     *
     * @param name
     * @param prefix
     * @returns {[]}
     */
    this.faIcon = function (name, prefix) {
        if (!prefix)
            prefix = HC.globals.faPrefix;

        return [prefix, name]
    };

    /**
     * Creating button class
     *
     * @param name
     * @param disabled
     * @returns {string}
     */
    this.buttonClass = function (name, disabled) {
        if (!name)
            name = "clean";

        if (disabled)
            disabled = " disabled";
        else
            disabled = "";

        return "btn btn-" + name + disabled;
    };

    /**
     * Validating email address
     * @method validateEmail
     * @param {string} email address
     */
    this.validateEmail = function (email)
    {
        let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{1,10})?$/;

        if (!email || email === "")
            return false;

        return emailReg.test (email);
    };

    /**
     * Finding index of translations array, based on currentLanguage
     *
     * @param language
     * @param translations
     * @returns {*}
     */
    this.getTranslationsLanguageElementIndex = function (language, translations)
    {
        let _key = undefined;

        $.each (translations, function (key, value)
        {
            if (value.language_code === language)
                _key = key;
        });

        if (_key === undefined)
            _key = Object.keys(translations).length;

        return _key;
    };

    /**
     * Getting to Array element
     * obj,['1','2','3'] -> ((obj['1'])['2'])['3']
     *
     * @credit http://stackoverflow.com/a/6394168/657451
     *
     * @param obj
     * @param is
     * @returns {*}
     */
    this.multiIndex = function (obj, is)
    {
        if (!obj)
            return false;

        return is.length ? this.multiIndex (obj[is[0]], is.slice (1)) : obj
    };

    /**
     * Getting to Array element
     * obj,'1.2.3' -> multiIndex(obj,['1','2','3'])
     *
     * @param obj
     * @param is
     * @returns {*}
     */
    this.pathIndex = function (obj, is)
    {
        return this.multiIndex (obj, is.split ('.'))
    };

    /**
     * Checking if object is Array
     *
     * @param object
     * @returns {boolean}
     */
    this.isArray = function (object)
    {
        return this.isObjectMyType (object, '[object Array]');
    };

    /**
     * Checking if object is Object
     *
     * @param object
     * @returns {boolean}
     */
    this.isObject = function (object)
    {
        return this.isObjectMyType (object, '[object Object]');
    };

    /**
     * Checking if object is String
     *
     * @param object
     * @returns {boolean}
     */
    this.isString = function (object)
    {
        return this.isObjectMyType (object, '[object String]');
    };

    /**
     * Check if given object is a string or json string
     *
     * @param object
     * @returns {boolean}
     */
    this.isStringJson = function (object)
    {
        try
        {
            JSON.parse (object);
        } catch (e)
        {
            return false;
        }
        return true;
    };

    /**
     * Checking if object is String
     *
     * @param object
     * @returns {boolean}
     */
    this.isNumber = function (object)
    {
        return this.isObjectMyType (object, '[object Number]');
    };

    /**
     * Checking if object is of provided type
     *
     * @param object
     * @param type
     * @returns {boolean}
     */
    this.isObjectMyType = function (object, type)
    {
        return type === Object.prototype.toString.call (object);
    };
};