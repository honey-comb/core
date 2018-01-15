import React, {Component} from 'react';

let classNames = require('classnames');

export default class BaseField extends Component {

    constructor(props) {
        super(props);

        this.validationTimeOut = undefined;

        this.state = {
            hasError: false
        };

        this.contentChange = this.contentChange.bind(this);
        this.validate = this.validate.bind(this);
        this.getValue = this.getValue.bind(this);
    }


    render() {
        let fieldClasses = classNames(
            "form-group", {
                "has-error": this.state.hasError,
                hidden: this.props.config.hidden,
            }
        );

        return <div className={fieldClasses}>
            {this.getLabel()}
            {this.getInput()}
            {this.getNote()}
        </div>;
    }

    /**
     * Creating label div
     *
     * @returns {*}
     */
    getLabel() {

        if (!this.props.config.label)
            return "";

        return <label htmlFor={this.props.config.fieldId}>{this.props.config.label} {this.getRequired()}</label>;
    }

    /**
     * Getting input field
     *
     * @returns {*}
     */
    getInput() {

        return <input type={this.props.config.type}
                      ref="inputField"
                      placeholder={this.props.config.label}
                      className="form-control"
                      required
                      readOnly={this.props.config.readonly}
                      disabled={this.props.config.disabled}
                      onChange={this.contentChange}/>;

        return <div className="input-group">
            {this.getInputAddon(true)}
            "INPUT"
            {this.getInputAddon(false)}
        </div>
    }

    /**
     * Getting input addon
     *
     * @param first
     * @returns {*}
     */
    getInputAddon(first) {
        return "";

        return <div className="input-group-addon">$</div>;
    }

    getRequired ()
    {
        if (!this.props.config.required)
            return "";

        return <span className="required">*</span>
    }

    /**
     * Getting annotation
     *
     * @returns {*}
     */
    getNote() {

        if (!this.props.config.note)
            return "";

        return <small id="emailHelp" className="form-text text-muted">{this.props.config.note}</small>;
    }

    /**
     * On content change set validation timeout
     */
    contentChange() {

        if (this.validationTimeOut)
            clearTimeout(this.validationTimeOut);

        this.validationTimeOut = setTimeout(this.validate, 400);
    }

    /**
     * Validating input field
     */
    validate() {
        let isValid = this.isValid();

        if (isValid) {
            this.triggerChange();
        }

        this.setState({hasError: !isValid});

        return isValid;
    }

    /**
     * If data is valid trigger change
     */
    triggerChange() {
        this.props.config.updateFormData(this.props.config.fieldId, this.getValue());
    }

    /**
     * Checking if value is valid
     *
     * @returns {boolean}
     */
    isValid() {
        return true;
    }

    /**
     * Getting value
     */
    getValue() {
        return this.refs.inputField.value;
    }
}