/**
 * A module which contains global functions used by various modules and classes.
 * @module HCFunctions
 */
HCFunctions = new function ()
{
    /**
     * Something went wrong
     *
     * @method handleError
     * @param type
     * @param e error information
     */
    this.notify = function (type, e)
    {
        var message;
        if (!e)
            message = ['Unknown error'];
        else if (e.message)
        {
            if (HCFunctions.isStringJson (e.message))
            {
                // TODO loop throw error messages. Error message also cant contain array of strings
                message = [e.message];
            }
            else if (HCFunctions.isString (e.message))
                message = [e.message];
        }
        else if (HCFunctions.isString (e))
            message = e;

        if (message)
            toastr[type] (message);
    };

    /**
     * Validating URL structure
     *
     * @credit http://stackoverflow.com/a/26680227/657451
     * @method validateURL
     * @param {string} value URL
     */
    this.validateURL = function (value)
    {
        var pattern = new RegExp ('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator

        return pattern.test (value);
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
        var _type = Object.prototype.toString.call (object);

        return type == _type;
    };

    /**
     * Replacing brackets with dynamic values. {x} - structure for dynamic values.
     * @method replaceBrackets
     * @param {string} text
     * @param {Array|string|number} args values which will be placed into the text
     */
    this.replaceBrackets = function (text, args)
    {
        if (args instanceof Object)
        {
            $.each (args, function (key, value) {
                text = text.replace('{' + key + '}', value);
            })
        }

        if (args instanceof Array)
            for (var i = 0; i < args.length; i++)
                text = text.replace ('{' + i + '}', args[i]);
        else
            text = text.replace ('{0}', args);

        return text;
    };

    /**
     * /**
     * Finding index of translations array, based on currentLanguage
     *
     * @param language
     * @param translations
     * @returns {*}
     */
    this.getTranslationsLanguageElementIndex = function (language, translations)
    {
        var _key = undefined;

        $.each (translations, function (key, value)
        {
            if (value.language_code == language)
                _key = key;
        });

        if (_key == undefined)
            _key = Object.size (translations);

        return _key;
    };

    /**
     * Generating random string (Latin)
     * @method randomString
     * @param {number} length of the string
     * @param {boolean} timestamp should string used the miliseconds timestamp in random string
     * @param {boolean} numbers should numbers be used in random string
     * @param {boolean} special should special symbols be used in random string
     * @param {string} unique any other symbols
     */
    this.randomString = function (length, timestamp, numbers, special, unique)
    {
        var randomKey = '';
        var keySet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if (unique)
            keySet += unique;

        if (numbers)
            keySet += '0123456789';

        if (special)
            keySet += '^/|!@#$%^&()_-|=+~\'";:,.*?<>{}[]';

        if (timestamp)
            randomKey += new Date ().getTime () + '_';

        for (var i = 0; i < length; i++)
            randomKey += keySet.charAt (Number (Math.random () * keySet.length));

        return randomKey;
    };

    /**
     * Validating email address
     * @method validateEmail
     * @param {string} email address
     */
    this.validateEmail = function (email)
    {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{1,10})?$/;

        return emailReg.test (email);
    };

    /**
     * Binding functions from different scopes
     * @method bind
     * @param {string} scope where function should be called
     * @param {object} target jQuery object
     * @param {string} event name or names separated by space
     * @param {function} handler callback function
     */
    this.bind = function (scope, target, event, handler)
    {
        $ (target).bind (event, function (e)
        {
            handler.call (scope, e);
        });
    };

    /**
     * Binding functions from different scopes
     * @method bind
     * @param {object} target jQuery object
     * @param {string} event name or names separated by space
     * @param {function} handler callback function
     */
    this.bindStrict = function (target, event, handler)
    {
        $ (target).bind (event, handler);
    };

    /**
     * Unbinding function from target
     *
     * @param target
     * @param event
     * @param handler
     */
    this.unbindStrict = function (target, event, handler)
    {
        $ (target).unbind (event, handler);
    };

    /**
     * Formating file size
     * Oll functions accept bytes
     *
     * @class FileSize class,
     */
    this.FileSize = new function ()
    {
        var KB = 1024;
        var MB = Math.pow (KB, 2);
        var GB = Math.pow (KB, 3);
        var TB = Math.pow (KB, 4);
        var PB = Math.pow (KB, 5);
        var EB = Math.pow (KB, 6);
        var ZB = Math.pow (KB, 7);
        var YB = Math.pow (KB, 8);

        this.toKB = function (value)
        {
            return formatAnswer (value / KB) + ' KB';
        };

        this.toMB = function (value)
        {
            if (value < MB)
                return this.toKB(value);

            return formatAnswer (value / MB) + ' MB';
        };

        this.toGB = function (value)
        {
            if (value < GB)
                return this.toMB(value);

            return formatAnswer (value / GB) + ' GB';
        };

        this.toTB = function (value)
        {
            if (value < TB)
                return this.toGB(value);

            return formatAnswer (value / TB) + ' TB';
        };

        this.toPB = function (value)
        {
            if (value < PB)
                return this.toTB(value);

            return formatAnswer (value / PB) + ' PB';
        };

        this.toEB = function (value)
        {
            if (value < EB)
                return this.toPB(value);

            return formatAnswer (value / EB) + ' EB';
        };

        this.toZB = function (value)
        {
            if (value < ZB)
                return this.toEB(value);

            return formatAnswer (value / ZB) + ' ZB';
        };

        this.toYB = function (value)
        {
            if (value < YB)
                return this.toZB(value);

            return formatAnswer (value / YB) + ' YB';
        };

        function formatAnswer (value)
        {
            var roundNum = Math.round (value);

            if (roundNum > 0)
                return roundNum.toFixed (1).replace (/(\d)(?=(\d{3})+\.)/g, "$1.").slice (0, -2);
            else
                return value;
        }
    };

    /**
     * @credit http://stackoverflow.com/a/1230491/657451
     *
     * @method bold String
     *
     * @param needle
     * @param text
     */
    this.boldString = function (needle, text)
    {
        if (!needle || !text)
            return;

        return text.replace (new RegExp (needle, "ig"), function (str)
        {
            return '<b>' + str + '</b>'
        });
    };

    /**
     * Creating UUID
     *
     * @returns {string}
     */
    this.createUUID = function ()
    {
        // http://www.ietf.org/rfc/rfc4122.txt
        var s         = [];
        var hexDigits = "0123456789abcdef";
        for (var i = 0; i < 36; i++)
        {
            s[i] = hexDigits.substr (Math.floor (Math.random () * 0x10), 1);
        }
        s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
        s[19] = hexDigits.substr ((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
        s[8]  = s[13] = s[18] = s[23] = "-";

        return s.join ("");
    };

    /**
     * Fully stripping HTML from the text
     *
     * @param html
     * @returns {string}
     */
    this.stripHTML = function (html)
    {
        var tmp       = document.createElement ("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText;
    };


    /**
     * Resizing the iFrame to the iFrames content height
     *
     * @param obj
     */
    this.resizeIFrame = function (obj)
    {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    };

    /**
     * Remove from array particular value
     *
     * @param array
     * @param value
     */
    this.removeFromArray = function (array, value)
    {
        var index = array.indexOf (value);

        if (index > -1)
            array.splice (index, 1);
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
     * Cloning an object / array / date
     * @credit http://stackoverflow.com/a/728694/657451
     *
     * @param obj
     * @returns {*}
     */
    this.clone = function (obj)
    {
        var copy;

        // Handle the 3 simple types, and null or undefined
        if (null == obj || "object" != typeof obj) return obj;

        // Handle Date
        if (obj instanceof Date)
        {
            copy = new Date ();
            copy.setTime (obj.getTime ());
            return copy;
        }

        // Handle Array
        if (obj instanceof Array)
        {
            copy = [];
            for (var i = 0, len = obj.length; i < len; i++)
            {
                copy[i] = this.clone (obj[i]);
            }
            return copy;
        }

        // Handle Object
        if (obj instanceof Object)
        {
            copy = {};
            for (var attr in obj)
            {
                if (obj.hasOwnProperty (attr)) copy[attr] = this.clone (obj[attr]);
            }
            return copy;
        }

        throw new Error ("Unable to copy obj! Its type isn't supported.");
    }
};