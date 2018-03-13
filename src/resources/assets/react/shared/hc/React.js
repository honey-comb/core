import React from "react";
import ReactDOM from "react-dom";
import HCPopUp from "../../components/HCPopUp";
import HCAdminListView from "../../components/HCAdminList";
import FontAwesomeIcon from '@fortawesome/react-fontawesome'
import HCForm from "../../components/HCForm";
import * as axios from "axios/index";
import {toast, ToastContainer} from 'react-toastify';

HC.react = new function () {

    let scope = this;
    let popUpCount = 0;

    /**
     * Loader
     * @constructor
     */
    let Loader = function () {
        /**
         * Get method
         *
         * @param url
         * @param params
         * @param callback
         * @param notify
         */
        this.get = function (url, params, callback, notify) {
            axios.get(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                if (axios.isCancel(error)) {
                    console.log('Request canceled', error.message);
                } else {
                    handleAxiosError(error);
                }
            });
        };

        /**
         * Put method
         *
         * @param url
         * @param params
         * @param callback
         * @param notify
         */
        this.put = function (url, params, callback, notify) {
            axios.put(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                handleAxiosError(error);
            });
        };

        /**
         *
         * @param url
         * @param params
         * @param callback
         * @param notify
         */
        this.post = function (url, params, callback, notify) {
            axios.post(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                handleAxiosError(error);
            });
        };

        this.delete = function(url, params, callback, notify)
        {
            axios.delete(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                handleAxiosError(error);
            });
        };

        /**
         * Handling success
         *
         * @param data
         * @param callback
         * @param notify
         */
        function handleSuccess(data, callback, notify) {
            if (notify && data.message) {
                toast.success(data.message, {position: toast.POSITION.TOP_CENTER})
            }

            callback(data);
        }

        /**
         * Handling error
         *
         * @param e
         */
        function handleAxiosError(e) {
            toast.error(e.message, {position: toast.POSITION.TOP_CENTER});
        }
    };

    /**
     * showing admin list
     * @param data
     */
    this.adminList = function (data) {
        ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'));
        ReactDOM.render(<ToastContainer/>, document.getElementById('toastrify'));
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
            creating.classList.add('inner-pop-up');
            data.style = {marginLeft: 50 * popUpCount};
            existing.append(creating);
        }

        existing = document.getElementById(id);
        data.parent = id;

        ReactDOM.render(<HCPopUp config={data}/>, existing);

        popUpCount++;
        toggleBody();
    };

    this.popUpRemove = function (id) {
        ReactDOM.unmountComponentAtNode(document.getElementById(id));
        document.getElementById(id).remove();

        popUpCount--;
        toggleBody();
    };

    function toggleBody() {
        if (popUpCount > 0) {
            document.body.classList.add('disabled');
        }
        else {
            document.body.classList.remove('disabled');
        }

    }

    this.hcForm = function (config) {
        ReactDOM.render(<HCForm config={config}
                                formClosed={this.handlePopUpClose}/>, document.getElementById(config.divId));
    };

    /**
     * enable font awesome icons
     */
    this.enableFaIcons = function () {
        let list = document.getElementsByClassName('fa-icon');

        for (let i = 0; i < list.length; i++) {
            let icon = list[i].dataset;

            ReactDOM.render(<FontAwesomeIcon icon={HC.helpers.faIcon(icon.icon)}/>, list[i]);
        }
    };

    this.loader = new Loader();
};