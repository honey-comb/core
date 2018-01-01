import React, {Component} from 'react';
import OnOffButton from "../hc-form/buttons/OnOffButton";

export default class Settings extends Component {

    constructor ()
    {
        super();

        this.handleChange = this.handleChange.bind(this);
    }

    render() {
        return <div id="settings">
            <OnOffButton display={this.props.trashHidden} onChange={this.handleChange} icon={HCHelpers.faIcon("trash")} on={false} typeOff={HCHelpers.buttonClass()} typeOn={HCHelpers.buttonClass("forestgreen")}/>
        </div>;
    }

    handleChange (data) {
        this.props.onChange(data);
    }
}