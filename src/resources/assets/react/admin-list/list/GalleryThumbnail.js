import React, {Component} from 'react';
import Editable from "./cells/types/Editable";

export default class GalleryThumbnail extends Editable {

    constructor(props) {
        super(props);

        this.getCells = this.getCells.bind(this);
        this.getCell = this.getCell.bind(this);
    }

    render() {

        return <div className="gallery-thumbnail" onClick={this.editRecord}>
            {this.getCells()}
        </div>
    }

    getCells() {
        let list = [];

        Object.keys(this.props.config.headers).map((key, i) => {
            list.push(this.getCell(key, i))
        });

        return list;
    }

    getCell(key, index) {

        const Cell = HC.adminListCells.get(this.props.config.headers[key].type);
        let value = this.props.value[key];

        switch (this.props.config.headers[key].type) {
            case 'image' :
                break;

            default :
                if (this.props.config.headers[key].label !== '') {
                    value = '<b>' + this.props.config.headers[key].label + '</b>: ' + value;
                }
                else {
                    value = '<b>' + value + '</b>';
                }
                break;
        }

        return <Cell config={this.props.config.headers[key]} value={value} key={index}/>
    }
}