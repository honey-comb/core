import React from "react";
import ReactDOM from "react-dom";
import HCAdminListView from "./../components/HCAdminList";
import HCPopUp from "../components/HCPopUp";

let uuid = require("uuid/v4");

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

        for (let i = keys.length; i--;) {
            if (arr1[keys[i]] !== arr2[keys[i]]) {
                if (typeof (arr1[keys[i]]) === "object" && typeof (arr2[keys[i]]) === "object")
                    return HCHelpers.arraysEqual(arr1[keys[i]], arr2[keys[i]]);

                return false;
            }
        }

        return true;
    },
    RenderAdminList: function (data) {
        ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'))
    },
    OpenPopUp: function (data) {

        let id = "hc-pop-up";

        if (data.id)
            id = data.id;

        let existing = document.getElementById(id);

        if (!existing) {
            let creating = document.createElement('div');
            creating.id = id;
            document.body.prepend(creating);
        }
        else {
            id = uuid();
            let creating = document.createElement('div');
            creating.id = id;
            existing.prepend(creating);
        }

        existing = document.getElementById(id);
        data.parent = id;

        ReactDOM.render(<HCPopUp config={data}/>, existing);
    },
    uuid : function ()
    {
        return uuid();
    }
};