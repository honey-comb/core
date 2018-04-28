import React from 'react';
import Picker from "./time/Picker";
import DatePicker from 'react-datepicker';

export default class TimePicker extends Picker {

    getInput() {
        return <DatePicker
            selected={this.state.startDate}
            ref="inputField"
            onChange={this.handleSelectionChange}
            timeFormat={this.props.config.timeFormat}
            timeIntervals={this.props.config.timeIntervals}
            locale={this.props.config.locale}
            showTimeSelect={true}
            showTimeSelectOnly={true}
            dateFormat={this.props.config.dateFormat ? this.props.config.dateFormat : 'LT'}
        />;
    }
}

HC.formFields.register('timePicker', TimePicker);