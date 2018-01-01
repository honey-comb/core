import React, {Component} from 'react';
import axios from 'axios';

const uuid = require('uuid/v4');

export default class List extends Component {
    constructor(props) {
        super(props);

        this.state = {
            records: {
                data: []
            }
        };

        this.getDataRowField = this.getDataRowField.bind(this);
    }

    componentDidMount() {
        axios.get(this.props.url)
            .then(res => {

                const data = res.data;

                this.setState({records: data});
            });
    }

    render() {
        let id = uuid();

        return <div id="list">
            <table id={id} className="table table-bordered table-striped dataTable" role="grid">
                <thead>
                <tr role="row">
                    {Object.keys(this.props.headers).map((item, i) => (
                            <th tabIndex="0" aria-controls={id} key={i}
                                className="sorting">{this.props.headers[item].label}</th>
                        )
                    )}
                </tr>
                </thead>
                <tbody>

                {this.state.records.data.map((item, i) => (
                    this.getDataRow(item, i)
                ))}
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>;
    }

    getDataRow(record, key) {

        return <tr id={record.id} key={key}>

            {Object.keys(this.props.headers).map((item, i) => (
                    this.getDataRowField(item, record[item], i)
            ))}
        </tr>
    }

    getDataRowField(id, value, key)
    {
        if (id === 'id')
            return <td key={key} hidden={true}>{value}</td>;

        switch (this.props.headers[id].type)
        {
            case 'text' :

                break;
        }

        return <td key={key}>{value}</td>;
    }
}