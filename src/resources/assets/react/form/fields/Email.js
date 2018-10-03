import React from 'react'
import BaseField from "./BaseField";

export default class Email extends BaseField {
    isValid() {
        let email = this.getValue();

        if (email.length === 0 && !this.props.config.required) {
            return true;
        }

        return HC.helpers.validateEmail(this.getValue());
    }
}

HC.formFields.register('email', Email);