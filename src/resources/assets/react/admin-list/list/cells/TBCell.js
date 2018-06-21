import React, {Component} from 'react';
import Thumbnail from "../../../form/fields/media/Thumbnail";
import Url from "./types/Url";
import List from "./types/List";
import Copy from "./types/Copy";
import DateTime from "./types/DateTime";
import Action from "./types/Action";

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
        this.reloadAfterPatch = false;

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
            this.state.url = this.props.config.url + '/' + nextProps.id;
            this.state.value = nextProps.value;
        }
    }

    render() {

        const content = this.getContent();

        const tdClass = classNames({
            update: (this.props.update && !this.disableUpdate)
        }, this.cellClasses);

        return <td className={tdClass} onClick={this.editRecord}
                   style={{width: this.props.options.cellWidth + '%'}}>{content}</td>;
    }

    editRecord() {

        if (!this.props.update || this.disableUpdate)
            return;

        if (this.props.config.options && this.props.config.options.separatePage) {
            window.location.href = window.location.href + '/edit/' + this.props.id;
            return;
        }

        HC.react.popUp({
            url: HC.helpers.extendUrl(this.props.config.form, "-edit"),
            type: "form",
            recordId: this.props.id,
            callBack: this.recordUpdated,
            scope: this
        });
    }

    recordUpdated() {

        this.props.reload(true);
    }

    getContent() {
        switch (this.props.options.type) {
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

            case 'copy' :

                this.disableUpdate = true;
                return this.getCopy();

            case 'time' :

                return this.getTime();

            case 'action' :

                this.reloadAfterPatch = true;
                this.state.url = this.props.config.url + '/' + this.props.id;
                this.disableUpdate = true;
                return this.getAction();
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

        return <Thumbnail value={this.state.value}
                          key={this.id}
                          config={this.props.options}/>
    }

    getUrl() {
        return <Url key={this.id}
                    id={this.props.id}
                    value={this.state.value}
                    config={this.props.options}/>
    }

    getList() {
        return <List key={this.id}
                     id={this.props.id}
                     value={this.state.value}
                     config={this.props.options}
                     recordUpdated={this.recordUpdated}
                     recordUpdatedScope={this}/>
    }

    getTime() {
        return <DateTime key={this.id}
                         value={this.state.value}
                         config={this.props.options}/>
    }

    getCopy() {
        return <Copy
            key={this.id}
            record={this.props.record}
            value={this.state.value}
            config={this.props.options}
        />
    }

    getAction() {
        return <Action key={this.id}
                       id={this.props.id}
                       config={this.props.options}
                       onChange={this.updateStrict}/>
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

                if (this.reloadAfterPatch) {
                    this.props.reload(true);
                }
            }).catch(error => {
            this.setState({
                value: !value,
                disabled: false,
            });
        });
    }
}