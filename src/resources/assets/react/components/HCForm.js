import React, {Component} from 'react'
import axios from "axios/index";

export default class HCForm extends Component {

    constructor(props) {
        super(props);

        this.finalFieldStructure = [];

        this.existingRecord = {};
        this.record = {};

        this.state = {
            id: HC.helpers.uuid(),
            formData: {},
            formDisabled: false,
            language: null
        };

        this.opacity = 0;
        this.dependencyFields = null;

        this.getFields = this.getFields.bind(this);
        this.updateFormData = this.updateFormData.bind(this);
        this.submitData = this.submitData.bind(this);
        this.languageChange = this.languageChange.bind(this);
        this.updateDependencies = this.updateDependencies.bind(this);
        this.handleSubmitComplete = this.handleSubmitComplete.bind(this);
        this.handleSubmitError = this.handleSubmitError.bind(this);
    }

    render() {

        return <div ref="formHolder" id={this.state.id} className="hc-form">
            <div className="form-structure">
                {this.getFields()}
            </div>
            <div className="footer">
                {this.getButtons()}
            </div>
        </div>;
    }

    /**
     * When component has mounted, load form data
     */
    componentDidMount() {

        if (this.props.structure) {
            let stateObject = {
                formData: {
                    structure: this.props.structure
                }
            };

            this.setState(stateObject);
        }
        else {
            this.loadFormData();
        }
    }

    /**
     * When form has been completely loaded
     * Fill in the information which is available
     */
    fillForm() {

        if (Object.keys(this.existingRecord).length > 0) {
            Object.keys(this.finalFieldStructure).map((key, i) => {

                let value = this.existingRecord[key];

                if (key.indexOf('.') !== -1) {

                    let keySequence = key.split('.');
                    if (keySequence[0] === 'translations') {

                        if (this.existingRecord['translations']) {
                            this.existingRecord[keySequence[0]].map((item, i) => {

                                this.refs[key].setMultiLanguageValue(item['language_code'], item[keySequence[1]]);
                            });
                        }
                        else if (value !== null && value !== undefined) {

                            this.refs[key].setValue(value);
                        }
                    }
                }
                else if (value !== null && value !== undefined) {

                    this.refs[key].setValue(value);
                }
            });
        }
    }

    /**
     * Loading form data
     */
    loadFormData() {

        let scope = this;

        HC.react.loader.get(this.props.config.url, this.props.config.params, function (response) {

            let stateObject = {
                formData: response
            };

            if (response.availableLanguages && response.availableLanguages.length > 0) {
                stateObject.language = response.availableLanguages[0];
            }

            if (scope.props.config.recordId) {
                HC.react.loader.get(response.storageUrl + '/' + scope.props.config.recordId, null, function (response) {
                    scope.existingRecord = response;

                    scope.setState(stateObject);
                    scope.fillForm();
                });
            }
            else {
                scope.setState(stateObject);
                scope.updateDependencies();
            }
        });
    }

    /**
     * Getting all fields
     *
     * @returns {Array}
     */
    getFields() {
        let structure = this.state.formData.structure;
        this.finalFieldStructure = [];

        if (!structure)
            return this.finalFieldStructure;

        Object.keys(structure).map((key, i) => (
            this.finalFieldStructure[key] = this.getField(structure[key], key, i)
        ));

        let finalArray = [];

        Object.keys(this.finalFieldStructure).map((key, i) => finalArray.push(this.finalFieldStructure[key]));

        return finalArray;
    }

    /**
     * Getting single field
     *
     * @param data
     * @param ref
     * @param i
     * @returns {*}
     */
    getField(data, ref, i) {

        data.updateFormData = this.updateFormData;

        const FieldTagName = HC.formFields.get(data.type);

        if (!FieldTagName)
            return '';

        return <FieldTagName key={i}
                             config={data}
                             ref={ref}
                             id={ref}
                             language={this.state.language}
                             onLanguageChange={this.languageChange}
                             availableLanguages={this.state.formData.availableLanguages}/>;
    }

    languageChange(language) {
        this.setState({language: language});
    }

    /**
     * Updating form data
     *
     * @param fieldId
     * @param value
     */
    updateFormData(fieldId, value) {

        this.record[fieldId] = value;
        this.updateDependencies(fieldId);

        if (this.props.onSelectionChange)
            this.props.onSelectionChange(this.record);
    }

