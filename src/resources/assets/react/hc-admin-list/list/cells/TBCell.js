import React, {Component} from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome'
import axios from "axios/index";

let classNames = require('classnames');

export default class TBCell extends Component {

    constructor(props) {
        super(props);

        this.state = {
            value: false, //this.props.value
            url: this.props.url + '/' + this.props.id
        };

        this.getCheckBox = this.getCheckBox.bind(this);
        this.updateStrict = this.updateStrict.bind(this);
    }

    render() {
        return <td>{this.getContent()}</td>;
    }

    getContent() {
        switch (this.props.config.type) {
            case "text" :

                return this.props.value;

            case "checkBox" :

                return this.getCheckBox();
        }

        return "";
    }

    getCheckBox() {
        return <input type="checkbox" checked={this.state.value} onChange={this.updateStrict}/>
    }

    updateStrict() {
        let value = !this.state.value;
        let params = {};
        params[this.props.fieldKey] = value;

        axios.patch(this.state.url, params)
            .then(res => {

                this.setState({
                    value: value,
                });
            }).catch(error => {
            this.setState({
                value: !value,
            });
        });
    }
}