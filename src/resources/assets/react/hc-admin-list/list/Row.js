import React, {Component} from 'react';

export default class Row extends Component {

    constructor(props) {
        super(props);

        this.state = {
            selected: false
        };

        this.handleRowCheckBoxClick = this.handleRowCheckBoxClick.bind(this);
    }

    componentWillUpdate(nextProps, nextState) {
        this.state.selected = nextProps.globalSelection;
    }

    render() {

        let selectedClass = this.state.selected ? "selected" : "";

        return <tr id={this.props.record.id} className={selectedClass} key={this.props.key}>
            <td hidden={this.props.hideCheckBox}
                onClick={this.handleRowCheckBoxClick}>
                <input type="checkbox"
                       checked={this.state.selected}/>
            </td>

            {Object.keys(this.props.headers).map((item, i) => (
                this.getDataRowField(item, this.props.record[item], i)
            ))}
        </tr>
    }

    getDataRowField(id, value, key) {
        if (id === 'id')
            return <td key={key}
                       hidden={true}>{value}</td>;

        switch (this.props.headers[id].type) {
            case 'text' :

                break;
        }

        return <td key={key}>{value}</td>;
    }

    handleRowCheckBoxClick() {

        let selected = !this.state.selected;

        this.setState({selected: selected});
        this.props.onChange(this.props.record.id, selected);
    }
}