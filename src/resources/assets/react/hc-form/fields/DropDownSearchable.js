import React from 'react';
import Select from 'react-select';
import BaseField from "./BaseField";
import * as axios from "axios";

export default class DropDownSearchable extends BaseField {
    constructor(props) {
        super(props);

        this.state.multi = this.props.config.multi;
        this.state.options = this.props.config.options;
        this.state.multiValue = [];
        this.state.value = undefined;
        this.state.createble = false;
        this.state.backspaceRemoves = true;

        this.search = this.search.bind(this);
        this.setValue = this.setValue.bind(this);
    }

    setValue(value) {

        if (value && !value.label) {
            value.label = value[this.props.config.originalLabel];
        }

        this.setState({
            value: value,
        });
    }

    componentDidUpdate ()
    {
        this.triggerChange();
    }

    search(input, callback) {
        if (!input) {
            return Promise.resolve({options: this.props.config.options});
        }

        let params = {
            q: input
        };

        axios.get(this.props.config.searchUrl, {params: params}).then(res => {
            callback(null, {options: res.data})
        });
    }

    getInput() {
        const AsyncComponent = this.state.creatable
            ? Select.AsyncCreatable
            : Select.Async;

        return (
            <div className="section">
                <AsyncComponent multi={this.state.multi} value={this.state.value} onChange={this.setValue} valueKey="id"
                                labelKey="label" loadOptions={this.search}
                                disabled={this.getDisabled()}
                                backspaceRemoves={this.state.backspaceRemoves}/>
            </div>
        );
    }

    getValue() {

        return this.state.value;
    }
}
