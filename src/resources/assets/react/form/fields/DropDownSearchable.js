import React from 'react';
import {AsyncCreatable, Async} from 'react-select'
import BaseField from "./BaseField";
import * as axios from "axios";
import FAButton from "../buttons/FAButton";

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
        this.getNewButton = this.getNewButton.bind(this);
        this.newOptionAction = this.newOptionAction.bind(this);
    }

    setValue(value) {

        if (HC.helpers.isArray(value) && value.length === 0) {
            value = null;
        }
        if (value && !HC.helpers.isArray(value) && !value.label) {
            value.label = value[this.props.config.originalLabel];
        }

        this.setState({
            value: value,
        });
    }

    componentDidUpdate() {
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

        HC.react.loader.get(this.props.config.searchUrl, params, function (data) {
            callback(null, {options: data});
        });
    }

    getInput() {

        return [this.getSelect(), this.getNewButton(), <div key={2} className="clearfix"/>];
    }

    getSelect() {
        const AsyncComponent = this.state.creatable
            ? AsyncCreatable
            : Async;

        let classNames = this.getClassNames({
            "section": true,
            "new-option": !!this.props.config.new
        });

        return (
            <div key={0} className={classNames}>
                <AsyncComponent multi={this.state.multi}
                                value={this.state.value}
                                onChange={this.setValue}
                                valueKey="id"
                                labelKey="label"
                                loadOptions={this.search}
                                options={this.getOptions()}
                                disabled={this.getDisabled()}
                                backspaceRemoves={this.state.backspaceRemoves}/>
            </div>
        );
    }

    getValue() {

        return this.state.value;
    }

    /**
     * Getting new button
     */
    getNewButton() {

        if (!!this.props.config.new) {
            return <FAButton key={1}
                             icon={HC.helpers.faIcon('plus')}
                             type={HC.helpers.buttonClass('info')}
                             onPress={this.newOptionAction}
                             classes={"new-option-button"}

            />
        }
        else {
            return '';
        }
    }

    /**
     * Adding new Action
     */
    newOptionAction() {

        let params = this.state.dependencyValues ? this.state.dependencyValues : {};
        params.hc_new = 1;

        if (this.props.config.new.require) {
            this.props.config.new.require.map((value) => {
                params[value] = HC.helpers.pathIndex(this.props.fullFormData, value);
            });
        }

        HC.react.popUp({
            url: this.props.config.new,
            params: {params: params},
            type: 'form',
            createdCallback: this.newOptionCreated,
            createdCallbackScope: this
        });
    }

    /**
     * new option created
     *
     * @param data
     */
    newOptionCreated(data) {

        this.addNewOption(data);

        if (!this.state.value) {
            this.state.value = data.id;
        }
        else {
            this.state.value += ',' + data.id;
        }

        this.setState(this.state);
    }
}

HC.formFields.register('dropDownSearchable', DropDownSearchable);
