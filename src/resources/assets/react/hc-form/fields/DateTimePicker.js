import React from 'react';
import DatePicker from 'react-datepicker';
import Picker from "./time/Picker";

export default class DateTimePicker extends Picker {

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
}

HC.formFields.register('dateTimePicker', DateTimePicker);