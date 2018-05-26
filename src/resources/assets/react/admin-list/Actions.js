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

import React, {Component} from 'react';
import FAButton from '../form/buttons/FAButton';
import HCForm from "../HCForm";
import FontAwesomeIcon from "@fortawesome/react-fontawesome";

export default class Actions extends Component {

    constructor() {
        super();

        this.params = {};

        this.refs = {
            searchField: {
                value: ""
            }
        };

        this.state = {
            enableClearFilter: false
        };

        this.getNewButton = this.getNewButton.bind(this);
        this.newAction = this.newAction.bind(this);
        this.deleteAction = this.deleteAction.bind(this);
        this.forceDeleteAction = this.forceDeleteAction.bind(this);
        this.restoreAction = this.restoreAction.bind(this);
        this.filterAction = this.filterAction.bind(this);
        this.searchAction = this.searchAction.bind(this);
        this.getFilters = this.getFilters.bind(this);
        this.getParams = this.getParams.bind(this);
        this.reset = this.reset.bind(this);
        this.actionCompleted = this.actionCompleted.bind(this);
    }

    render() {
        return <div style={{display: 'flex', alignItems: 'center', flexWrap: 'wrap'}}>
            <div id="actions">
                {this.getNewButton()}
                {this.getDeleteButton()}
                {this.getRestoreButton()}
                {this.getForceDeleteButton()}
                {this.getSearchField()}
                {this.getMergeButton()}
                {this.getCloneButton()}
            </div>
            <div id="filters">
                {this.getFilters()}
            </div>
        </div>;
    }

    getFilters() {

        if (this.props.config.filters) {
            return [
                <HCForm key={0} ref="form" structure={this.props.config.filters} onSelectionChange={this.filterAction}/>,
                <button key={1} ref="clear" onClick={this.reset} className="btn btn-danger">
                    <FontAwesomeIcon icon={HC.helpers.faIcon('times')}/>
                </button>
            ]
        }

        return '';
    }

    reset(inner) {
        this.params = {};
        this.refs.searchField.value = "";

        if (this.refs.form)
            this.refs.form.reset();

        if (inner)
            this.props.reload();
    }

    /**
     * Getting search field and search button
     *
     * @returns {*}
     */
    getSearchField() {

        return <div key="first">
            <input className="form-control input-bg"
                   placeholder="Search" ref="searchField" onKeyUp={this.searchAction}/>
        </div>;
    }

    /**
     * Getting new button
     *
     * @returns {*}
     */
    getNewButton() {
        if (this.props.config.actions.indexOf('new') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed}
                         type={HC.helpers.buttonClass("success")}
                         icon={HC.helpers.faIcon("plus")}
                         onPress={this.newAction}/>;
    }

    /**
     * Getting Delete button
     *
     * @returns {*}
     */
    getDeleteButton() {
        if (this.props.config.actions.indexOf('delete') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed}
                         disabled={this.props.actionsDisabled.delete}
                         type={HC.helpers.buttonClass("danger")}
                         icon={HC.helpers.faIcon("trash")}
                         showCounter={true}
                         count={this.props.selected.length}
                         onPress={this.deleteAction}/>;
    }

    /**
     * Getting Merge button
     *
     * @returns {*}
     */
    getMergeButton() {
        if (this.props.config.actions.indexOf('merge') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed}
                         disabled={this.props.actionsDisabled.merge}
                         type={HC.helpers.buttonClass("clean")}
                         icon={HC.helpers.faIcon("code-merge")}
                         showCounter={true}
                         count={this.props.selected.length}/>;
    }

    /**
     * Getting Clone button
     *
     * @returns {*}
     */
    getCloneButton() {
        if (this.props.config.actions.indexOf('clone') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed}
                         disabled={this.props.actionsDisabled.clone}
                         type={HC.helpers.buttonClass("info")}
                         icon={HC.helpers.faIcon("clone")}
                         showCounter={true}
                         count={this.props.selected.length}/>;
    }

    /**
     * Getting Restore button
     *
     * @returns {*}
     */
    getRestoreButton() {
        if (this.props.config.actions.indexOf('restore') === -1)
            return '';

        return <FAButton display={!this.props.onlyTrashed}
                         disabled={this.props.actionsDisabled.restore}
                         type={HC.helpers.buttonClass("success")}
                         icon={HC.helpers.faIcon("arrow-circle-up")}
                         showCounter={true}
                         count={this.props.selected.length}
                         onPress={this.restoreAction}/>;
    }

    /**
     * Getting ForceDelete button
     *
     * @returns {*}
     */
    getForceDeleteButton() {
        if (this.props.config.actions.indexOf('forceDelete') === -1)
            return '';

        return <FAButton display={!this.props.onlyTrashed}
                         disabled={this.props.actionsDisabled.forceDelete}
                         type={HC.helpers.buttonClass("danger")}
                         icon={HC.helpers.faIcon("minus-octagon")}
                         showCounter={true}
                         count={this.props.selected.length}
                         onPress={this.forceDeleteAction}/>;
    }

    newAction() {

        if (this.props.config.options && this.props.config.options.separatePage)
        {
            window.location.href = window.location.href + '/create';
            return;
        }

        HC.react.popUp({
            url: HC.helpers.extendUrl(this.props.config.form, "-new"),
            type: "form",
            callBack: this.newCreated,
            scope: this
        });
    }

    newCreated() {
        this.props.reload(true);
    }

    /**
     *  delete action function
     */
    deleteAction() {

        let params = {data: {list: this.props.selected}};

        HC.react.loader.delete(this.props.config.url, params, this.actionCompleted, true);
    }

    /**
     * Deletion completed force reload
     */
    actionCompleted() {
        this.props.reload(true);
    }

    /**
     *  force delete action function
     */
    forceDeleteAction() {

        let params = {data: {list: this.props.selected}};

        HC.react.loader.delete(this.props.config.url + '/force', params, this.actionCompleted, true);
    }

    /**
     *  restore action function
     */
    restoreAction() {
        let params = {list: this.props.selected};

        HC.react.loader.post(this.props.config.url + '/restore', params, this.actionCompleted, true);
    }

    /**
     * On search change
     *
     * @param e
     */
    searchAction(e) {

        if (e.target.value.length > 2 || e.target.value.length === 0) {
            this.params.q = e.target.value;
            this.props.reload();
        }
    }

    /**
     * On filter update
     *
     * @param record
     */
    filterAction(record) {
        let q = '';

        Object.keys(record).map((key) => {

            if (record[key]) {
                if (record[key].indexOf('=') === -1) {
                    q += key + '=' + record[key] + '&';
                }
                else if (record[key]) {
                    q += record[key] + '&';
                }
            }
        });

        let params = this.filterParams(q);

        if (this.params.q)
            params.q = this.params.q;

        this.params = params;

        this.props.reload();
    }

    getParams() {
        return this.params;
    }

    /**
     * Creating filter based params
     *
     * @param query
     * @returns {{}}
     */
    filterParams(query) {
        const getParams = query => {
            if (!query) {
                return {};
            }

            return (/^[?#]/.test(query) ? query.slice(1) : query)
                .split('&')
                .reduce((params, param) => {
                    let [key, value] = param.split('=');

                    if (key === "" || value === "")
                        return params;

                    if (!params[key]) {
                        params[key] = value;
                    }
                    else {
                        if (!HC.helpers.isArray(params[key])) {
                            params[key] = [params[key]]
                        }

                        params[key].push(value);
                    }
                    return params;
                }, {});
        };

        return getParams(query);
    }
}