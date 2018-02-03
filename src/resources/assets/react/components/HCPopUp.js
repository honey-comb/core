import React, {Component} from 'react'
import HCForm from "./HCForm";

export default class HCPopUp extends Component {

    constructor(props) {
        super(props);

        this.state = {
            id: HC.helpers.uuid()
        };

        this.handlePopUpClose = this.handlePopUpClose.bind(this);
    }

    render() {
        switch (this.props.config.type) {
            case "form" :

                return <div id={this.state.id} className="hc-pop-up" style={this.props.config.style}>
                    <HCForm config={this.props.config} formClosed={this.handlePopUpClose}/>
                </div>
        }

        return "";
    }

    handlePopUpClose() {

        if (this.props.config.callBack) {
            this.props.config.callBack.call(this.props.config.scope);
        }

        HC.react.popUpRemove(this.props.config.parent);
    }
}