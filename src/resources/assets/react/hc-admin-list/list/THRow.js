import React, {Component} from 'react';
import THCell from "./cells/THCell";

export default class THRow extends Component {

    constructor(props)
    {
        super(props);

        this.state = {
            sort_by: "",
            sort_order: "",
            thFields:[]
        };

        this.onSortOrderChange = this.onSortOrderChange.bind(this);
    }

    render ()
    {
        return <tr role="row">
            <th hidden={this.props.hideCheckBox}
                className="main-checkbox">

                <input type="checkbox"
                       checked={this.props.checked}
                       onChange={this.props.invertAll}/>
            </th>
            {Object.keys(this.props.headers).map((item, i) => (
                <THCell label={this.props.headers[item].label}
                        key={item}
                        field={item}
                        active={this.state.thFields[item]}
                        onSortOrderChange={this.onSortOrderChange}/>
                )
            )}
        </tr>
    }

    /**
     * sorting order update
     * @param key
     */
    onSortOrderChange (key)
    {
        this.state.thFields[this.state.sort_by] = false;

        if (this.state.sort_by === key)
        {
            if(this.state.sort_order === "asc")
                this.state.sort_order = "desc";
            else
                this.state.sort_order = "asc";
        }
        else
        {
            this.state.sort_by = key;
            this.state.sort_order = "asc";
        }

        this.state.thFields[key] = true;

        this.props.onSortOrderUpdate(key, this.state.sort_order);
    }
}