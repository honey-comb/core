import React from 'react'
import Base from "./Base";

export default class Email extends Base {
    isValid ()
    {
        return HC.helpers.validateEmail(this.getValue());
    }
}