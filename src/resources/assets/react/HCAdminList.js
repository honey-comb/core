/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */
import List from "./hc-admin-list/List";

require('../../js/bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./shared/HCHelpers');

import React, {Component} from 'react';
import ReactDOM from 'react-dom';

import Actions from './hc-admin-list/Actions';
import Settings from './hc-admin-list/Settings';

import fontAwesome from '@fortawesome/fontawesome'
import FAProRegularIcons from '@fortawesome/fontawesome-pro-regular'
import axios from "axios/index";

class HCAdminListView extends Component {

    constructor(props) {
        super(props);

        this.state = {
            title: this.props.config.title,
            records: {
                data: []
            },
            onlyTrashed: false,
            selected: [],
            hideCheckBox: this.getCheckBoxConfiguration(false),
            actionsDisabled: {
                delete:true,
                merge:true,
                clone:true,
                forceDelete:true,
                restore:true,
            },
        };

        this.handleTrashedEvent = this.handleTrashedEvent.bind(this);
        this.getCheckBoxConfiguration = this.getCheckBoxConfiguration.bind(this);
        this.selectionUpdated = this.selectionUpdated.bind(this);

        fontAwesome.library.add(FAProRegularIcons);
    }

    componentDidMount ()
    {
        this.handleTrashedEvent(false);
    }

    render() {

        return <div className="box">
            <div className="box-header">
                <h3 className="box-title">{this.state.title}</h3>
                <Settings onChange={this.handleTrashedEvent} trashHidden={this.getCheckBoxConfiguration(true)}/>
            </div>
            <div className="box-body">
                <Actions
                    url={this.props.config.url}
                    form={this.props.config.form}
                    actions={this.props.config.actions}
                    onlyTrashed={this.state.onlyTrashed}
                    actionsDisabled={this.state.actionsDisabled}
                    selected={this.state.selected}
                />
                <List
                    headers={this.props.config.headers}
                    perPage={this.props.config.perPage}
                    hideCheckBox={this.state.hideCheckBox}
                    onlyTrashed={this.state.onlyTrashed}
                    records={this.state.records}
                    selectionUpdated={this.selectionUpdated}
                />
            </div>
        </div>
    }

    selectionUpdated(selected)
    {
        let actionsDisabled = {
            delete:true,
            merge:true,
            clone:true,
            forceDelete:true,
            restore:true,
        };

        if (selected.length > 0)
        {
            actionsDisabled.delete = false;
            actionsDisabled.clone = false;
            actionsDisabled.forceDelete = false;
            actionsDisabled.restore = false;

            if (selected.length > 1)
                actionsDisabled.merge = false;
        }


        this.setState(
            {
                selected:selected,
                actionsDisabled: actionsDisabled
            })
    }

    handleTrashedEvent(value) {
        let options = {
            onlyTrashed: value,
            records: {
                data: []
            },
        };

        let params = {};

        if (value) {
            options.title = this.props.config.title + ' (Trashed)';
            options.hideCheckBox = this.getCheckBoxConfiguration(true);

            params = {params:{trashed:1}};
        }
        else {
            options.title = this.props.config.title;
            options.hideCheckBox = this.getCheckBoxConfiguration(false);
        }

        this.setState(options);

        axios.get(this.props.config.url, params)
            .then(res => {

                options.records = res.data;
                this.setState(options);
            });
    }

    getCheckBoxConfiguration(trashed) {

        if (trashed)
            return (this.props.config.actions.indexOf('forceDelete') === -1 && this.props.config.actions.indexOf('restore') === -1);
        else
            return (this.props.config.actions.indexOf('delete') === -1 && this.props.config.actions.indexOf('merge') === -1 && this.props.config.actions.indexOf('clone') === -1);
    }
}

window.RenderAdminList = function (data) {
    ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'))
};



