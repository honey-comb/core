window.HCHelpers = {

    faPrefix: "far",

    faIcon: function (name, prefix)
    {
        if (!prefix)
            prefix = this.faPrefix;

        return [prefix, name]
    },

    buttonClass: function (name)
    {
        if (!name)
            name = "clean";

        return "btn btn-" + name;
    }
};

