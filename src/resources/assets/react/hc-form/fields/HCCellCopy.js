import React, {Component} from 'react'
import FontAwesomeIcon from "@fortawesome/react-fontawesome";

export default class HCCellCopy extends Component {

    constructor(props) {
        super(props);

        this.copyData = this.copyData.bind(this);
        this.getText = this.getText.bind(this);
    }

    render() {

        const icon = 'copy';

        return <div onClick={this.copyData} className="text-center">
            <a style={{cursor: 'pointer'}}>
                <FontAwesomeIcon icon={HC.helpers.faIcon(icon)}/>
            </a>
        </div>;
    }

    /**
     * https://stackoverflow.com/a/48908805/657451
     */
    copyData() {
        const textField = document.createElement('textarea');
        textField.innerText = this.getText();
        const parentElement = document.getElementsByTagName('body');
        parentElement.appendChild(textField);
        textField.select();
        document.execCommand('copy');
        parentElement.removeChild(textField);
    }

    getText() {
        let value = null;

        if (this.props.config.valuePath) {
            value = HC.helpers.pathIndex(this.props.record, this.props.config.valuePath);
        }
        else {
            value = this.state.value;
        }

        if (this.props.config.prefix) {
            return this.props.config.prefix + value;
        }

        return value;
    }
}