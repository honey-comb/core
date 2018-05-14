import React from 'react'
import BaseField from "./BaseField";
import FAButton from "../buttons/FAButton";
import Select from 'react-select';

export default class DropDownList extends BaseField {

    constructor(props) {
        super(props);

        this.validationTimeOutMiliseconds = 0;
        this.key = HC.helpers.uuid();
        this.state = {
            value: this.props.config.value
        };

        this.getNewButton = this.getNewButton.bind(this);
        this.newOptionAction = this.newOptionAction.bind(this);
        this.inputUpdated = this.inputUpdated.bind(this);
    }

    getInput() {

        return [this.getSelect(), this.getNewButton()]
    }

    /**
     *Set value for input
     *
     * @param value
     */
    inputUpdated(value) {

        this.state.value = value;
        this.setState(this.state);

        this.validate();
    }

    getSelect() {
        let classNames = this.getClassNames({
            "form-control": true,
            "new-option": !!this.props.config.new
        });

        return <Select classNamePrefix={classNames}
                       key={this.key}
                       defaultValue={this.state.value}
                       options={this.formatOptions(this.getOptions())}
                       rtl={this.state.rtl}
                       ref="inputField"
                       disabled={this.getDisabled()}
                       onChange={this.inputUpdated}
        >
        </Select>
    }

    /**
     * Format options for needed format
     *
     * @param options
     * @returns {Array}
     */
    formatOptions (options)
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
     * Getting value
     *
     * @returns {undefined}
     */
    getValue() {

        if (this.state.value === "undefined")
        {
            if (this.props.config.value && this.getDisabled())
                return this.props.config.value;

            return undefined;
        }

        return this.state.value;
    }

    /**
     * Setting value
     *
     * @param value
     */
    setValue (value)
    {
        this.setState({value:value});
        this.validate();
        this.triggerChange();
    }

    /**
     * Getting new button
     */
    getNewButton() {

        if (!!this.props.config.new)
        {
            return <FAButton key={HC.helpers.uuid()}
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

    newOptionAction() {

        let params = this.state.dependencyValues;
        params.hc_options = 1;

        HC.react.popUp({
            url: this.props.config.new,
            params: params,
            type: 'form',
            createdCallback: this.newOptionCreated,
            createdCallbackScope: this
        });
    }

    newOptionCreated (data)
    {
        this.addNewOption(data);
        this.render();
        this.setValue(data.id);
        this.validate();
    }
}

HC.formFields.register('dropDownList', DropDownList);