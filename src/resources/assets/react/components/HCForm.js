import React, {Component} from 'react'
import TweenMax from "gsap"
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class HCForm extends Component {

    constructor(props) {
        super(props);

        this.refs = {
            formHolder: ""
        };

        this.state = {
            id: HC.helpers.uuid()
        };

        this.opacity = 0;
    }

    render() {
        return <div ref="formHolder" id={this.state.id} className="hc-form" style={{opacity:this.opacity}}>
            <div className="header">
                <div className="close" style={{float:"left"}} onClick={() => this.animateForm(false)}>
                    <FontAwesomeIcon icon={HC.helpers.faIcon('times-circle')}/>
                </div>
                <div className="label">{this.props.contentID ? "Edit record" : "New record"}</div>
            </div>
        </div>;
    }

    componentDidMount ()
    {
        this.animateForm (true);
    }

    animateForm (forward)
    {
        if (forward)
        {
            TweenMax.to(this, 0.5, {
                opacity: 1,
                onUpdate: () => this.refs.formHolder.style.opacity = this.opacity,
            });
        }
        else
        {
            console.log('backwards');

            TweenMax.to(this, 0.5, {
                opacity: 0,
                onUpdate: () => this.refs.formHolder.style.opacity = this.opacity,
                onComplete: this.props.formClosed
            });
        }
    }
}