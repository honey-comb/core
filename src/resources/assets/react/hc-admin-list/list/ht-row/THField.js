import React, {Component} from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

let classNames = require('classnames');

export default class THField extends Component {

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
        return <th tabIndex="0"
                   key={this.props.theKey}
        >
            <div className={this.state.className}>
                <FontAwesomeIcon onClick={(e) => {
                    if (this.state.flip === undefined)
                        this.state.flip = "vertical";
                    else
                        this.state.flip = undefined;

                    this.props.onSortOrderChange(this.props.theKey);
                }}
                                 icon={HCHelpers.faIcon("sort-amount-down" )} flip={this.state.flip}/>
            </div>
            <div>{this.props.label}</div>
        </th>
    }
}