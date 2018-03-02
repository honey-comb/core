import React, {Component} from 'react';
import FAButton from '../hc-form/buttons/FAButton';
import axios from "axios/index";
import HCForm from "../components/HCForm";
import FontAwesomeIcon from "@fortawesome/react-fontawesome";

export default class Actions extends Component {

    constructor() {
        super();

        this.allParams = {};

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
        this.clearFilters = this.clearFilters.bind(this);
    }

    componentWillUpdate(nextProps) {
        if (this.props.onlyTrashed !== nextProps.onlyTrashed)
            this.clearFilters();
    }

    render() {
        return <div style={{display: 'flex', alignItems: 'center'}}>
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

        if (this.props.filters) {
            return [
                <HCForm key={0} ref="form" structure={this.props.filters} onSelectionChange={this.filterAction}/>,
                <button key={1} ref="clear" onClick={this.clearFilters} className="btn btn-danger">
                    <FontAwesomeIcon icon={HC.helpers.faIcon('times')}/>
                </button>
            ]
        }

        return '';
    }

    clearFilters() {
        this.allParams = {};
        this.refs.searchField.value = "";
        this.refs.form.reset();
        this.loadData();
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
        if (this.props.actions.indexOf('new') === -1)
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
        if (this.props.actions.indexOf('delete') === -1)
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
        if (this.props.actions.indexOf('merge') === -1)
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
        if (this.props.actions.indexOf('clone') === -1)
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
        if (this.props.actions.indexOf('restore') === -1)
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
        if (this.props.actions.indexOf('forceDelete') === -1)
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

        HC.react.popUp({
            url: HC.helpers.extendUrl(this.props.form, "-new"),
            type: "form",
            callBack: this.newCreated,
            scope: this
        });
    }

    newCreated() {
        this.props.reload();
    }

    deleteAction() {

        let params = {data: {list: this.props.selected}};

        axios.delete(this.props.url, params)
            .then(res => {

                this.props.reload();
            });
    }

    forceDeleteAction() {

        let params = {data: {list: this.props.selected}};

        axios.delete(this.props.url + '/force', params)
            .then(res => {

                this.props.reload();
            });
    }

    restoreAction() {
        let params = {list: this.props.selected};

        axios.post(this.props.url + '/restore', params)
            .then(res => {

                this.props.reload();
            });
    }

    /**
     * On search change
     *
     * @param e
     */
    searchAction(e) {

        if (e.target.value.length > 2 || e.target.value.length === 0) {
            this.allParams.q = e.target.value;
            this.loadData();
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

        if (this.allParams.q)
            params.q = this.allParams.q;

        this.allParams = params;

        this.loadData();
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

    /**
     * Loading new content based on filters and search element
     */
    loadData() {
        if (this.props.onlyTrashed)
            this.allParams.trashed = 1;

        axios.get(this.props.url, {params: this.allParams})
            .then(res => {
                this.props.reload(res);
            });
    }
}