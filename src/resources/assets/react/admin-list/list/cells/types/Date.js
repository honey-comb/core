import React, {Component} from 'react'

export default class Date extends Component {

    render() {

        return <div dangerouslySetInnerHTML={{__html: this.props.value.substr(0, this.props.value.lastIndexOf(' '))}}/>;
    }
}

HC.adminListCells.register('date', Date);