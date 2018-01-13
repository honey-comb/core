import React, {Component} from 'react';
import FAButton from '../hc-form/buttons/FAButton';
import axios from "axios/index";

export default class Actions extends Component {

    constructor() {
        super();

        this.state = {
            searchValue: ""
        };

        this.refs = {
            hcAdminFilter: {
                value: ""
            }
        };

        this.getNewButton = this.getNewButton.bind(this);
        this.newAction = this.newAction.bind(this);
        this.deleteAction = this.deleteAction.bind(this);
        this.forceDeleteAction = this.forceDeleteAction.bind(this);
        this.restoreAction = this.restoreAction.bind(this);
        this.filterAction = this.filterAction.bind(this);
    }

    componentWillUpdate(nextProps) {
        if (this.props.onlyTrashed !== nextProps.onlyTrashed)
            this.refs.hcAdminFilter.value = "";
    }

    render() {
        return <div id="actions">
            {this.getNewButton()}
            {this.getDeleteButton()}
            {this.getRestoreButton()}
            {this.getForceDeleteButton()}
            {this.getSearchField()}
            {this.getMergeButton()}
            {this.getCloneButton()}
        </div>;
    }

    /**
     * Getting search field and search button
     *
     * @returns {*}
     */
    getSearchField() {

        return <div key="first">
            <input className="form-control input-bg"
                   placeholder="Search" ref="hcAdminFilter" onKeyUp={this.filterAction}/>
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

        HC.react.popUp({url: this.props.form + "-new", type: "form"});
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

    filterAction(e) {
        let params = {
            q: e.target.value
        };

        if (this.props.onlyTrashed)
            params.trashed = 1;

        if (e.target.value.length > 2 || e.target.value.length === 0)
            axios.get(this.props.url, {params: params})
                .then(res => {
                    this.props.reload(res);
                });
    }
}