import React, {Component} from "react";

import Pagination from "rc-pagination";

import Actions from './../hc-admin-list/Actions';
import Settings from './../hc-admin-list/Settings';
import List from './../hc-admin-list/List';

import axios from "axios/index";
import Select from 'rc-select';

export default class HCAdminListView extends Component {

    /**
     * Initializing component
     *
     * @param props
     */
    constructor(props) {
        super(props);

        this.state = {
            id: HC.helpers.uuid(),
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
            pageSizeOptions: ["25", "50", "100", "500"],
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

        if (this.props.config.pageSizeOptions)
            this.state.pageSizeOptions = this.props.config.pageSizeOptions;

        this.handleTrashedEvent = this.handleTrashedEvent.bind(this);
        this.getCheckBoxConfiguration = this.getCheckBoxConfiguration.bind(this);
        this.selectionUpdated = this.selectionUpdated.bind(this);
        this.reload = this.reload.bind(this);
        this.onShowSizeChange = this.onShowSizeChange.bind(this);
        this.onSortOrderUpdate = this.onSortOrderUpdate.bind(this);
    }

    /**
     * When mounted, load data
     */
    componentDidMount() {
        this.handleTrashedEvent(false);
    }

    /**
     * Rendering view
     *
     * @returns {*}
     */
    render() {

        return <div className="box" id={this.id}>
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
                    url={this.props.config.url}
                    headers={this.props.config.headers}
                    perPage={this.props.config.perPage}
                    form={this.props.config.form}
                    hideCheckBox={this.state.hideCheckBox}
                    onlyTrashed={this.state.onlyTrashed}
                    actions={this.props.config.actions}
                    records={this.state.records}
                    selectionUpdated={this.selectionUpdated}
                    onSortOrderUpdate={this.onSortOrderUpdate}
                    reload={this.reload}
                />
                <Pagination selectComponentClass={Select}
                            showSizeChanger
                            showTotal={(total, range) => `${range[0]} - ${range[1]} of ${total} items`}
                            onShowSizeChange={this.onShowSizeChange}
                            defaultCurrent={1}
                            total={this.state.records.total}
                            current={this.state.records.current_page}
                            pageSize={this.state.records.per_page}
                            pageSizeOptions={this.state.pageSizeOptions}
                            onChange={this.onShowSizeChange}/>
            </div>
        </div>
    }

    onSortOrderUpdate(key, order) {
        this.state.params.params.page = 1;
        this.state.params.params.sort_by = key;
        this.state.params.params.sort_order = order;

        this.reload();

    }

    /**
     * On showSizeChange or pagination change reload content
     * @param current
     * @param pageSize
     */
    onShowSizeChange(current, pageSize) {
        this.state.params.params.page = current;

        if (pageSize)
            this.state.params.params.per_page = pageSize;

        this.reload();
    }

    /**
     * Selected items checking
     *
     * @param selected
     */
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

    /**
     * Switch between trashed and live items
     *
     * @param value
     */
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

        this.reload();
    }

    /**
     * Reload page with data or without it.
     *
     * @param data
     */
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

    /**
     * Get mainCheckBox configuration
     *
     * @param trashed
     * @returns {boolean}
     */
    getCheckBoxConfiguration(trashed) {

        if (trashed)
            return (this.props.config.actions.indexOf('forceDelete') === -1 && this.props.config.actions.indexOf('restore') === -1);
        else
            return (this.props.config.actions.indexOf('delete') === -1 && this.props.config.actions.indexOf('merge') === -1 && this.props.config.actions.indexOf('clone') === -1);
    }
}