import React from 'react'
import BaseField from "./BaseField";

export default class Password extends BaseField {
    isValid ()
    {
        if (!this.props.config.required)
        {
            if (this.getValue() === "")
                return true;
        }

        return (this.getValue().length >= 8);
    }
}

HC.formFields.register('password', Password);