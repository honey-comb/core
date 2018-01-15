import React from 'react'
import Base from "./Base";

export default class Password extends Base {
    isValid ()
    {
        return (this.getValue().length >= 8);
    }
}