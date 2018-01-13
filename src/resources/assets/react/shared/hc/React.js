import React from "react";
import ReactDOM from "react-dom";
import HCPopUp from "../../components/HCPopUp";
import HCAdminListView from "../../components/HCAdminList";
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

HC.react = new function () {

    let scope = this;
    let popUpCount = 0;

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

        popUpCount++;
        toggleBody();
    };

    this.popUpRemove = function (id)
    {
        ReactDOM.unmountComponentAtNode(document.getElementById(id));

        popUpCount--;
        toggleBody();
    };

    function toggleBody ()
    {
        if (popUpCount > 0)
        {
            document.body.classList.add('disabled');
        }
        else
        {
            document.body.classList.remove('disabled');
        }

    }

    /**
     * enable font awesome icons
     */
    this.enableFaIcons = function () {
        let list = document.getElementsByClassName('fa-icon');

        for (let i = 0; i < list.length; i++) {
            let icon = list[i].dataset;

            ReactDOM.render(<FontAwesomeIcon icon={HC.helpers.faIcon(icon.icon)}/>, list[i]);
        }
    }
};