import React, {Component} from 'react';
import Row from "./list/Row";

const uuid = require('uuid/v4');

export default class List extends Component {

    /**
     * initializing component
     * @param props
     */
    constructor(props) {
        super(props);

        this.state = {
            listId: uuid(),
            globalSelection: false,
            allSelected: false,
            selected: []
        };

        this.invertAll = this.invertAll.bind(this);
        this.getRows = this.getRows.bind(this);
        this.singleRowSelect = this.singleRowSelect.bind(this);
    }

    /**
     * Checking if component needs to be re-rendered
     * @param nextProps
     * @param nextState
     * @returns {boolean}
     */
    shouldComponentUpdate(nextProps, nextState) {

        //checking if internal Update
        if (typeof(this.state.internalUpdate) === 'undefined')
            return true;

        //checking if mainCheckBox was clicked
        if (this.state.globalSelection !== nextState.globalSelection)
            return true;

        //checking if trashed changes
        if (this.props.onlyTrashed !== nextProps.onlyTrashed)
            return true;

        //checking if records has changed
        if (this.props.records.data.toString() !== nextProps.records.data.toString())
                return true;

        this.state.internalUpdate = false;

        return this.state.internalUpdate;
    }

    /**
     * Inverting list selection when mainCheckbox has been clicked
     */
    invertAll() {

        let selectAll = !this.state.allSelected;
        let options = {
            globalSelection: selectAll,
            allSelected: selectAll,
        };

        if (!selectAll)
            options.selected = [];
        else
            this.props.records.data.map((item, i) => (
                this.singleRowSelect(item.id, true, true)
            ));

        this.setState(options);

        if (!selectAll)
            this.initialiseMainCheckBoxUpdate(options.selected);
    }

    /**
     * Single row selection update
     * @param record
     * @param value
     */
    singleBoxClick(record, value) {
        this.state.selections[record.id] = value;
        this.setState({selections: this.state.selections});
    }

    /**
     * Rendering view
     * @returns {*}
     */
    render() {

        return <div id="list">
            <table id={this.state.listId} className="table table-hover table-bordered dataTable" role="grid">
                <thead>
                <tr role="row">
                    <th hidden={this.props.hideCheckBox} className="main-checkbox"><input type="checkbox"
                                                                                          checked={this.state.allSelected}
                                                                                          onChange={this.invertAll}/>
                    </th>
                    {Object.keys(this.props.headers).map((item, i) => (
                            <th tabIndex="0"
                                aria-controls={this.state.listId}
                                key={i}
                                className="sorting">{this.props.headers[item].label}</th>
                        )
                    )}
                </tr>
                </thead>
                <tbody>
                {this.getRows()}
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>;
    }

    /**
     * Getting rows for a list
     */
    getRows() {

        this.state.rows = [];

        this.props.records.data.map((item, i) => (
            this.state.rows.push(<Row key={i} record={item} headers={this.props.headers}
                                      globalSelection={this.state.globalSelection} onChange={this.singleRowSelect}/>)
        ));

        return this.state.rows;
    }

    /**
     * Updating selected list
     *
     * @param id
     * @param select
     * @param skipMainStateUpdate
     */
    singleRowSelect(id, select, skipMainStateUpdate) {
        if (select) {
            if (this.state.selected.indexOf(id) === -1)
                this.state.selected.push(id);
        }
        else {
            this.state.selected.splice(this.state.selected.indexOf(id), 1)
        }

        if (!skipMainStateUpdate) {
            if (this.state.selected.length === this.props.records.to - this.props.records.from + 1)
                this.setState({allSelected: true, globalSelection: true});
            else if (this.state.allSelected)
                this.setState({allSelected: false});
        }

        this.updateActions(this.state.selected);
    }

    /**
     * Updating updateActions
     *
     * @param selected
     */
    updateActions(selected) {
        this.state.internalUpdate = true;
        this.props.selectionUpdated(selected);
    }
}