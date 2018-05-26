HC.globals = {

    /**
     * Font awesome prefix, depending which version is being used
     */
    faPrefix: "far"
};

function RegisterElements (value)
{
    let list = {};
    let _default = value;

    this.register = function (name, component) {
        list[name] = component;
    };

    this.get = function (name) {

        if (!list[name])
            name = _default;

        return list[name];
    }
}

/**
 * Admin list types storage
 */
HC.adminList = new RegisterElements('simple');

/**
 * Admin list cell types storage
 */
HC.adminListCells = new RegisterElements();

/**
 * Form fields types storage
 */
HC.formFields = new RegisterElements();