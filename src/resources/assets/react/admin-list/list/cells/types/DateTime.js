import React, {Component} from 'react'

export default class DateTime extends Component {

    constructor(props) {
        super(props);

        this.getValue = this.getValue.bind(this);
    }

    render() {
        return <div>
            {this.getValue()}
        </div>;
    }

    getValue() {
        let time = new Date(this.props.value);
        time.addHours(this.props.config.utc);

        return time.getFullYear() + '-' + time.getFullMonth() + '-' + time.getFullDay() + ' ' +
            time.getFullHours() + ':' + time.getFullMinutes() + ':' + time.getFullSeconds()
    }
}