import React, {Component} from 'react';
import OnOffButton from "../form/buttons/OnOffButton";

export default class Settings extends Component {

    constructor ()
    {
        super();

        this.handleChange = this.handleChange.bind(this);
    }

    render() {
        return <div id="settings">
            <OnOffButton display={this.props.trashHidden} onChange={this.handleChange} icon={HC.helpers.faIcon("trash")} on={false} typeOff={HC.helpers.buttonClass()} typeOn={HC.helpers.buttonClass("forestgreen")}/>
        </div>;
    }

    handleChange (data) {
        this.props.onChange(data);
    }
}