import React from 'react'
import Base from "./BaseField";
import FAButton from "../buttons/FAButton";

export default class DropDownList extends Base {

    constructor(props) {
        super(props);

        this.validationTimeOutMiliseconds = 0;
        this.key = HC.helpers.uuid();

        this.getNewButton = this.getNewButton.bind(this);
        this.newOptionAction = this.newOptionAction.bind(this);
    }

    getInput() {

        return [this.getSelect(), this.getNewButton()]
    }

    getSelect() {
        let classNames = this.getClassNames({
            "form-control": true,
            "new-option": !!this.props.config.new
        });

        return <select className={classNames}
                       ref="inputField"
                       value={this.state.value}
                       key={this.key}
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
            return undefined;

        return this.refs.inputField.value;
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