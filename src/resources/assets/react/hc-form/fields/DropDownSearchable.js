import React from 'react';
import {AsyncCreatable, Async} from 'react-select'
import BaseField from "./BaseField";
import * as axios from "axios";

export default class DropDownSearchable extends BaseField {
    constructor(props) {
        super(props);

        this.state.multi = this.props.config.multi;
        this.state.options = this.props.config.options;
        this.state.multiValue = [];
        this.state.value = undefined;
        this.state.creatable = this.props.config.creatable;
        this.state.backspaceRemoves = true;

        this.dataLoadingSource = undefined;

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
        if (!input || !this.props.config.searchUrl) {
            return Promise.resolve({options: this.props.config.options});
        }

        let params = {
            params: {q: input}
        };

        if (this.dataLoadingSource) {
            this.dataLoadingSource.cancel();
        }

        let CancelToken = axios.CancelToken;
        this.dataLoadingSource = CancelToken.source();

        params.cancelToken = this.dataLoadingSource.token;

        HC.react.loader.get(this.props.config.searchUrl, params, function (data)
        {
            callback(null, {options: data});

            /**
             * Format options for needed format
             *
             * @param options
             * @returns {Array}
             */
            function formatOptions (options)
            {
                let newOptions = [];

                options.map((option, i) =>
                {
                    newOptions.push({
                        value:option.id,
                        label:option.label,
                    });
                });

                return newOptions;
            }
        });
    }

    getInput() {
        const AsyncComponent = this.state.creatable
            ? AsyncCreatable
            : Async;

        return (
            <div className="section">
                <AsyncComponent multi={this.state.multi}
                                value={this.state.value}
                                onChange={this.setValue}
                                valueKey="id"
                                labelKey="label"
                                loadOptions={this.search}
                                disabled={this.getDisabled()}
                                backspaceRemoves={this.state.backspaceRemoves}/>
            </div>
        );
    }

    getValue() {

        return this.state.value;
    }

        console.log(this.state.options);
        console.log(this.state.options)
}

HC.formFields.register('dropDownSearchable', DropDownSearchable);
