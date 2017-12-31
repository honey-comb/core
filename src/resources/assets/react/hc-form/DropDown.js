import React, {Component} from 'react'

export default class DropDown extends Component {
    render() {

        let fgClass = "form-group ";

        if (this.props.oneLine)
            fgClass += "one-line";

        return <div className={fgClass}>
            <label>Select</label>
            <select className="form-control">
                <option>option 1</option>
                <option>option 2</option>
                <option>option 3</option>
                <option>option 4</option>
                <option>option 5</option>
            </select>
        </div>;
    }
}