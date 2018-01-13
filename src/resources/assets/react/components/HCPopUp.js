import React, {Component} from 'react'
import HCForm from "./HCForm";

export default class HCPopUp extends Component {

    constructor(props) {
        super(props);

        this.state = {
            id: HC.helpers.uuid()
        }
    }

    render() {
        switch (this.props.config.type) {
            case "form" :

                return <div id={this.state.id} className="hc-pop-up">
                    <HCForm config={this.props.config} formClosed={() => HC.react.popUpRemove(this.props.config.parent)}/>
                </div>
        }

        return "";
    }
}