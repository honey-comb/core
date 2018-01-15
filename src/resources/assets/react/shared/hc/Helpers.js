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

        return emailReg.test (email);
    };
};