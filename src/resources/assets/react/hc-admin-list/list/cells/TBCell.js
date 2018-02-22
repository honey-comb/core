import React, {Component} from 'react';
import Thumbnail from "../../../hc-form/fields/media/Thumbnail";
import Url from "../../../hc-form/fields/Url";

let classNames = require('classnames');

export default class TBCell extends Component {

    constructor(props) {
        super(props);

        this.state = {
            url: this.props.url + '/' + this.props.id,
            internalUpdate: false,
            value: this.props.value,
            disabled: false
        };

        this.disableUpdate = false;

        this.getCheckBox = this.getCheckBox.bind(this);
        this.updateStrict = this.updateStrict.bind(this);
        this.editRecord = this.editRecord.bind(this);
    }

    componentWillUpdate(nextProps, nextState) {

        if (this.state.internalUpdate) {
            this.state.internalUpdate = false;
            this.state.value = nextProps.value;
        }
        else {
            this.state.url = this.props.url + '/' + nextProps.id;
            this.state.value = nextProps.value;
        }
    }

    render() {

        let tdClass = classNames({
            update: this.props.update
        });

        return <td className={tdClass} onClick={this.editRecord}>{this.getContent()}</td>;
    }

    editRecord() {

        if (!this.props.update || this.disableUpdate)
            return;

        HC.react.popUp({
            url: HC.helpers.extendUrl(this.props.form, "-edit"),
            type: "form",
            recordId: this.props.id,
            callBack: this.recordUpdated,
            scope: this
        });
    }

    recordUpdated() {
        this.props.reload();
    }

    getContent() {
        switch (this.props.config.type) {
            case "text" :

                return this.state.value;

            case "checkBox" :

                this.disableUpdate = true;
                return this.getCheckBox();

            case "image" :

                this.disableUpdate = true;
                return this.getImage();

            case 'url' :
                this.disableUpdate = true;
                return this.getUrl();
        }

        return "";
    }

    getCheckBox() {
        return <input type="checkbox"
                      disabled={this.state.disabled}
                      checked={this.state.value}
                      onChange={this.updateStrict}/>
    }

    getImage() {

        return <Thumbnail mediaId={this.state.value}
                          key={HC.helpers.uuid()}
                          hideDelete={true}
                          hideEdit={true}
                          viewUrl="/resources"/>
    }

    getUrl ()
    {
        return <Url key={HC.helpers.uuid()}
                    id={this.props.id}
                    value={this.state.value}
                    config={this.props.config}/>
    }

    updateStrict() {
        let value = !this.state.value;
        let params = {};
        params[this.props.fieldKey] = value;

        this.setState({disabled: true});

        this.state.internalUpdate = true;

        axios.patch(this.state.url, params)
            .then(res => {

                this.setState({
                    value: value,
                    disabled: false,
                });
            }).catch(error => {
            this.setState({
                value: !value,
                disabled: false,
            });
        });
    }
}