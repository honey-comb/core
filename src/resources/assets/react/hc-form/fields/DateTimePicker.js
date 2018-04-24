import React from 'react';
import BaseField from "./BaseField";
import DatePicker from 'react-datepicker';
import moment from 'moment';

import 'react-datepicker/dist/react-datepicker.css';

// CSS Modules, react-datepicker-cssmodules.css
// import 'react-datepicker/dist/react-datepicker-cssmodules.css';

export default class DateTimePicker extends BaseField {
    constructor (props) {
        super(props);

        this.state = {
            startDate: moment(this.props.value)
        };

        this.value = this.state.startDate;

        this.handleSelectionChange = this.handleSelectionChange.bind(this);
    }

    handleSelectionChange(date) {
        this.setState({
            startDate: date
        });

        this.value = date.format(this.props.config.dateFormat);

        this.validate();
    }

    getInput() {
        return <DatePicker
            selected={this.state.startDate}
            ref="inputField"
            onChange={this.handleSelectionChange}
            dateFormat={this.props.config.dateFormat}
            locale={this.props.config.locale}
        />;
    }

    getValue ()
    {
        return this.value;
    }

    setValue (value)
    {
        this.setState({
            startDate: moment(value)
        });
    }

    componentDidMount ()
    {
        this.validate();
    }
}