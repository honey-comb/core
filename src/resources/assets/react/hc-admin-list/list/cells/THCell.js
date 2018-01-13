import React, {Component} from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

let classNames = require('classnames');

export default class THCell extends Component {

    constructor(props) {
        super(props);

        this.state = {
            flip: undefined,
        }
    }

    componentWillUpdate(nextProps, nextState) {
        this.state.className = classNames("sorting", {
            "active": nextProps.active
        });

        if (nextProps.active === false)
            this.state.flip = undefined;
    }

    render() {
        return <th tabIndex="0">
            <div className={this.state.className}>
                <FontAwesomeIcon onClick={(e) => {
                    if (this.state.flip === undefined)
                        this.state.flip = "vertical";
                    else
                        this.state.flip = undefined;

                    this.props.onSortOrderChange(this.props.field);
                }}
                                 icon={HC.helpers.faIcon("sort-amount-down" )} flip={this.state.flip}/>
            </div>
            <div>{this.props.label}</div>
        </th>
    }
}