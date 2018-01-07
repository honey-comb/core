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
import Pagination from "rc-pagination";

import Actions from './hc-admin-list/Actions';
import Settings from './hc-admin-list/Settings';

import fontAwesome from '@fortawesome/fontawesome'
import FAProRegularIcons from '@fortawesome/fontawesome-pro-regular'
import axios from "axios/index";
import Select from 'rc-select';

import 'rc-pagination/assets/index.css';
import 'rc-select/assets/index.css';

class HCAdminListView extends Component {

    constructor(props) {
        super(props);

        this.state = {
            title: this.props.config.title,
            records: {
                current_page: 0,
                data: [],
                first_page_url: "",
                from: 0,
                last_page: 0,
                last_page_url: "",
                next_page_url: "",
                path: "",
                per_page: this.props.config.perPage,
                prev_page_url: "",
                to: 0,
                total: 0
            },
            onlyTrashed: false,
            selected: [],
            hideCheckBox: this.getCheckBoxConfiguration(false),
            actionsDisabled: {
                delete: true,
                merge: true,
                clone: true,
                forceDelete: true,
                restore: true,
            },
        };

        this.handleTrashedEvent = this.handleTrashedEvent.bind(this);
        this.getCheckBoxConfiguration = this.getCheckBoxConfiguration.bind(this);
        this.selectionUpdated = this.selectionUpdated.bind(this);
        this.loadList = this.loadList.bind(this);
        this.reload = this.reload.bind(this);
        this.onShowSizeChange = this.onShowSizeChange.bind(this);

        fontAwesome.library.add(FAProRegularIcons);
    }

    componentDidMount() {
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
                    reload={this.reload}
                />
                <List
                    headers={this.props.config.headers}
                    perPage={this.props.config.perPage}
                    hideCheckBox={this.state.hideCheckBox}
                    onlyTrashed={this.state.onlyTrashed}
                    records={this.state.records}
                    selectionUpdated={this.selectionUpdated}
                />
                <Pagination selectComponentClass={Select}
                            showSizeChanger
                            showTotal={(total, range) => `${range[0]} - ${range[1]} of ${total} items`}
                            onShowSizeChange={this.onShowSizeChange}
                            defaultCurrent={1}
                            total={this.state.records.total}
                            current={this.state.records.current_page}
                            pageSize={this.state.records.per_page}
                            onChange={this.onShowSizeChange}/>
            </div>
        </div>
    }

    onShowSizeChange (current, pageSize)
    {
        this.state.params.params.page = current;

        if (pageSize)
            this.state.params.params.per_page = pageSize;

        this.reload ();
    }

    selectionUpdated(selected) {
        let actionsDisabled = {
            delete: true,
            merge: true,
            clone: true,
            forceDelete: true,
            restore: true,
        };

        if (selected.length > 0) {
            actionsDisabled.delete = false;
            actionsDisabled.forceDelete = false;
            actionsDisabled.restore = false;

            if (selected.length > 1)
                actionsDisabled.merge = false;
            else
                actionsDisabled.clone = false;
        }


        this.setState(
            {
                selected: selected,
                actionsDisabled: actionsDisabled
            })
    }

    handleTrashedEvent(value) {
        this.state.onlyTrashed = value;
        this.state.records = {
            current_page: 0,
            data: [],
            first_page_url: "",
            from: 0,
            last_page: 0,
            last_page_url: "",
            next_page_url: "",
            path: "",
            per_page: this.props.config.perPage,
            prev_page_url: "",
            to: 0,
            total: 0
        };

        if (value) {
            this.state.title = this.props.config.title + ' (Trashed)';
            this.state.hideCheckBox = this.getCheckBoxConfiguration(true);

            this.state.params = {params: {trashed: 1}};
        }
        else {
            this.state.title = this.props.config.title;
            this.state.hideCheckBox = this.getCheckBoxConfiguration(false);
            this.state.params = {params: {}};
        }

        this.setState(this.state);

        this.loadList();
    }

    loadList() {
        this.setState({selected: []});

        axios.get(this.props.config.url, this.state.params)
            .then(res => {

                this.setState({
                    records: res.data,
                });
            });
    }

    reload(data) {
        this.setState({selected: []});

        if (data) {
            this.setState({
                records: data.data
            });
        }
        else
            axios.get(this.props.config.url, this.state.params)
                .then(res => {

                    this.setState({
                        records: res.data,
                    });
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