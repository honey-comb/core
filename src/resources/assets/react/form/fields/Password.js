import React from 'react'
import BaseField from "./BaseField";

export default class Password extends BaseField {
    isValid() {
        if (!this.props.config.required) {
            if (this.getValue() === "")
                return true;
        }
        return (this.getValue().length >= this.getMinLength());
    }

    getMinLength() {
        if(this.props.config.minLength) {
            return this.props.config.minLength;
        }

        return 8;
    }
}

HC.formFields.register('password', Password);
