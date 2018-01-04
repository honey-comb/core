window.HCHelpers = {

    faPrefix: "far",

    faIcon: function (name, prefix) {
        if (!prefix)
            prefix = this.faPrefix;

        return [prefix, name]
    },

    buttonClass: function (name) {
        if (!name)
            name = "clean";

        return "btn btn-" + name;
    },

    arraysEqual: function (arr1, arr2) {

        if (Object.size(arr1) !== Object.size(arr2))
            return false;

        let keys = Object.keys(arr1);

        for(let i = keys.length; i--;)
        {
            if (arr1[keys[i]] !== arr2[keys[i]])
            {
                if (typeof (arr1[keys[i]]) === "object" && typeof (arr2[keys[i]]) === "object")
                    return HCHelpers.arraysEqual(arr1[keys[i]], arr2[keys[i]]);

                return false;
            }
        }

        return true;
    }
};

