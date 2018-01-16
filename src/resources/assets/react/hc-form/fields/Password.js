import React from 'react'
import Base from "./BaseField";

export default class Password extends Base {
    isValid ()
    {
        return (this.getValue().length >= 8);
    }
}