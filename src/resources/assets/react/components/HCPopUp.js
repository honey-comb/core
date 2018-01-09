import React, {Component} from 'react'

const uuid = require('uuid/v4');

export default class HCPopUp extends Component {
    render ()
    {
        return <div id={uuid()} className="hc-pop-up">Welcome to PopUp</div>
    }
}