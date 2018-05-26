import React, {Component} from 'react'

export default class Html extends Component {

    render() {

        return <div dangerouslySetInnerHTML={{__html: this.props.value}}/>;
    }
}

HC.adminListCells.register('html', Html);