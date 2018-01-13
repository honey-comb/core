import React, {Component} from 'react'
import TweenMax from "gsap"
import FontAwesomeIcon from '@fortawesome/react-fontawesome'
import axios from "axios/index";

export default class HCForm extends Component {

    constructor(props) {
        super(props);

        this.refs = {
            formHolder: ""
        };

        this.state = {
            id: HC.helpers.uuid(),
            formData: {}
        };

        this.opacity = 0;
    }

    render() {
        return <div ref="formHolder" id={this.state.id} className="hc-form" style={{opacity: this.opacity}}>
            <div className="header">
                <div className="close" style={{float: "left"}} onClick={() => this.animateForm(false)}>
                    <FontAwesomeIcon icon={HC.helpers.faIcon('times-circle')}/>
                </div>
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

    componentDidMount() {
        this.animateForm(true);
        this.loadFormData();
    }

    /**
     * Loading form data
     */
    loadFormData() {
        let url = this.props.config.url;

        axios.get(url)
            .then(res => {

                this.setState({
                    formData: res.data,
                });
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

            TweenMax.to(this, 0.5, {
                opacity: 0,
                onUpdate: () => this.refs.formHolder.style.opacity = this.opacity,
                onComplete: this.props.formClosed
            });
        }
    }

    getFields ()
    {
        console.log(this.state.formData.structure);
    }

    getButtons ()
    {
        let buttons = this.state.formData.buttons;
        let finalButtons = [];

        if (!buttons) {
            return "";
        }

        Object.keys(buttons).map((item, i) => (
            finalButtons.push(this.getButton (item, buttons[item], i))
        ));

        return finalButtons;
    }

    getButton (type, data, i)
    {
        switch (type)
        {
            case "submit" :

                return <div key={i} className={HC.helpers.buttonClass('primary')}>{data.label}</div>;
        }
    }

}