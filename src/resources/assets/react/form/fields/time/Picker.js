import React from 'react';
import moment from 'moment';

import BaseField from "../BaseField";

export default class Picker extends BaseField {
    constructor(props) {
        super(props);

        this.state = {
            startDate: this.props.value ? moment(this.props.value) : null
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

    getDateFormat() {
        if (this.props.config.timeFormat)
            return this.props.config.dateFormat + ' ' + this.props.config.timeFormat;

        return this.props.config.dateFormat;
    }

    getValue() {
        if (this.value)
            return this.value.format(this.getDateFormat());

        return null;
    }

    setValue(value) {
        this.value = moment(value);

        this.setState({
            startDate: this.value,
        });

        this.validate();
    }

    componentDidMount() {
        this.validate();
    }
}