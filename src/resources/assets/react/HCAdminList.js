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

class HCAdminListView extends Component {

    constructor (props)
    {
        super(props);

        this.state = {
            onlyTrashed: false,
            _title: this.props.config.title
        };

        this.handleTrashedEvent = this.handleTrashedEvent.bind(this);

        fontAwesome.library.add(FAProRegularIcons);
    }

    render() {

        return <div className="box">
            <div className="box-header">
                <h3 className="box-title">{this.state._title}</h3>
                <Settings onChange={this.handleTrashedEvent}/>
            </div>
            <div className="box-body">
                <Actions
                    url={this.props.config.url}
                    form={this.props.config.form}
                    actions={this.props.config.actions}
                    onlyTrashed={this.state.onlyTrashed}
                />
                <List
                    url={this.props.config.url}
                    headers={this.props.config.headers}
                    perPage={this.props.config.perPage}
                />
            </div>
        </div>
    }

    handleTrashedEvent (value)
    {
        let options = {
            onlyTrashed : value,
        };

        if (value)
            options._title = this.props.config.title + ' (Trashed)';
        else
            options._title = this.props.config.title;

        this.setState(options);
    }
}

window.RenderAdminList = function (data) {
    ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'))
};



