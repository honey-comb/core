import React, {Component} from 'react';
import TDRow from "./list/TBRow";
import THRow from "./list/THRow";

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
            update: this.props.config.actions.indexOf('update') !== -1 ? 1 : 0,
            selected: [],
            sortBy: {},
            listHeight: {}
        };

        this.invertAll = this.invertAll.bind(this);
        this.getRows = this.getRows.bind(this);
        this.singleRowSelect = this.singleRowSelect.bind(this);
        this.handleResize = this.handleResize.bind(this);
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
        if (this.props.onlyTrashed !== nextProps.onlyTrashed) {
            nextState.allSelected = false;
            nextState.globalSelection = false;
            nextState.selected = [];

            if (nextProps.onlyTrashed)
                nextState.update = false;
            else
                nextState.update = this.props.config.actions.indexOf('update') !== -1 ? 1 : 0;

            return true;
        }

        //checking if records has changed
        if (!HC.helpers.arraysEqual(this.props.records, nextProps.records)) {
            nextState.allSelected = false;
            nextState.globalSelection = false;
            nextState.selected = [];
            return true;
        }

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
            this.updateActions(options.selected);
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

        return <div>

            <div className="list" style={{overflow: 'auto', height:this.state.listHeight}} ref="listArea">
                <table id={this.state.listId} className="table table-hover table-bordered" role="grid">
                    <thead>
                    <THRow hideCheckBox={this.props.hideCheckBox}
                           headers={this.props.config.headers}
                           invertAll={this.invertAll}
                           checked={this.state.allSelected}
                           onSortOrderUpdate={this.props.onSortOrderUpdate}/>
                    </thead>
                    <tbody>
                    {this.getRows()}
                    </tbody>
                </table>
            </div>
        </div>;
    }

    componentDidMount ()
    {
        window.addEventListener('resize', this.handleResize);
        this.calculateListHeight(window.innerHeight);
    }

    handleResize (e)
    {
        this.calculateListHeight(e.currentTarget.innerHeight);
    }

    calculateListHeight (height)
    {
        height -= 345;
        this.setState({listHeight:height});
    }

    /**
     * Getting rows for a list
     */
    getRows() {

        this.state.rows = [];

        this.props.records.data.map((item, i) => (
            this.state.rows.push(<TDRow key={i}
                                        record={item}
                                        update={this.state.update}
                                        config={this.props.config}
                                        globalSelection={this.state.globalSelection}
                                        onChange={this.singleRowSelect}
                                        reload={this.props.reload}
                                        hideCheckBox={this.props.hideCheckBox}/>)
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