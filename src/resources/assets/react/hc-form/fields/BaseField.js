import React, {Component} from 'react';

let classNames = require('classnames');

export default class BaseField extends Component {

    constructor(props) {
        super(props);

        this.validationTimeOut = undefined;
        this.multiLanguage = false;
        this.multiLanguageValues = {};

        this.state = {
            hasError: false,
            value: undefined
        };

        this.contentChange = this.contentChange.bind(this);
        this.validate = this.validate.bind(this);
        this.getValue = this.getValue.bind(this);
        this.getMultiLanguage = this.getMultiLanguage.bind(this);
    }

    componentWillUpdate(nextProps, nextState) {
        if (this.multiLanguage) {
            if (this.props.language !== nextProps.language) {
                this.multiLanguageValues[this.props.language] = this.refs.inputField.value;
                this.contentChange();
            }
        }
    }

    render() {
        let fieldClasses = classNames(
            "form-group", {
                "has-error": this.state.hasError,
                hidden: this.props.config.hidden,
            }
        );

        console.log('*', this.multiLanguageValues);

        return <div className={fieldClasses}>
            {this.getLabel()}
            <div>
                {this.getInput()}
                {this.getNote()}
                {this.getMultiLanguage()}
            </div>
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

        return <label htmlFor={this.props.id}>{this.props.config.label} {this.getRequired()}</label>;
    }

    /**
     * Getting input field
     *
     * @returns {*}
     */
    getInput() {

        let inputClasses = classNames({
            "form-control": true,
            "multi-language": this.props.config.multiLanguage
        });

        return <input type={this.props.config.type}
                      ref="inputField"
                      placeholder={this.props.config.label}
                      className={inputClasses}
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

    getRequired() {
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
        this.props.config.updateFormData(this.props.id, this.getValue());
    }

    /**
     * Checking if value is valid
     *
     * @returns {boolean}
     */
    isValid() {

        if (this.props.config.required) {
            if (this.getValue() == null || this.getValue() === "")
                return false;
        }

        return true;
    }

    /**
     * Getting value
     */
    getValue() {

        if (!this.multiLanguage)
            return this.refs.inputField.value;

        return this.getMultiLanguageValue();
    }

    getMultiLanguageValue() {

        this.multiLanguageValues[this.props.language] = this.refs.inputField.value;
        return this.multiLanguageValues;
    }

    /**
     * Setting value
     *
     * @param value
     */
    setValue(value) {

        this.refs.inputField.value = value;

        this.validate();
    }

    /**
     * Setting multi language values
     *
     * @param language
     * @param value
     */
    setMultiLanguageValue (language, value)
    {
        this.multiLanguageValues[language] = value;

        if (this.multiLanguageValues[this.props.language])
            this.refs.inputField.value = this.multiLanguageValues[this.props.language];
        else
            this.refs.inputField.value = "";

        this.validate();
    }

    /**
     *
     */
    getMultiLanguage() {

        if (!this.props.availableLanguages || !this.props.config.multiLanguage)
            return "";

        this.multiLanguage = true;

        return [<select key={HC.helpers.uuid()} className="form-control multi" ref="multiLanguage"
                        onChange={(e) => this.props.onLanguageChange(this.refs.multiLanguage.value)}
                        value={this.props.language}>

            {this.getMultiLanguageOptions()}

        </select>,
        <div key={HC.helpers.uuid()} className="clearfix"></div>];
    }

    getMultiLanguageOptions() {
        let list = [];

        this.props.availableLanguages.map((item, i) => {
            list.push(<option key={i} value={item}>{item}</option>);
        });

        return list;
    }
}