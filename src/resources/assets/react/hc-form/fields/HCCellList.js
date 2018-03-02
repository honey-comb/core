import React, {Component} from 'react'

export default class HCCellList extends Component {

    constructor(props) {
        super(props);

        this.addMorePopUp = this.addMorePopUp.bind(this);
    }

    render() {

        const list = <ul>{this.getList()}</ul>;

        if (list) {
            return list;
        }

        return <div>-</div>;
    }

    getList() {
        let content = [];
        let lastKey = 0;

        this.props.value.map((value, i) => {
            content.push(<li key={i}>{value[this.props.config.valuePath]}</li>);
            lastKey++;
        });

        if (this.props.config.addMore) {
            content.push(<li key={lastKey} onClick={this.addMorePopUp}><a style={{cursor: 'pointer'}}>Add more</a>
            </li>);
        }

        return content;
    }

    addMorePopUp() {
        const url = this.props.config.addMore + '?' + this.props.config.idAs + '=' + this.props.id;

        HC.react.popUp({
            url: url,
            type: "form",
            callBack: this.props.recordUpdated,
            scope: this.props.recordUpdatedScope
        });
    }
}