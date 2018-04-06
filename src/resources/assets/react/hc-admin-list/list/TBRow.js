import React, {Component} from 'react';
import TBCell from "./cells/TBCell";

export default class TBRow extends Component {

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

        return <tr id={this.props.record.id} className={selectedClass}>
            <td hidden={this.props.hideCheckBox}
                onClick={this.handleRowCheckBoxClick}>
                <input type="checkbox"
                       checked={this.state.selected}/>
            </td>

            {Object.keys(this.props.headers).map((item, i) => (
                <TBCell config={this.props.headers[item]}
                        value={HC.helpers.pathIndex(this.props.record, item)}
                        key={i}
                        fieldKey={item}
                        form={this.props.form}
                        update={this.props.update}
                        id={this.props.record.id}
                        reload={this.props.reload}
                        record={this.props.record}
                        url={this.props.url}/>
            ))}
        </tr>
    }

    handleRowCheckBoxClick() {

        let selected = !this.state.selected;

        this.setState({selected: selected});
        this.props.onChange(this.props.record.id, selected);
    }
}