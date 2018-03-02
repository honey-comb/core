import React, {Component} from 'react';
import Thumbnail from "../../../hc-form/fields/media/Thumbnail";
import Url from "../../../hc-form/fields/Url";
import HCCellList from "../../../hc-form/fields/HCCellList";

let classNames = require('classnames');

export default class TBCell extends Component {

    constructor(props) {
        super(props);

        this.id = HC.helpers.uuid();

        this.cellClasses = '';

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

        const content = this.getContent();

        const tdClass = classNames({
            update: (this.props.update && !this.disableUpdate)
        }, this.cellClasses);

        return <td className={tdClass} onClick={this.editRecord}>{content}</td>;
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

                this.cellClasses = 'text-center';
                this.disableUpdate = true;
                return this.getCheckBox();

            case "image" :

                this.disableUpdate = true;
                return this.getImage();

            case 'url' :
                this.disableUpdate = true;
                return this.getUrl();

            case 'list' :
                this.disableUpdate = true;
                return this.getList();
        }

        return "";
    }

    getCheckBox() {
        return <input type="checkbox"
                      key={this.id} disabled={this.state.disabled}
                      checked={this.state.value}
                      onChange={this.updateStrict}/>
    }

    getImage() {

        return <Thumbnail mediaId={this.state.value}
                          key={this.id}
                          hideDelete={true}
                          hideEdit={true}
                          viewUrl="/resources"/>
    }

    getUrl ()
    {
        return <Url key={this.id}
                    id={this.props.id}
                    value={this.state.value}
                    config={this.props.config}/>
    }

    getList ()
    {
        return <HCCellList key={this.id}
                           id={this.props.id}
                           value={this.state.value}
                           config={this.props.config}
                           recordUpdated={this.recordUpdated}
                           recordUpdatedScope={this}/>
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