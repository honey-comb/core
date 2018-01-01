import React, {Component} from 'react'
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

let classNames = require('classnames');

export default class FAButton extends Component {

    render() {

        let classes = classNames (this.props.type, this.props.display);

        return <div className={classes} onMouseUp={this.props.onPress}>
            <FontAwesomeIcon icon={this.props.icon}/>
        </div>;
    }
}