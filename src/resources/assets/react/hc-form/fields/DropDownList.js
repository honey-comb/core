import React from 'react'
import Base from "./BaseField";

export default class DropDownList extends Base {

    getInput() {
        return <select className="form-control"
                       ref="inputField"
                       disabled={this.props.config.disabled || this.props.config.readonly}
                       onChange={this.contentChange}>

            {this.getOptions()}

        </select>
    }

    /**
     * Creating select options
     *
     * @returns {Array}
     */
    getOptions() {
        let list = [];

        if (!this.props.config.required) {
            list.push(<option key={-1} value="undefined">Please select:</option>)
        }

        this.props.config.options.map((item, i) => list.push(<option key={i} value={item.id}>{item.label}</option>));

        return list;
    }

    /**
     * If input required validate first option
     */
    componentDidMount() {
        if (this.props.config.required)
            this.validate();
    }

    /**
     * Validating input
     *
     * @returns {boolean}
     */
    isValid() {
        if (this.props.config.required)
            if (!this.refs.inputField.value)
                return false;

        return true;
    }

    /**
     * Getting value
     *
     * @returns {undefined}
     */
    getValue() {
        if (this.refs.inputField.value === "undefined")
            return undefined;

        return this.refs.inputField.value;
    }

}