    /**
     * Updating dependencies
     * @param fieldChanged
     */
    updateDependencies(fieldChanged = null) {
        if (!this.dependencyFields) {
            this.dependencyFields = {};
            Object.keys(this.state.formData.structure).map((item) => {
                if (this.state.formData.structure[item].dependencies) {
                    this.dependencyFields[item] = (this.state.formData.structure[item]);
                }
            });
        }
        //TODO: check if changed field is in dependencies, if not do not update components

        //running through fields which has dependencies
        Object.keys(this.dependencyFields).map((key) => {

            //checking for field names in dependency list
            if (this.refs[key]) {

                // getting dependency configuration
                let dependant = this.dependencyFields[key];

                if (!fieldChanged || dependant.dependencies[fieldChanged]) {
                    let hide = false;
                    let hideList = {};
                    let fieldsData = {};
                    let scope = this;

                    // running through every dependency
                    Object.keys(dependant.dependencies).map(function (targetKey) {

                        hideList[targetKey] = true;

                        if (scope.record[targetKey] === undefined)
                            return;

                        let config = dependant.dependencies[targetKey];
                        fieldsData[targetKey] = scope.record[targetKey];

                        if (HC.helpers.isObject(config)) {

                            if (config.value.indexOf(scope.record[targetKey].id) >= 0) {
                                hideList[targetKey] = false;
                            }

                        } else if (HC.helpers.isArray(config)) {

                            if (config.length > 0) {
                                if (config.indexOf(scope.record[targetKey]) >= 0) {
                                    hideList[targetKey] = false;
                                }
                            }
                            else {
                                if (HC.helpers.isArray(scope.record[targetKey])) {
                                    if (scope.record[targetKey].length > 0) hideList[targetKey] = false;
                                } else {
                                    hideList[targetKey] = false;
                                }
                            }
                        }
                    });

                    Object.keys(hideList).map((key) => {

                        if (hideList[key] === true) {
                            hide = true;
                        }
                    });

                    this.refs[key].toggleDependency(hide, fieldsData, dependant.dependencies);
                }
            }
        });
    }

    /**
     * Get buttons
     *
     * @returns {*}
     */
    getButtons() {
        let buttons = this.state.formData.buttons;
        let finalButtons = [];

        if (!buttons) {
            return finalButtons;
        }

        Object.keys(buttons).map((item, i) => (
            finalButtons.push(this.getButton(item, buttons[item], i))
        ));

        return finalButtons;
    }

    /**
     *
     * Get button
     *
     * @param type
     * @param data
     * @param i
     * @returns {*}
     */
    getButton(type, data, i) {
        switch (type) {
            case "submit" :

                return <button disabled={this.state.formDisabled}
                               key={i}
                               className={HC.helpers.buttonClass('primary')}
                               onClick={this.submitData}>{data.label}</button>;
        }
    }

    /**
     * Submitting data
     */
    submitData() {
        let valid = true;

        Object.keys(this.finalFieldStructure).map((key, i) => {

            if (!this.refs[key].validate(true))
                valid = false;
        });

        if (!valid)
            return;

        let finalRecordStructure = this.finalizeStructure();

        this.setState({formDisabled: true});

        if (this.props.config.recordId)
            HC.react.loader.put(this.state.formData.storageUrl + '/' + this.props.config.recordId, finalRecordStructure, this.handleSubmitComplete, false, this.handleSubmitError);
        else
            HC.react.loader.post(this.state.formData.storageUrl, finalRecordStructure, this.handleSubmitComplete, false, this.handleSubmitError);
    }

    /**
     * Finalizing structure
     *
     * @returns {{}}
     */
    finalizeStructure() {
        let structure = {};

        Object.keys(this.record).map((item, i) => {

            if (item.indexOf('.') === -1) {
                structure[item] = this.record[item];
            }
            else {
                let keys = item.split('.');

                if (keys[0] === 'translations' && keys.length === 2) {

                    let languages = Object.keys(this.record[item]);

                    if (!structure[keys[0]])
                        structure[keys[0]] = [];

                    languages.map((language, i) => {
                        let index = HC.helpers.getTranslationsLanguageElementIndex(language, structure[keys[0]]);

                        if (!structure[keys[0]][index]) {
                            structure[keys[0]][index] = {};
                            structure[keys[0]][index]['language_code'] = language;
                        }

                        structure[keys[0]][index][keys[1]] = this.record[item][language];
                    });
                }
            }
        });

        return structure;
    }

    /**
     * After submit completed redirect
     * @param r
     */
    handleSubmitComplete(r) {

        if (r.success) {
            if (r.redirectUrl)
                document.location.href = r.redirectUrl;

            if (this.props.config.createdCallback)
                this.props.config.createdCallback.call(this.props.config.createdCallbackScope, r.data);

            this.props.formClosed();
        }
    }

    handleSubmitError() {
        this.setState({formDisabled: false});
    }

    /**
     * Reset form values
     */
    reset() {
        Object.keys(this.finalFieldStructure).map((key) => {
            this.refs[key].reset();
        });
    }
}