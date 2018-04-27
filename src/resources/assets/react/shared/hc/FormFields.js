HC.formFields = new function () {

    let formList = {};

    this.register = function (name, component) {
        formList[name] = component;
    };

    this.get = function (name) {
        return formList[name];
    }
};