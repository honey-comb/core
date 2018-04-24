import React from 'react';
import BaseField from "./BaseField";
import DatePicker from 'react-datepicker';
import moment from 'moment';

import 'react-datepicker/dist/react-datepicker.css';

// CSS Modules, react-datepicker-cssmodules.css
// import 'react-datepicker/dist/react-datepicker-cssmodules.css';

export default class DateTimePicker extends BaseField {
    constructor(props) {
        super(props);

        this.state = {
            startDate: moment(this.props.value)
        };

        this.value = this.state.startDate;

        this.handleSelectionChange = this.handleSelectionChange.bind(this);
        this.getDateFormat = this.getDateFormat.bind(this);
    }

    handleSelectionChange(date) {
        this.setState({
            startDate: date
        });

        this.value = date;

        this.validate();
    }

    getInput() {
        return <DatePicker
            selected={this.state.startDate}
            ref="inputField"
            showYearDropdown
            onChange={this.handleSelectionChange}
            dateFormat={this.getDateFormat()}
            showTimeSelect={this.props.config.timeFormat ? true : false}
            timeFormat={this.props.config.timeFormat}
            locale={this.props.config.locale}
            timeIntervals={this.props.config.timeIntervals ? this.props.config.timeIntervals : 60}
        />;
    }

    getDateFormat() {
        if (this.props.config.timeFormat)
            return this.props.config.dateFormat + ' ' + this.props.config.timeFormat;

        return this.props.config.dateFormat;
    }

    getValue() {
        return this.value.format(this.getDateFormat());
    }

    setValue(value) {
        this.value = moment(value);

        this.setState({
            startDate: this.value,
        });
    }

    componentDidMount() {
        this.validate();
    }
}