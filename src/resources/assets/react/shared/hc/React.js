import React from "react";
import ReactDOM from "react-dom";
import HCPopUp from "../../components/HCPopUp";
import HCAdminListView from "../../components/HCAdminList";

HC.react = new function () {

    let scope = this;

    /**
     * showing admin list
     * @param data
     */
    this.adminList = function (data) {
        ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'))
    };

    /**
     * Showing hc pop up
     * @param data
     */
    this.popUp = function (data) {

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
            id = HC.helpers.uuid();
            let creating = document.createElement('div');
            creating.id = id;
            existing.prepend(creating);
        }

        existing = document.getElementById(id);
        data.parent = id;

        ReactDOM.render(<HCPopUp config={data}/>, existing);
    };
};