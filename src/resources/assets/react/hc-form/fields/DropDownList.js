import React from 'react'
import Base from "./BaseField";
import FAButton from "../buttons/FAButton";

export default class DropDownList extends Base {

    constructor(props) {
        super(props);

        this.validationTimeOutMiliseconds = 0;

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
                       key={HC.helpers.uuid()}
                       disabled={this.getDisabled()}
                       onChange={this.contentChange}>

            {this.getOptionsFormatted()}
        </select>
    }

    /**
     * If input required validate first option
     */
    componentDidMount() {

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

        console.log(this.state.dependencyValues);

        HC.react.popUp({
            url: this.props.config.new,
            params: this.state.dependencyValues,
            type: "form",
            callBack: this.newOptionCreated,
            scope: this
        });

        console.log('NEW');
    }

    newOptionCreated (data)
    {
        console.log(data);
    }
}