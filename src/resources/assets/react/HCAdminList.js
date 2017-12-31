/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('../../js/bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./shared/HCHelpers');

import React from 'react';
import ReactDOM from 'react-dom';

import HCBaseComponent from './shared/HCBaseComponent';
import Actions from './hc-admin-list/Actions';
import Settings from './hc-admin-list/Settings';

import fontAwesome from '@fortawesome/fontawesome'
import FAProRegularIcons from '@fortawesome/fontawesome-pro-regular'

class HCAdminListView extends HCBaseComponent {

    constructor ()
    {
        super();
        fontAwesome.library.add(FAProRegularIcons);
    }

    render() {

        return <div className="box">
            <div className="box-header">
                <h3 className="box-title">{this.props.config.title}</h3>
                <Settings onPress={this.toggleView}/>
            </div>
            <div className="box-body">
                <Actions
                    url={this.props.config.url}
                    forms={this.props.config.forms}
                    actions={this.props.config.actions}
                />
            </div>
        </div>
    }
}

window.RenderAdminList = function (data) {
    ReactDOM.render(<HCAdminListView config={data}/>, document.getElementById('admin-list'))
};



