import React, {Component} from 'react';
import OnOffButton from "../hc-form/buttons/OnOffButton";

export default class Settings extends Component {

    render() {
        return <div id="settings">
            <OnOffButton icon={HCHelpers.faIcon("trash")} on={false} typeOff={HCHelpers.buttonClass()} typeOn={HCHelpers.buttonClass("forestgreen")}/>
        </div>;
    }
}