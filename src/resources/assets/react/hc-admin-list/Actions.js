import React, {Component} from 'react';
import FAButton from '../hc-form/buttons/FAButton';

export default class Actions extends Component {

    constructor() {
        super();

        this.getNewButton = this.getNewButton.bind(this);
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

        let filter = <div key="first">
            <input className="form-control input-bg"
                   placeholder="Search"/>
        </div>;

        if (this.props.actions.indexOf('search') === -1)
            return filter;

        return [
            filter,
            <FAButton key="second"
                      type={HCHelpers.buttonClass("warning")}
                      icon={HCHelpers.faIcon("search")}>
                <div>{filter}</div>
            </FAButton>
        ];
    }

    /**
     * Getting new button
     *
     * @returns {*}
     */
    getNewButton() {
        if (this.props.actions.indexOf('new') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed ? 'hidden' : ''}
                         type={HCHelpers.buttonClass("success")}
                         icon={HCHelpers.faIcon("plus")}
                         onPress={newButtonAction}/>;
    }

    newButtonAction() {
        HCService.PopUp.Pop({
            label: 'New Record',
            type: 'form',
            config: {
                structureURL: this.props.form + "-new"
            }
        });
    }

    /**
     * Getting Delete button
     *
     * @returns {*}
     */
    getDeleteButton() {
        if (this.props.actions.indexOf('delete') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed ? 'hidden' : ''}
                         type={HCHelpers.buttonClass("coral")}
                         icon={HCHelpers.faIcon("trash")}/>;
    }

    /**
     * Getting Merge button
     *
     * @returns {*}
     */
    getMergeButton() {
        if (this.props.actions.indexOf('merge') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed ? 'hidden' : ''}
                         type={HCHelpers.buttonClass("clean")}
                         icon={HCHelpers.faIcon("code-merge")}/>;
    }

    /**
     * Getting Clone button
     *
     * @returns {*}
     */
    getCloneButton() {
        if (this.props.actions.indexOf('clone') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed ? 'hidden' : ''}
                         type={HCHelpers.buttonClass("info")}
                         icon={HCHelpers.faIcon("clone")}/>;
    }

    /**
     * Getting Restore button
     *
     * @returns {*}
     */
    getRestoreButton() {
        if (this.props.actions.indexOf('restore') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed ? '' : 'hidden'}
                         type={HCHelpers.buttonClass("success")}
                         icon={HCHelpers.faIcon("arrow-circle-up")}/>;
    }

    /**
     * Getting ForceDelete button
     *
     * @returns {*}
     */
    getForceDeleteButton() {
        if (this.props.actions.indexOf('forceDelete') === -1)
            return '';

        return <FAButton display={this.props.onlyTrashed ? '' : 'hidden'}
                         type={HCHelpers.buttonClass("danger")}
                         icon={HCHelpers.faIcon("minus-octagon")}/>;
    }
}