import React, {Component} from 'react';
import TDRow from "./list/TBRow";
import THRow from "./list/THRow";
import GalleryThumbnail from "./list/GalleryThumbnail";

const uuid = require('uuid/v4');

export default class GalleryList extends Component {

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
        this.getGalleries = this.getGalleries.bind(this);
    }

    /**
     * Rendering view
     * @returns {*}
     */
    render() {

        return <div id="hc-gallery-list">{this.getGalleries()}</div>;
    }

    getGalleries ()
    {
        let galleries = [];

        this.props.records.data.map ((value, i) => {
            galleries.push(<GalleryThumbnail config={this.props.config}
                                             value={value}
                                             key={i}
                                             reload={this.props.reload}
            />)
        });

        return galleries;
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

HC.adminList.register('gallery', GalleryList);