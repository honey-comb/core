import React, {Component} from 'react'
import FontAwesomeIcon from "@fortawesome/react-fontawesome";

export default class Action extends Component {

    constructor(props) {
        super(props);

        this.commitAction = this.commitAction.bind(this);
        this.actionCompleted = this.actionCompleted.bind(this);
    }

    render() {

        const icon = this.props.config.icon;

        return <div onClick={this.props.onChange} className="text-center">
            <a style={{cursor: 'pointer'}}>
                <FontAwesomeIcon icon={HC.helpers.faIcon(icon)}/>
            </a>
        </div>;
    }

    commitAction() {
        HC.react.loader.patch(this.props.config.url + '/' + this.props.id, null, this.actionCompleted);
    }

    actionCompleted ()
    {
        this.props.actionCompleted();
    }
}