import React, {Component} from 'react';
import Row from "./list/Row";

const uuid = require('uuid/v4');

export default class List extends Component {
    constructor(props) {
        super(props);

        this.state = {
            listId: uuid(),
            globalSelection: false,
            allSelected: false,
            onlyTrashed: false,
            headers: {},
            selected: []
        };

        this.invertAll = this.invertAll.bind(this);
        this.getRows = this.getRows.bind(this);
        this.updateMainCheckBox = this.updateMainCheckBox.bind(this);
    }

    shouldComponentUpdate(nextProps, nextState) {
        if (typeof(this.state.internalUpdate) === 'undefined')
            return true;

        if (this.state.globalSelection !== nextState.globalSelection)
            return true;

        if (this.state.onlyTrashed !== nextState.onlyTrashed)
            return true;

        this.state.internalUpdate = false;

        return this.state.internalUpdate;
    }

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
                this.updateMainCheckBox(item.id, true, true)
            ));

        this.setState(options);

        if (!selectAll)
            this.initialiseMainCheckBoxUpdate(options.selected);
    }

    singleBoxClick(record, value) {
        this.state.selections[record.id] = value;
        this.setState({selections: this.state.selections});
    }

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

    getRows() {

        this.state.rows = [];

        this.props.records.data.map((item, i) => (
            this.state.rows.push(<Row key={i} record={item} headers={this.props.headers}
                                      globalSelection={this.state.globalSelection} onChange={this.updateMainCheckBox}/>)
        ));

        return this.state.rows;
    }

    updateMainCheckBox(id, select, skipMainStateUpdate) {
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

        this.initialiseMainCheckBoxUpdate(this.state.selected);
    }

    initialiseMainCheckBoxUpdate(selected) {
        this.state.internalUpdate = true;
        this.props.selectionUpdated(selected);
    }
}