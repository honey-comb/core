import React, {Component} from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome'
import axios from "axios/index";

let classNames = require('classnames');

export default class TBCell extends Component {

    constructor(props) {
        super(props);

        this.state = {
            url: this.props.url + '/' + this.props.id,
            internalUpdate: false,
            value: this.props.value,
            disabled: false
        };

        this.getCheckBox = this.getCheckBox.bind(this);
        this.updateStrict = this.updateStrict.bind(this);
    }

    componentWillUpdate(nextProps, nextState) {

        if (this.state.internalUpdate) {
            this.state.internalUpdate = false;
            this.state.value = nextProps.value;
        }
        else {
            this.state.url = this.props.url + '/' + nextProps.id;
            this.state.value = nextProps.value;
        }
    }

    render() {
        return <td>{this.getContent()}</td>;
    }

    getContent() {
        switch (this.props.config.type) {
            case "text" :

                return this.state.value;

            case "checkBox" :

                return this.getCheckBox();
        }

        return "";
    }

    getCheckBox() {
        return <input type="checkbox" disabled={this.state.disabled} checked={this.state.value} onChange={this.updateStrict}/>
    }

    updateStrict() {
        let value = !this.state.value;
        let params = {};
        params[this.props.fieldKey] = value;

        this.setState({disabled: true});

        this.state.internalUpdate = true;

        axios.patch(this.state.url, params)
            .then(res => {

                this.setState({
                    value: value,
                    disabled: false,
                });
            }).catch(error => {
            this.setState({
                value: !value,
                disabled: false,
            });
        });
    }
}