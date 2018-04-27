import React from 'react'
import Base from "./BaseField";

export default class TextArea extends Base {

    getInput ()
    {
        let inputClasses = this.getClassNames({
            "form-control": true,
            "multi-language": this.props.config.multiLanguage
        });

        return <textarea
                      ref="inputField"
                      placeholder={this.props.config.label}
                      className={inputClasses}
                      readOnly={this.props.config.readonly}
                      disabled={this.props.config.disabled}
                      rows={5}
                      onChange={this.contentChange}/>;
    }
}

HC.formFields.register('textArea', TextArea);