import React from 'react';
import GalleryThumbnail from "./list/GalleryThumbnail";
import HCAdminListCore from "./list/HCAdminListCore";

export default class GalleryList extends HCAdminListCore {

    /**
     * initializing component
     * @param props
     */
    constructor(props) {
        super(props);

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
}

HC.adminList.register('gallery', GalleryList);