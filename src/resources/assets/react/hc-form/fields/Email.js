import React from 'react'
import BaseField from "./BaseField";

export default class Email extends BaseField {
    isValid ()
    {
        return HC.helpers.validateEmail(this.getValue());
    }
}

HC.formFields.register('email', Email);