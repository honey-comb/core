/*
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

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
         * @param errorCallBack
         */
        this.get = function (url, params, callback, notify, errorCallBack) {
            axios.get(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                if (axios.isCancel(error)) {
                    console.log('Request canceled', error.message);
                } else {
                    handleAxiosError(error, errorCallBack);
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
         * @param errorCallBack
         */
        this.put = function (url, params, callback, notify, errorCallBack) {
            axios.put(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                handleAxiosError(error, errorCallBack);
            });
        };

        /**
         *
         * @param url
         * @param params
         * @param callback
         * @param notify
         * @param errorCallBack
         */
        this.post = function (url, params, callback, notify, errorCallBack) {
            axios.post(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                handleAxiosError(error, errorCallBack);
            });
        };

        /**
         *
         * @param url
         * @param params
         * @param callback
         * @param notify
         * @param errorCallBack
         */
        this.delete = function (url, params, callback, notify, errorCallBack) {
            axios.delete(url, params).then(res => {

                res = res.data;
                handleSuccess(res, callback, notify);

            }).catch(function (error) {
                handleAxiosError(error, errorCallBack);
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

            if (callback) {
                callback(data);
            }
        }

        /**
         * Handling error
         *
         * @param e
         * @param errorCallBack
         */
        function handleAxiosError(e, errorCallBack) {

            if (errorCallBack)
                errorCallBack(e);

            let message = e.message;

            if (e.response && e.response.data) {

                if (e.response.data.message && e.response.data.errors) {
                    message = '';

                    Object.keys(e.response.data.errors).map((value) => {
                        toast.error(e.response.data.errors[value][0], {position: toast.POSITION.TOP_CENTER});
                    });

                    return;
                }
                else if (e.response.data.message) {
                    message = e.response.data.message;
                }
            }

            toast.error(message, {position: toast.POSITION.TOP_CENTER});
        }
    };

    /**
     * showing admin list
     * @param data
     */
    this.adminList = function (data) {
        ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'));
        this.enableToastContainer();
    };

    /**
     * Enabling toastr container
     */
    this.enableToastContainer = function () {
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

    /**
     * Custom notifications
     *
     * @param type
     * @param message
     * @param options
     */
    this.notify = function (type, message) {
        toast[type](message, {position: toast.POSITION.TOP_CENTER})
    };

    this.loader = new Loader();
};