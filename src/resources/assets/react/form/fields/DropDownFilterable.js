import React from 'react';
import Select from 'react-select';
import BaseField from "./BaseField";
import FAButton from "../buttons/FAButton";

export default class DropDownFilterable extends BaseField {
    constructor(props) {
        super(props);

        this.state.multi = this.props.config.multi;
        this.state.options = this.props.config.options;
        this.state.multiValue = [];
        this.state.value = null;

        this.inputUpdated = this.inputUpdated.bind(this);
        this.newOptionAction = this.newOptionAction.bind(this);
    }

    /**
     *Set value for input
     *
     * @param value
     */
    inputUpdated(value) {
        this.state.value = value;
        this.setState(this.state);

        this.validate();
    }

    /**
     * Get input value
     *
     * @returns {*}
     */
    getInput() {

        return [this.getSelect(), this.getNewButton(), <div key={2} className="clearfix"/>];
    }

    /**
     *
     * @returns {*}
     */
    getSelect() {
        let classNames = this.getClassNames({
            "new-option": !!this.props.config.new
        });

        return <Select
            key={0}
            className={classNames}
            options={this.formatOptions(this.getOptions())}
            rtl={this.state.rtl}
            value={this.state.value}
            ref="inputField"
            multi={this.state.multi}
            disabled={this.getDisabled()}
            creatable={this.props.config.creatable}
            onChange={this.inputUpdated}
            removeSelected
            simpleValue
        />
    }

    /**
     * Format options for needed format
     *
     * @param options
     * @returns {Array}
     */
    formatOptions(options) {
        let newOptions = [];

        options.map((option, i) => {
            newOptions.push({
                id: option.id,
                value: option.id,
                label: option.label,
            });
        });

        return newOptions;
    }

    /**
     * If input required validate first option
     */
    componentDidMount() {

        super.componentDidMount();

        if (this.props.config.required)
            this.validate();
    }

    /**
     * Get input value
     *
     */
    getValue() {
        return this.state.value;
    }

    /**
     *
     */
    setValue(value) {

        if (HC.helpers.isArray(value)) {
            if (!this.state.options) {
                this.state.options = value;
            }

            this.state.value = '';

            value.map((value, i) => {
                this.state.value += value.id + ',';
            });

            this.state.value = this.state.value.substring(0, this.state.value.length - 1);

        }
        else if (HC.helpers.isObject(value)) {

            if (!this.state.options) {
                this.state.options = [value];
            }

            this.state.value = value;
        }
        else {

            this.state.value = value;
        }

        this.setState(this.state);
        this.validate();
    }

    /**
     * Getting new button
     */
    getNewButton() {

        if (!!this.props.config.new) {
            return <FAButton key={1}
                             icon={HC.helpers.faIcon('plus')}
                             type={HC.helpers.buttonClass('info')}
                             onPress={this.newOptionAction}
                             classes={"new-option-button"}

            />
        }
        else {
            return '';
        }
    }

    /**
     * Adding new Action
     */
    newOptionAction() {

        let params = this.state.dependencyValues ? this.state.dependencyValues : {};
        params.hc_new = 1;

        if (this.props.config.new.require) {
            this.props.config.new.require.map((value) => {
                params[value] = HC.helpers.pathIndex(this.props.fullFormData, value);
            });
        }

        HC.react.popUp({
            url: this.props.config.new.url,
            params: {params: params},
            type: 'form',
            createdCallback: this.newOptionCreated,
            createdCallbackScope: this
        });
    }

    /**
     * new option created
     *
     * @param data
     */
    newOptionCreated(data) {

        this.addNewOption(data);

        if (!this.state.value) {
            this.state.value = data.id;
        }
        else {
            this.state.value += ',' + data.id;
        }

        this.setState(this.state);
        this.triggerChange();
    }
}

HC.formFields.register('dropDownFilterable', DropDownFilterable);
