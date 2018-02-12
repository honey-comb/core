import React from 'react'
import Base from "./BaseField";

export default class DropDownList extends Base {

    constructor(props) {
        super(props);

        this.validationTimeOutMiliseconds = 0;
    }

    getInput() {

        return <select className="form-control"
                       ref="inputField"
                       disabled={this.getDisabled()}
                       onChange={this.contentChange}>

            {this.getOptionsFormatted()}

        </select>
    }

    /**
     * If input required validate first option
     */
    componentDidMount() {

        if (this.props.config.required)
            this.validate();
    }

    /**
     * Creating select options
     *
     * @returns {Array}
     */
    getOptionsFormatted() {
        let list = [];
        let options = this.getOptions();

        if (!this.props.config.required) {
            list.push(<option key={-1} value="undefined">Please select:</option>)
        }

        if (options)
            options.map((item, i) => list.push(<option key={i} value={item.id}>{item.label}</option>));

        return list;
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