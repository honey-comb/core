import React from 'react'
import Base from "./BaseField";

export default class Password extends Base {
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