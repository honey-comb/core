import React from 'react'
import Base from "./BaseField";
import Select from 'react-select';

export default class TagList extends Base {
    constructor(props) {

        super(props);

        this.state.multi = this.props.config.multi;
        this.state.options = this.props.config.options;
        this.state.multiValue = [];
        this.state.value = undefined;

        this.handleOnChange = this.handleOnChange.bind(this);
    }

    getInput() {

        return <Select.Creatable
            multi={this.state.multi}
            options={this.state.options}
            onChange={this.handleOnChange}
            value={this.state.multi ? this.state.multiValue : this.state.value}
        />
    }

    handleOnChange(value) {

        this.state.multi ? this.state.multiValue = value : this.state.value = value;
        this.setState(this.state);
        this.triggerChange();
    }

    setValue(value) {
        this.handleOnChange(value);
    }

    getValue() {
        return this.state.multi ? this.state.multiValue : this.state.value;
    }

    isValid() {
        if (this.getRequired()) {

            return this.state.multi ? this.state.multiValue.length !== 0 : this.state.value !== undefined;
        }

        return true;
    }
}