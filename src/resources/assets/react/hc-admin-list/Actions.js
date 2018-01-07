import React, {Component} from 'react';
import FAButton from '../hc-form/buttons/FAButton';
import axios from "axios/index";

export default class Actions extends Component {

    constructor() {
        super();

        this.getNewButton = this.getNewButton.bind(this);
        this.newAction = this.newAction.bind(this);
        this.deleteAction = this.deleteAction.bind(this);
        this.forceDeleteAction = this.forceDeleteAction.bind(this);
        this.restoreAction = this.restoreAction.bind(this);
        this.filterAction = this.filterAction.bind(this);
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
                   placeholder="Search" onKeyUp={this.filterAction}/>
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
                         type={HCHelpers.buttonClass("success")}
                         icon={HCHelpers.faIcon("plus")}
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
                         type={HCHelpers.buttonClass("danger")}
                         icon={HCHelpers.faIcon("trash")}
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
                         type={HCHelpers.buttonClass("clean")}
                         icon={HCHelpers.faIcon("code-merge")}
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
                         type={HCHelpers.buttonClass("info")}
                         icon={HCHelpers.faIcon("clone")}
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
                         type={HCHelpers.buttonClass("success")}
                         icon={HCHelpers.faIcon("arrow-circle-up")}
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
                         type={HCHelpers.buttonClass("danger")}
                         icon={HCHelpers.faIcon("minus-octagon")}
                         showCounter={true}
                         count={this.props.selected.length}
                         onPress={this.forceDeleteAction}/>;
    }

    newAction() {
        console.log(this.props.form + "-new");
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

    restoreAction ()
    {
        let params = {list: this.props.selected};

        axios.post(this.props.url+ '/restore', params)
            .then(res => {

                this.props.reload();
            });
    }

    filterAction (e)
    {
        if (e.target.value.length > 2 || e.target.value.length === 0)
            axios.get(this.props.url, {params:{q:e.target.value}})
                .then(res => {
                    this.props.reload(res);
                });
    }
}