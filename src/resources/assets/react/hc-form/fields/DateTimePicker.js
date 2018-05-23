import React from 'react';
import BaseField from "./BaseField";
import {DatePicker} from "element-react";
import {i18n} from 'element-react'
import locale from './locale/lt'

export default class DateTimePicker extends BaseField {

    constructor(props) {
        super(props);

        this.state.value = null;
        this.state.showTime = this.props.config.showTime;
        this.state.firstDayOFWeek = this.props.config.firstDayOFWeek;

        if (this.state.showTime) {
            this.state.format = "yyyy-MM-dd HH:mm:ss"
        }
        else {
            this.state.format = "yyyy-MM-dd"
        }
    }

    getInput() {
        return (
            <DatePicker
                firstDayOFWeek={this.state.firstDayOFWeek}
                isShowTime={this.state.showTime}
                format={this.state.format}
                onChange={this.handleSelectionChange.bind(this)}
                value={this.state.value}
                isDisabled={this.getDisabled()}
                readOnly={this.props.config.readonly}
                disabledDate={
                    time => this.validateDate(time)
                }
                ref="inputField"
            />
        );
    }

    /**
     * Setting value
     *
     * @param value
     */
    setValue(value) {

        this.state.value = new Date(value);

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
        const config = this.props.config.disabled ? this.props.config.disabled : this.getOptions().disabled;
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
    }

    /**
     * Handling selection change
     * @param date
     */
    handleSelectionChange(date) {
        this.state.value = date;
        this.setState(this.state);
        this.validate();
    }

    /**
     * Getting value
     */
    getValue() {

        if (!this.state.value)
            return null;

        let date = this.state.value.getFullYear() + '-' + this.state.value.getFullMonth() + '-' + this.state.value.getFullDay();

        if (this.state.showTime) {
            date += ' ' + this.state.value.getFullHours() + ':' + this.state.value.getFullMinutes() + ':' + this.state.value.getFullSeconds();
        }

        return date;
    }
}
//TODO move this one to globals somewhere
i18n.use(locale);

HC.formFields.register('dateTimePicker', DateTimePicker);

