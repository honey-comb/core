import React from 'react';
import Select from 'react-select';
import BaseField from "./BaseField";

export default class DropDownFilterable extends BaseField {
    constructor(props) {
        super(props);

        this.state.isMulti = this.props.config.multi;
        this.state.options = this.props.config.options;
        this.state.multiValue = [];
        this.state.value = null;

        this.inputUpdated = this.inputUpdated.bind(this);
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
        return <Select
            options={this.formatOptions(this.getOptions())}
            rtl={this.state.rtl}
            value={this.state.value}
            ref="inputField"
            isMulti={this.state.isMulti}
            disabled={this.getDisabled()}
            creatable={this.props.config.creatable}
            onChange={this.inputUpdated}
        />
    }

    /**
     * Format options for needed format
     *
     * @param options
     * @returns {Array}
     */
    formatOptions (options)
    {
        let newOptions = [];

        options.map((option, i) =>
        {
            newOptions.push({
                value:option.id,
                label:option.label,
            });
        });

        return newOptions;
    }

    /**
     * Get input value
     *
     */
    getValue() {

        console.log(this.state.value);

        return this.state.value;
    }
}

HC.formFields.register('dropDownFilterable', DropDownFilterable);
