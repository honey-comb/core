import React, {Component} from 'react';

const uuid = require('uuid/v4');

export default class HCAdminListCore extends Component {

    /**
     * initializing component
     * @param props
     */
    constructor(props) {
        super(props);

        this.state = {
            listId: uuid(),
            globalSelection: false,
            allSelected: false,
            update: this.props.config.actions.indexOf('update') !== -1 ? 1 : 0,
            selected: [],
            sortBy: {},
            listHeight: {}
        };

        this.handleResize = this.handleResize.bind(this);
    }

    componentDidMount ()
    {
        window.addEventListener('resize', this.handleResize);
        this.calculateListHeight(window.innerHeight);
    }

    handleResize (e)
    {
        this.calculateListHeight(e.currentTarget.innerHeight);
    }

    calculateListHeight (height)
    {
        height -= 345;
        this.setState({listHeight:height});
    }
}