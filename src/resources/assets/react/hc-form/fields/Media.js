import React from 'react';
import Base from "./BaseField";
import Thumbnail from "./media/Thumbnail";

export default class Media extends Base {

    constructor(props) {
        super(props);

        this.count = this.props.config.count ? this.props.config.count : 1;
        this.multiple = !(this.count === 1);
        this.thumbnails = [];
        this.state = {
            count: 0
        };

        this.mediaList = [];

        this.fileListChange = this.fileListChange.bind(this);
        this.thumbnailUpdated = this.thumbnailUpdated.bind(this);
    }

    /**
     *
     * @returns {*[]}
     */
    getInput() {
        return <div className="thumbnails">{this.generateThumbnails()}</div>
    }

    /**
     *
     * @param e
     */
    fileListChange(e) {

        let files = this.refs.inputField.files;

        Object.keys(files).map((item, i) => this.createThumbnailFromFile(files[item]));
    }

    /**
     * Creating thumbnail from file
     * @param file
     */
    createThumbnailFromFile(file) {
        if (file.size > this.props.config.size * 1024) {
            console.log('File to big');
            return;
        }

        this.createThumbnail(file, null);
    }

    /**
     * Creating media from existing id
     * @param media
     */
    createThumbnailFromMedia(media) {
        //if it is already exists do not add it
        if (this.mediaList.indexOf(media) !== -1) return;

        this.mediaList.push(media);
        this.createThumbnail(null, media);
    }

    /**
     * Creating thumbnail
     *
     * @param file
     * @param mediaId
     */
    createThumbnail(file, mediaId) {
        this.state.count++;

        this.thumbnails.push(<Thumbnail file={file}
                                        mediaId={mediaId}
                                        key={HC.helpers.uuid()}
                                        uploadUrl={this.props.config.uploadUrl}
                                        viewUrl={this.props.config.viewUrl}
                                        editUrl={this.props.config.editUrl}
                                        onChange={this.thumbnailUpdated}/>);

        this.setState({count: this.state.count});
    }

    /**
     * Generating all of the thumbnails
     *
     * @returns {*[]}
     */
    generateThumbnails() {

        //TODO if data is available

        return [...this.thumbnails, this.getAddButton()];
    }

    /**
     * Creating add more button
     *
     * @returns {*}
     */
    getAddButton() {
        if (this.count <= this.state.count) {
            return "";
        }

        return <div className="hc-media-uploader" key={HC.helpers.uuid()}>
            <label>
                <input type="file"
                       ref="inputField"
                       multiple={this.multiple}
                       accept={this.props.config.accept}
                       onChange={this.fileListChange}/>
                <span>Upload media (max: {this.count - this.state.count})</span>
            </label>
        </div>;
    }

    /**
     * Thumbnail has been updated
     *
     * @param config
     */
    thumbnailUpdated(config) {
        switch (config.action) {
            case "uploaded":

                this.mediaList.push(config.id);
                break;

            case "remove":

                this.mediaList.splice(this.mediaList.indexOf(config.id), 1);

                this.state.count--;
                this.setState({count: this.state.count});
                break;
        }

        this.contentChange();
    }

    /**
     * Setting value
     * @param data
     */
    setValue(data) {
        if (this.count === 1) {
            if (!Array.isArray(data))
                this.createThumbnailFromMedia(data);

            else
                this.createThumbnailFromMedia(data[0].id);

            return;
        }

        data.map((media) => {

            this.createThumbnailFromMedia(media.id);
        });

        this.triggerChange();
    }

    /**
     * Getting value
     */
    getValue() {

        if (this.count === 1)
            if (this.mediaList[0])
                return this.mediaList[0];


        return this.mediaList;
    }
}