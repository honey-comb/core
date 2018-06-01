import React from 'react'
import BaseField from "./BaseField";
import FAButton from "../buttons/FAButton";

export default class DropDownList extends BaseField {

    constructor(props) {
        super(props);

        this.key = HC.helpers.uuid();
        this.state = {
            value : this.props.config.value
        };

        this.getNewButton = this.getNewButton.bind(this);
        this.newOptionAction = this.newOptionAction.bind(this);
    }

    getInput() {

        return [this.getSelect(), this.getNewButton(), <div key={2} className="clearfix"/>];
    }

    getSelect() {
        let classNames = this.getClassNames({
            "form-control": true,
            "new-option": !!this.props.config.new
        });

        return <select className={classNames}
                       ref="inputField"
                       key={this.key}
                       value={this.state.value}
                       disabled={this.getDisabled()}
                       onChange={this.contentChange}>


            {this.getOptionsFormatted()}
        </select>
    }

    contentChange (e)
    {
        this.setValue(e.target.value);
    }

    /**
     * If input required validate first option
     */
    componentDidMount() {

        super.componentDidMount();

        if (this.props.config.required)
            this.validate();
    }

    /**
     * Creating select options
     *
     * @returns {Array}
     */
    getOptionsFormatted() {
        let list = [];
        let options = this.getOptions();

        if (!this.props.config.required) {
            list.push(<option key={-1} value="undefined">Please select:</option>)
        }

        if (options)
            options.map((item, i) => list.push(<option key={i} value={item.id}>{item.label}</option>));

        return list;
    }

    /**
     * Validating input
     *
     * @returns {boolean}
     */
    isValid() {

        if (this.props.config.required)
            if (!this.refs.inputField.value)
                return false;

        return true;
    }

    /**
     * Getting value
     *
     * @returns {undefined}
     */
    getValue() {

        if (this.refs.inputField.value === "undefined")
        {
            if (this.props.config.value && this.getDisabled())
                return this.props.config.value;

            return undefined;
        }

        return this.refs.inputField.value;
    }

    /**
     * Setting value
     *
     * @param value
     */
    setValue (value)
    {
        if (HC.helpers.isArray(value)) {
            if (!this.state.options) {
                this.state.options = value;
            }

            this.state.value = '';

            value.map((value, i) => {
                this.state.value += value.id + ',';
            });

            this.state.value = this.state.value.substring(0, this.state.value.length - 1);

        }
        else {

            this.state.value = value;
        }

        this.setState(this.state);
        this.validate();
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
        this.triggerChange();
    }
}

HC.formFields.register('dropDownList', DropDownList);