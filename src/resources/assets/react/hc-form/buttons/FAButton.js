import React, {Component} from 'react'
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

let classNames = require('classnames');

export default class FAButton extends Component {

    render() {

        let divClasses = classNames(this.props.type, {
            "hidden": this.props.display,
            "disabled": this.props.disabled
        }, this.props.classes);

        let hideCounter = !this.props.showCounter;

        if (this.props.disabled)
            hideCounter = true;

        if (this.props.count === 0)
            hideCounter = true;

        let counterClasses = classNames(
            "counter", "fa-layers-counter", {
                "hidden": hideCounter
            }
        );

        return <div className={divClasses} onMouseUp={(e) => {
            if (!this.props.disabled && this.props.onPress)
                this.props.onPress()
        }}>
            <FontAwesomeIcon icon={this.props.icon}/>
            <span className={counterClasses}>{this.props.count}</span>
        </div>;
    }
}