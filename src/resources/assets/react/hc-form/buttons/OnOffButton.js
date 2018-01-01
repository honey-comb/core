import React, {Component} from 'react'
import FAButton from "./FAButton";

export default class OnOffButton extends Component {

    constructor(props) {
        super(props);

        this.state = {
            type: this.props.on ? this.props.typeOn : this.props.typeOff,
            on: false
        };

        this.toggleState = this.toggleState.bind(this);

    }

    render() {

        return <FAButton display={this.props.display} type={this.state.type} icon={this.props.icon} label={this.props.label}
                               onPress={this.toggleState}/>;
    }

    toggleState() {

        if (this.state.on) {
            this.setState({type: this.props.typeOff});
        }
        else {
            this.setState({type: this.props.typeOn});
        }

        this.state.on = !this.state.on;
        this.props.onChange(this.state.on);
    }
}