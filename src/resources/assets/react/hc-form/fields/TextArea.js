import React from 'react'
import Base from "./BaseField";

export default class TextArea extends Base {

    getInput ()
    {
        return <textarea
                      ref="inputField"
                      placeholder={this.props.config.label}
                      className="form-control"
                      readOnly={this.props.config.readonly}
                      disabled={this.props.config.disabled}
                      onChange={this.contentChange}/>;
    }

    /**
     * Creating select options
     *
     * @returns {Array}
     */
    getOptions ()
    {
        let list = [];

        if (!this.props.config.required)
        {
            list.push (<option key={-1} value="undefined">Please select:</option>)
        }

        this.props.config.options.map((item, i) => list.push(<option key={i} value={item.id}>{item.label}</option>));

        return list;
    }

    /**
     * If input required validate first option
     */
    componentDidMount()
    {
        if(this.props.config.required)
            this.validate();
    }

    /**
     * Validating input
     *
     * @returns {boolean}
     */
    isValid ()
    {
        if (this.props.config.required)
            if (this.props.config.options.indexOf(this.refs.inputField.value) === -1)
                return false;

        return true;
    }

    /**
     * Getting value
     * 
     * @returns {undefined}
     */
    getValue ()
    {
        if (this.refs.inputField.value === "undefined")
            return undefined;

        return this.refs.inputField.value;
    }

}