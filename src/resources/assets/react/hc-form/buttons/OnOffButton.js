import React, {Component} from 'react'
import FAButton from "./FAButton";

export default class OnOffButton extends Component {

    constructor(props) {
        super(props);

        this.state = {
            type: this.props.on ? this.props.typeOn : this.props.typeOff,
            current: false
        };

        this.toggleState = this.toggleState.bind(this);

    }

    render() {

        return <FAButton type={this.state.type} icon={this.props.icon} label={this.props.label}
                               onPress={this.toggleState}/>;
    }

    toggleState() {

        if (this.state.current) {
            this.setState({type: this.props.typeOff});
        }
        else {
            this.setState({type: this.props.typeOn});
        }

        this.state.current = !this.state.current;
    }
}