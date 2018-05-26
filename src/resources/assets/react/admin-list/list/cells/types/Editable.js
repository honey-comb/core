import React, {Component} from 'react';

export default class Editable extends Component {

    constructor(props) {
        super(props);

        this.editRecord = this.editRecord.bind(this);
        this.recordUpdated = this.recordUpdated.bind(this);
    }

    editRecord() {

        if (this.props.config.options && this.props.config.options.separatePage)
        {
            window.location.href = window.location.href + '/edit/' + this.props.value.id;
            return;
        }

        HC.react.popUp({
            url: HC.helpers.extendUrl(this.props.config.form, "-edit"),
            type: "form",
            recordId: this.props.value.id,
            callBack: this.recordUpdated,
            scope: this
        });
    }

    recordUpdated() {

        this.props.reload(true);
    }
}