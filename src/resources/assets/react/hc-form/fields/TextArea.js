import React from 'react'
import Base from "./BaseField";

export default class TextArea extends Base {

    getInput ()
    {
        return <textarea
                      ref="inputField"
                      placeholder={this.props.config.label}
                      className="form-control"
                      readOnly={this.props.config.readonly}
                      disabled={this.props.config.disabled}
                      onChange={this.contentChange}/>;
    }
}