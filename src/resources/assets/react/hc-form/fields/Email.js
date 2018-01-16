import React from 'react'
import Base from "./BaseField";

export default class Email extends Base {
    isValid ()
    {
        return HC.helpers.validateEmail(this.getValue());
    }
}