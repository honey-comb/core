import React from 'react';
import Select from 'react-select';
import BaseField from "./BaseField";

export default class DropDownFilterable extends BaseField {
    constructor(props) {
        super(props);

        this.state.multi = this.props.config.multi;
        this.state.options = this.props.config.options;
        this.state.multiValue = [];
        this.state.value = this.props.config.value;

        this.search = this.search.bind(this);
        this.setValue = this.setValue.bind(this);
    }

    /**
     *Set value for input
     *
     * @param value
     */
    setValue(value) {

        this.setState({
            value: value,
        });

        this.triggerChange();
    }

    /**
     * Get input value
     *
     * @returns {*}
     */
    getInput() {
        return <Select
            multi={this.props.config.multi}
            onChange={this.setValue}
            options={this.formatOptions(this.getOptions())}
            removeSelected={this.state.removeSelected}
            rtl={this.state.rtl}
            value={this.state.value}
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

        return this.state.value;
    }
}

HC.formFields.register('dropDownFilterable', DropDownFilterable);
