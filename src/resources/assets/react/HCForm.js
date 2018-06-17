/*
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

import React, {Component} from 'react'

let classNames = require('classnames');

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
            language: null,
            currentTab: undefined
        };

        this.opacity = 0;
        this.dependencyFields = null;
        this.listenTo = null;

        this.getFields = this.getFields.bind(this);
        this.getFieldsFiltered = this.getFieldsFiltered.bind(this);
        this.updateFormData = this.updateFormData.bind(this);
        this.submitData = this.submitData.bind(this);
        this.languageChange = this.languageChange.bind(this);
        this.updateDependencies = this.updateDependencies.bind(this);
        this.handleSubmitComplete = this.handleSubmitComplete.bind(this);
        this.handleSubmitError = this.handleSubmitError.bind(this);
        this.getStructuredFormFields = this.getStructuredFormFields.bind(this);
        this.changeTab = this.changeTab.bind(this);
        this.deleteData = this.deleteData.bind(this);
    }

    render() {

        const tabs = this.getTabs();

        let formClasses = classNames({
            'form-structure': true,
            'has-tabs': tabs !== ''
        });

        this.buttons = this.getButtons();

        return <div ref="formHolder" id={this.state.id} className="hc-form">
            {tabs}
            <div className={formClasses}>
                {this.getStructuredFormFields()}
            </div>
            <div className="footer">
                {this.buttons}
            </div>
        </div>;
    }

    getTabs() {
        if (!this.state.tabs)
            return '';

        if (!this.state.currentTab) {
            this.state.currentTab = this.state.tabs[0];
        }

        let tabs = [];

        this.state.tabs.map((value, i) => {

            let classes = classNames(
                {
                    'nav-link': true,
                    'active': this.state.currentTab === value,
                    'available': this.state.currentTab !== value
                }
            );

            tabs.push(
                <li key={i} className="nav-item form-tab">
                    <div className={classes} data-tab={value} onClick={this.changeTab}>{value}</div>
                </li>
            )
        });

        if (tabs.length <= 1) {
            tabs = [];
        }

        return <ul className="nav nav-pills form-tabs">
            {tabs}
        </ul>
    }

    changeTab(e) {
        this.setState({currentTab: e.currentTarget.dataset.tab})
    }

    getStructuredFormFields() {

        if (isNaN(this.state.formData.columns)) {
            return this.getFields();
        }
        else {

            let gridElements = [];

            let grid = [];
            let field;
            let column;
            let row = 0;
            let singleRow;

            Object.keys(this.state.formData.structure).map((key, i) => {

                field = this.state.formData.structure[key];

                if (field.column === undefined) {
                    row++;
                    column = 0;
                    singleRow = true;
                }
                else {
                    column = field.column;
                }

                if (!grid[row]) {
                    grid[row] = [];
                }

                field = this.getField(field, key, i);

                if (singleRow) {
                    grid[row] = field;
                }
                else {
                    if (!grid[row][column]) {
                        grid[row][column] = [];
                    }

                    grid[row][column].push(field);
                }

                this.finalFieldStructure[key] = field;

                if (singleRow) {
                    row++;
                    singleRow = false;
                }
            });

            grid.map((value, i) => {

                if (HC.helpers.isObject(value)) {
                    gridElements.push(<div key={i} className="row">{value}</div>)
                }
                else {

                    let columns = [];

                    value.map((value, index) => {
                        columns.push(<div key={index} className="col-sm">
                            {value}
                        </div>);
                    });

                    gridElements.push(<div key={i} className="row">
                        {columns}
                    </div>);
                }

            });

            return gridElements;
        }
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

            let formDisabledByRecord = this.isFormDisabled();

            if (formDisabledByRecord) {
                this.setState({formDisabled: true});
            }

            Object.keys(this.finalFieldStructure).map((key, i) => {

                this.refs[key].formDisabledByRecord = formDisabledByRecord;

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

    isFormDisabled() {
        let disabled = false;

        if (this.state.formData.disableFormBy) {
            Object.keys(this.state.formData.disableFormBy).map((value, i) => {

                if (this.existingRecord[value] === this.state.formData.disableFormBy[value]) {
                    disabled = true;
                }
            });
        }

        return disabled;
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

            Object.keys(response.structure).map((key, i) => {

                if (response.structure[key].tab) {
                    if (!stateObject.tabs) {
                        stateObject.tabs = [];
                    }

                    if (stateObject.tabs.indexOf(response.structure[key].tab) === -1) {
                        stateObject.tabs.push(response.structure[key].tab);
                    }
                }
            });

            if (response.availableLanguages && response.availableLanguages.length > 0) {
                stateObject.language = response.availableLanguages[0];
            }

            if (scope.props.config.recordId) {
                HC.react.loader.get(response.storageUrl + '/' + scope.props.config.recordId, null, function (recordData) {
                    scope.existingRecord = recordData;

                    scope.setState(stateObject);
                    scope.fillForm();

                    if (scope.props.formDataLoaded) {
                        scope.props.formDataLoaded(response.editLabelKey ? HC.helpers.pathIndex(recordData, response.editLabelKey) : "Edit record");
                    }
                });
            }
            else {
                scope.setState(stateObject);
                scope.updateDependencies();
                if (scope.props.formDataLoaded) {
                    scope.props.formDataLoaded(response.newLabel ? response.newLabel : "New record");
                }
            }
        });
    }

    getFieldsFiltered(options) {
        let structure = this.state.formData.structure;
        let includeList = {};
        let fields = [];

        Object.keys(structure).map((key, i) => {
            Object.keys(options).map((foKey) => {

                includeList[foKey] = structure[key][foKey] === options[foKey];
            });

            let include = true;

            Object.keys(includeList).map((includeKey) => {

                if (includeList[includeKey] === false) {
                    include = false;
                }
            });

            if (include) {
                fields[key] = this.getField(structure[key], key, i)
            }
        });

        return fields;
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

        Object.keys(structure).map((key, i) => {

            this.finalFieldStructure[key] = this.getField(structure[key], key, i)
        });

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
                             tab={this.state.currentTab}
                             language={this.state.language}
                             onLanguageChange={this.languageChange}
                             fullFormData={this.record}
                             fieldList={this.refs}
                             submitData={this.submitData}
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

        if (!this.listenTo) {
            this.listenTo = {};

            Object.keys(this.state.formData.structure).map((item) => {
                if (this.state.formData.structure[item].listenTo) {
                    this.listenTo[item] = this.state.formData.structure[item].listenTo;
                }
            });
        }
        else {
            Object.keys(this.listenTo).map((key) => {

                if (this.listenTo[key].indexOf(fieldChanged) >= 0) {
                    this.refs[key].listenedChange(fieldChanged);
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

                            if (!config.value) {
                                hideList[targetKey] = false;
                            }
                            else {
                                //TODO check if record item is Array and compare with multiple
                                //TODO move all validation of object->id/value to single function
                                if (HC.helpers.isObject(scope.record[targetKey])) {
                                    if (config.value.indexOf(scope.record[targetKey].id) >= 0) {
                                        hideList[targetKey] = false;
                                    }
                                }
                                else {
                                    if (config.value.indexOf(scope.record[targetKey]) >= 0) {
                                        hideList[targetKey] = false;
                                    }
                                }
                            }

                        } else if (HC.helpers.isArray(config)) {

                            if (config.length > 0) {

                                if (HC.helpers.isObject(scope.record[targetKey])) {

                                    if (scope.record[targetKey].id) {
                                        if (config.indexOf(scope.record[targetKey].id) >= 0) {
                                            hideList[targetKey] = false;
                                        }
                                    } else if (scope.record[targetKey].value) {
                                        if (config.indexOf(scope.record[targetKey].value) >= 0) {
                                            hideList[targetKey] = false;
                                        }
                                    }
                                }
                                else if (config.indexOf(scope.record[targetKey]) >= 0) {
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
            case 'submit' :

                return <button disabled={this.state.formDisabled}
                               key={i}
                               className={HC.helpers.buttonClass('primary')}
                               onClick={this.submitData}>{data.label}</button>;

            case 'delete' :

                return <button disabled={this.state.formDisabled}
                               key={i}
                               className={HC.helpers.buttonClass('danger')}
                               onClick={this.deleteData}>{data.label}</button>;
        }
    }

    /**
     * Deleting existing record
     */
    deleteData() {

        HC.react.loader.delete(this.state.formData.storageUrl + '/' + this.props.config.recordId, null, null, true);
    }

    /**
     * Submitting data
     */
    submitData(callbackSuccess, callbackFailure) {
        let valid = true;

        Object.keys(this.finalFieldStructure).map((key, i) => {

            if (!this.refs[key].validate(true)) {
                valid = false;
            }
        });

        if (!valid)
            return;

        let finalRecordStructure = this.finalizeStructure();

        this.setState({formDisabled: true});

        if (!callbackSuccess || !HC.helpers.isFunction(callbackSuccess)) {
            callbackSuccess = this.handleSubmitComplete;
        }

        if (!callbackFailure || !HC.helpers.isFunction(callbackSuccess)) {
            callbackFailure = this.handleSubmitError;
        }

        if (this.props.config.recordId)
            HC.react.loader.put(this.state.formData.storageUrl + '/' + this.props.config.recordId, finalRecordStructure, callbackSuccess, false, callbackFailure);
        else
            HC.react.loader.post(this.state.formData.storageUrl, finalRecordStructure, callbackSuccess, false, callbackFailure);
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
            if (r.redirectUrl) {
                document.location.href = r.redirectUrl;
                return;
            }

            if (this.props.config.createdCallback) {
                this.props.config.createdCallback.call(this.props.config.createdCallbackScope, r.data);
            }

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