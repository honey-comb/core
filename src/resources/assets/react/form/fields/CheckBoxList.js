import React from 'react'
import BaseField from "./BaseField";

export default class CheckBoxList extends BaseField {

    constructor(props) {
        super(props);

        this.options = [];

        this.handleCheckBoxChange = this.handleCheckBoxChange.bind(this);
    }

    getInput() {
        let list = [];

        this.props.config.options.map((item, i) => list.push(this.getCheckBox(item, i)));

        return list;
    }

    getCheckBox(data, key) {

        return <div className="checkbox" key={key}>
            <label>
                <input type="checkbox" ref={data.id} value={data.id} onChange={this.handleCheckBoxChange}/> {data.label}
            </label>
        </div>
    }

    handleCheckBoxChange(e) {
        if (e.target.checked) {
            // add the numerical value of the checkbox to options array
            this.options.push(e.target.value);
        } else {
            // or remove the value from the unchecked checkbox from the array
            let index = this.options.indexOf(e.target.value);
            this.options.splice(index, 1);
        }

        this.validate();
    }

    getValue() {
        if (this.props.config.options.length === 1) {

            if (this.options[0])
                return this.options[0];

            return null;
        }
        else
            return this.options;
    }

    setValue(value) {

        this.options = [];

        if (this.props.config.options.length === 1) {
            if (this.refs[value]) {
                this.refs[value].checked = 1;
                this.options.push(value);
            }
        }
        else {

            value.map((item, key) => {
                if (this.refs[item]) {
                    this.refs[item].checked = 1;
                    this.options.push(item);
                }
            });
        }

        this.validate();
    }
}

HC.formFields.register('checkBoxList', CheckBoxList);