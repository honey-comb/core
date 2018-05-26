import React, {Component} from 'react'
import HCForm from "./HCForm";
import FontAwesomeIcon from '@fortawesome/react-fontawesome';
import TweenMax from "gsap";

export default class HCPopUp extends Component {

    constructor(props) {
        super(props);

        this.state = {
            id: HC.helpers.uuid(),
            popUpLabel: ''
        };

        this.handlePopUpClose = this.handlePopUpClose.bind(this);
        this.updateLabel = this.updateLabel.bind(this);
    }

    render() {
        switch (this.props.config.type) {
            case "form" :

                return <div id={this.state.id} ref="popUp" className="hc-pop-up" style={this.props.config.style}>
                    <div className="header">
                        {this.getCloseButton()}
                        <div className="label">{this.state.popUpLabel}</div>
                    </div>
                    <HCForm config={this.props.config} formDataLoaded={this.updateLabel}
                            formClosed={this.handlePopUpClose}/>
                </div>
        }

        return "";
    }

    updateLabel(value) {
        this.setState({popUpLabel: value})
    }

    componentDidMount() {
        this.animatePopUp(true);
    }

    /**
     * Getting close button
     * TODO:Move button to PopUp
     * @returns {*}
     */
    getCloseButton() {
        if (this.props.config.parent)
            return <div className="close" style={{float: "left"}} onClick={() => this.animatePopUp(false)}>
                <FontAwesomeIcon icon={HC.helpers.faIcon('times-circle')}/>
            </div>;

        return "";
    }

    /**
     * Animate PopUp
     *
     * @param forward
     */
    animatePopUp(forward) {

        if (forward) {
            TweenMax.to(this, 0.5, {
                opacity: 1,
                onUpdate: () => this.refs.popUp.style.opacity = this.opacity,
            });
        }
        else {

            if (!this.props.config.parent)
                return;

            TweenMax.to(this, 0.5, {
                opacity: 0,
                onUpdate: () => this.refs.popUp.style.opacity = this.opacity,
                onComplete: this.handlePopUpClose
            });
        }
    }

    handlePopUpClose() {

        if (this.props.config.callBack) {
            this.props.config.callBack.call(this.props.config.scope);
        }

        HC.react.popUpRemove(this.props.config.parent);
    }
}