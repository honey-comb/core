import React from 'react';
import BaseField from "./BaseField";
import {DateRangePicker} from "element-react";

export default class HCDateRangePicker extends BaseField {

    constructor(props) {
        super(props);

        this.state.value = null;
        this.state.firstDayOFWeek = this.props.config.firstDayOFWeek;
    }

    getInput() {

        if (!this.getValue()) {
            this.state.value = null;
            this.changeHappened = true;
        }

        return <DateRangePicker
            format={this.state.format}
            onChange={this.handleSelectionChange.bind(this)}
            value={this.state.value}
            isDisabled={this.getDisabled()}
            readOnly={this.props.config.readonly}
            disabledDate={
                time => this.validateDate(time)
            }
            ref="inputField"
        />;
    }

    componentDidUpdate() {
        if (this.changeHappened) {
            this.triggerChange();
            this.changeHappened = false;
        }
    }

    /**
     * Setting value
     *
     * @param value
     */
    setValue(value) {

        if (value === '') {
            this.state.value = null;
        }
        else {
            this.setState({
                value: [new Date(value.from), new Date(value.to)]
            });
        }

        this.validate();
    }

    externalFocus() {
        this.refs.inputField.refs.inputRoot.focus();
    }

    /**
     * Validating Date availability
     *
     * @param time
     * @returns {boolean}
     */
    validateDate(time) {

        const config = this.props.config.disabledDays ? this.props.config.disabledDays : this.getOptions().disabled;
        let disable = false;

        time.addHours(-time.getTimezoneOffset() / 60);

        if (!config)
            return disable;

        if (config.min && config.max) {
            disable = !((time.getTime() > new Date(config.min).getTime()) && (time.getTime() < new Date(config.max).getTime()));
        }
        else {
            if (config.min) {
                disable = (time.getTime() < new Date(config.min));
            }

            if (config.max) {
                disable = (time.getTime() > new Date(config.max));
            }
        }

        if (disable)
            return disable;

        if (config.weekdays) {
            if (config.weekdays.indexOf(time.getDay()) >= 0)
                return true;
        }

        if (config.days) {
            if (config.days.indexOf(time.getFullYear() + '-' + time.getFullMonth() + '-' + time.getFullDay()) >= 0)
                return true;
        }

        return disable;
    }

    /**
     * Handling selection change
     * @param date
     */
    handleSelectionChange(date) {

        this.state.value = date;
        this.setState(this.state);
        this.validate(true);

        this.triggerChange();
    }

    /**
     * Getting value
     */
    getValue() {

        return this.state.value;
    }
}

HC.formFields.register('dateRangePicker', HCDateRangePicker);