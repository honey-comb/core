import React from 'react';
import BaseField from "./BaseField";
import {TimePicker} from "element-react";

export default class HCTimePicker extends BaseField {

    constructor (props)
    {
        super(props);
    }
    /**
     * Getting element-select timePicker
     * @returns {*}
     */
    getInput() {

        return (
            <TimePicker
                onChange={this.handleSelectionChange.bind(this)}
                value={this.state.value}
                ref="inputField"
            />
        )
    }

    /**
     * Updating change value
     * @param value
     */
    handleSelectionChange(value) {

        this.state.value = value;

        this.setState(this.state);
        this.validate();
    }

    /**
     * Setting value, expecting (HH:mm:ss)
     * @param value
     */
    setValue(value) {
        if (HC.helpers.isString(value)) {
            value = value.split(':');

            if (value.length !== 3) {
                value = null;
            }
            else {
                let today = new Date();
                value = new Date(today.getFullYear(), today.getMonth(), today.getDate(), value[0], value[1], value[2]);
            }
        }

        this.state.value = value;
        this.setState(this.state);
        this.validate();
    }

    /**
     * Retrieving hours (HH:mm:ss)
     * @returns {string|null}
     */
    getValue() {
        if (!this.state.value)
            return null;

        return this.state.value.getFullHours() + ':' + this.state.value.getFullMinutes() + ':' + this.state.value.getFullSeconds();
    }

}

HC.formFields.register('timePicker', HCTimePicker);
