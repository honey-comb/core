import React from 'react'
import BaseField from "./BaseField";

export default class HCNumber extends BaseField {
    isValid() {
        if (!this.props.config.required) {
            if (this.getValue() === "")
                return true;
        }

        if (this.props.config.int) {
            return Number.isInteger(Number(this.getValue()));
        }

        return false;
    }
}

HC.formFields.register('number', HCNumber);