import React, {Component} from 'react'

export default class Text extends Component {

    render() {

        return <div>
            {this.props.value}
        </div>;
    }
}

HC.adminListCells.register('text', Text);