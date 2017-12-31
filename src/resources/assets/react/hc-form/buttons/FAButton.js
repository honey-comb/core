import React, {Component} from 'react'
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class FAButton extends Component {

    render() {

        return <div className={this.props.type} onMouseUp={this.props.onPress}>
            <FontAwesomeIcon icon={this.props.icon}/>
        </div>;
    }
}