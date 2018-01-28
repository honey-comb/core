import React, {Component} from 'react'
import TweenMax from "gsap"
import FontAwesomeIcon from '@fortawesome/react-fontawesome'
import axios from "axios/index";
import Email from "../hc-form/fields/Email";
import Password from "../hc-form/fields/Password";
import CheckBoxList from "../hc-form/fields/CheckBoxList";
import BaseField from "../hc-form/fields/BaseField";
import DropDownList from "../hc-form/fields/DropDownList";
import TextArea from "../hc-form/fields/TextArea";

export default class HCForm extends Component {

    constructor(props) {
        super(props);

        this.finalStructure = [];

        this.record = {};

        this.state = {
            id: HC.helpers.uuid(),
            formData: {},
            formDisabled: false
        };

        this.opacity = 0;

        this.getFields = this.getFields.bind(this);
        this.updateFormData = this.updateFormData.bind(this);
        this.submitData = this.submitData.bind(this);
    }

    render() {

        return <div ref="formHolder" id={this.state.id} className="hc-form" style={{opacity: this.opacity}}>
            <div className="header">
                {this.getCloseButton()}
                <div className="label">{this.props.contentID ? "Edit record" : "New record"}</div>
            </div>
            <div className="form-structure">
                {this.getFields()}
            </div>
            <div className="footer">
                {this.getButtons()}
            </div>
        </div>;
    }

    /**
     * When component has mounted, load form data
     */
    componentDidMount() {
        this.animateForm(true);
        this.loadFormData();
    }

    /**
     * When form has been completely loaded
     * Fill in the information which is available
     */
    componentDidUpdate() {
        if (Object.keys(this.record).length > 0) {
            Object.keys(this.finalStructure).map((key, i) => {

                let value = this.record[key];

                if (value)
                    this.refs[key].setValue(value);
            });
        }
    }

    /**
     * Getting close button
     * TODO:Move button to PopUp
     * @returns {*}
     */
    getCloseButton() {
        if (this.props.config.parent)
            return <div className="close" style={{float: "left"}} onClick={() => this.animateForm(false)}>
                <FontAwesomeIcon icon={HC.helpers.faIcon('times-circle')}/>
            </div>;

        return "";
    }

    /**
     * Loading form data
     */
    loadFormData() {
        let url = this.props.config.url;
        let formData;

        axios.get(url)
            .then(res => {

                formData = res.data;

                if (this.props.config.recordId) {
                    axios.get(formData.storageUrl + '/' + this.props.config.recordId).then(
                        res => {

                            this.record = res.data;

                            this.setState({
                                formData: formData,
                            });
                        }
                    )
                }
                else {
                    this.setState({
                        formData: formData,
                    });
                }
            });
    }

    /**
     * Animate form
     *
     * @param forward
     */
    animateForm(forward) {

        if (forward) {
            TweenMax.to(this, 0.5, {
                opacity: 1,
                onUpdate: () => this.refs.formHolder.style.opacity = this.opacity,
            });
        }
        else {

            if (!this.props.config.parent)
                return;

            TweenMax.to(this, 0.5, {
                opacity: 0,
                onUpdate: () => this.refs.formHolder.style.opacity = this.opacity,
                onComplete: this.props.formClosed
            });
        }
    }

    /**
     * Getting all fields
     *
     * @returns {Array}
     */
    getFields() {
        let structure = this.state.formData.structure;
        this.finalStructure = [];

        if (!structure)
            return this.finalStructure;

        Object.keys(structure).map((key, i) => (
            this.finalStructure[key] = this.getField(structure[key], key, i)
        ));

        let finalArray = [];

        Object.keys(this.finalStructure).map((key, i) => finalArray.push(this.finalStructure[key]));

        return finalArray;
    }

    /**
     * Getting single field
     *
     * @param data
     * @param ref
     * @param i
     * @returns {*}
     */
    getField(data, ref, i) {

        data.updateFormData = this.updateFormData;

        switch (data.type) {
            case "email" :

                return <Email key={i} config={data} ref={ref} id={ref}/>;

            case "password" :

                return <Password key={i} config={data} ref={ref} id={ref}/>;

            case "checkBoxList" :

                return <CheckBoxList key={i} config={data} ref={ref} id={ref}/>;

            case "singleLine" :

                return <BaseField key={i} config={data} ref={ref} id={ref}/>;

            case "dropDownList" :

                return <DropDownList key={i} config={data} ref={ref} id={ref}/>;

            case "textArea" :

                return <TextArea key={i} config={data} ref={ref} id={ref}/>;
        }

        return "";
    }

    /**
     * Updating form data
     *
     * @param fieldId
     * @param value
     */
    updateFormData(fieldId, value) {

        this.record[fieldId] = value;
    }

    /**
     * Get buttons
     *
     * @returns {*}
     */
    getButtons() {
        let buttons = this.state.formData.buttons;
        let finalButtons = [];

        if (!buttons) {
            return finalButtons;
        }

        Object.keys(buttons).map((item, i) => (
            finalButtons.push(this.getButton(item, buttons[item], i))
        ));

        return finalButtons;
    }

    /**
     *
     * Get button
     *
     * @param type
     * @param data
     * @param i
     * @returns {*}
     */
    getButton(type, data, i) {
        switch (type) {
            case "submit" :

                return <button disabled={this.state.formDisabled}
                               key={i}
                               className={HC.helpers.buttonClass('primary')}
                               onClick={this.submitData}>{data.label}</button>;
        }
    }

    /**
     * Submitting data
     */
    submitData() {
        let valid = true;

        Object.keys(this.finalStructure).map((key, i) => {

            if (!this.refs[key].validate())
                valid = false;
        });

        if (!valid)
            return;

        this.setState({formDisabled: true});

        if (this.props.config.recordId)
            axios.put(this.state.formData.storageUrl + '/' + this.props.config.recordId, this.record).then(
                (res) =>
                    this.handleSubmitComplete(res.data)
            ).catch(error => {this.handleSubmitError(error)});
        else
            axios.post(this.state.formData.storageUrl, this.record)
                .then(
                    (res) => this.handleSubmitComplete(res.data)
                ).catch(error => {this.handleSubmitError(error)});
    }

    /**
     * Handling submit error
     *
     * @param error
     */
    handleSubmitError (error)
    {
        console.log(error.response);
        this.setState({formDisabled: false});
    }

    /**
     * After submit completed redirect
     * @param r
     */
    handleSubmitComplete(r) {

        if (r.success) {
            if (r.redirectUrl)
                document.location.href = r.redirectUrl;

            this.animateForm(false);

        }
    }
}