import React from 'react'
import BaseField from "./BaseField";

export default class HCNumber extends BaseField {

    constructor(props) {
        super(props);

        this.numberChange = this.numberChange.bind(this);
    }

    isValid() {

        if (!this.props.config.required) {
            if (this.getValue() === "")
                return true;
        }

        if (this.props.config.int) {
            return Number.isInteger(Number(this.getValue()));
        }

        return true;
    }

    /**
     * Getting input field
     *
     * @returns {*}
     */
    getInput() {

        let inputClasses = this.getClassNames({
            "form-control": true,
            "multi-language": this.props.config.multiLanguage
        });

        return <input type="number"
                      ref="inputField"
                      placeholder={this.props.config.label}
                      className={inputClasses}
                      readOnly={this.props.config.readonly}
                      disabled={this.getDisabled()}
                      step={this.props.config.step}
                      min={this.props.config.min}
                      max={this.props.config.max}
                      onChange={this.numberChange}/>;
    }

    numberChange() {
        if (this.refs.inputField.value < this.props.config.min) {
            this.refs.inputField.value = this.props.config.min;
        }

        if (this.refs.inputField.value > this.props.config.max) {
            this.refs.inputField.value = this.props.config.max;
        }

        this.contentChange();
    }
}

HC.formFields.register('number', HCNumber);