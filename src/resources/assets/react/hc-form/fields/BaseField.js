import React, {Component} from 'react';
import axios from "axios/index";

let classNames = require('classnames');

export default class BaseField extends Component {

    constructor(props) {
        super(props);

        this.validationTimeOut = undefined;
        this.multiLanguage = false;
        this.multiLanguageValues = {};
        this.validationTimeOutMiliseconds = 400;

        this.state = {
            hasError: false,
            value: undefined,
            hideDependant: false,
            dependencyValues: {},
            loadingDisabled: false
        };

        if (this.props.config.dependencies) {
            this.state.hideDependant = true;
        }

        this.contentChange = this.contentChange.bind(this);
        this.validate = this.validate.bind(this);
        this.getValue = this.getValue.bind(this);
        this.getMultiLanguage = this.getMultiLanguage.bind(this);
    }

    componentWillUpdate(nextProps, nextState) {

        if (this.multiLanguage) {

            if (this.props.language !== nextProps.language) {
                this.multiLanguageValues[this.props.language] = this.refs.inputField.value;

                if (this.multiLanguageValues[nextProps.language] !== null && this.multiLanguageValues[nextProps.language] !== undefined)
                    this.refs.inputField.value = this.multiLanguageValues[nextProps.language];
                else
                    this.refs.inputField.value = null;
            }
        }
    }

    render() {

        let fieldClasses = classNames(
            "form-group", {
                "has-error": this.state.hasError,
                hidden: this.getHidden(),
            }
        );

        return <div className={fieldClasses}>
            {this.getLabel()}
            <div>
                {this.getInput()}
                {this.getNote()}
                {this.getMultiLanguage()}
            </div>
        </div>;
    }

    toggleDependency(value, data, dependencyOptions) {

        let dependencyValues = this.state.dependencyValues;

        //checking if data is object then adding it to dependency values
        Object.keys(data).map((key, i) => {

            if (HC.helpers.isArray(data[key])) {
                dependencyValues[key] = [];

                data[key].map((selection, i) => {
                    dependencyValues[key].push(selection.id);
                });
            }
            else if (HC.helpers.isObject(data[key])) {
                dependencyValues[key] = data[key].id;
            }
            else {
                dependencyValues[key] = data[key];
            }
        });

        this.setState(
            {
                hideDependant: value,
                dependencyValues: data
            });

        Object.keys(dependencyOptions).map((key, i) => {

            if (dependencyOptions[key].sendAs) {
                dependencyValues[dependencyOptions[key].sendAs] = dependencyValues[key];
                delete(dependencyValues[key]);
            }
        });

        if (value === false)
            if (this.props.config.url)
                this.loadOptions();
    }

    getHidden() {

        return (this.props.config.hidden || this.state.hideDependant);
    }

    getDisabled() {

        return (this.props.config.disabled || this.state.loadingDisabled);
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
                      disabled={this.getDisabled()}
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

        this.validationTimeOut = setTimeout(this.validate, this.validationTimeOutMiliseconds);
    }

    /**
     * Validating input field
     */
    validate() {
        let isValid = this.isValid();

        //TODO: do not trigger if not needed
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

        if (this.multiLanguage) {
            if (this.multiLanguageValues[this.props.language])
                this.refs.inputField.value = this.multiLanguageValues[this.props.language];
            else
                this.refs.inputField.value = "";
        }
        else {

            this.refs.inputField.value = value;
        }

        this.validate();
    }

    /**
     * Setting multi language values
     *
     * @param language
     * @param value
     */
    setMultiLanguageValue(language, value) {

        this.refs.inputField.value = this.multiLanguageValues[language] = value;

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

            {this.getLanguageSelector()}

        </select>,
            <div key={HC.helpers.uuid()} className="clearfix"/>];
    }

    getLanguageSelector() {
        let list = [];

        this.props.availableLanguages.map((item, i) => {
            list.push(<option key={i} value={item}>{item}</option>);
        });

        return list;
    }

    getOptions() {
        if (this.state.options)
            return this.state.options;

        if (this.props.config.options)
            return this.props.config.options;

        return [];
    }

    loadOptions() {

        this.setState({loadingDisabled: true});

        axios.get(this.props.config.url, {params: this.state.dependencyValues})
            .then(res => {
                this.setState({
                    loadingDisabled: false,
                    options: res.data
                });
                this.validate();
            });
    }
}