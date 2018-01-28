import React, {Component} from 'react'
import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class Thumbnail extends Component {

    /**
     * @param props
     */
    constructor(props) {
        super(props);

        this.state = {
            progress: 0,
            mediaId: this.props.mediaId,
            abandoned: false
        };

        this.remove = this.remove.bind(this);
        this.edit = this.edit.bind(this);
    }

    /**
     * Rendering content
     * @returns {*}
     */
    render() {

        if (this.state.abandoned)
            return null;

        return <div className="hc-media">
            {this.getView()}
            <button onClick={this.remove} className="btn btn-danger remove">
                <FontAwesomeIcon icon={HC.helpers.faIcon('trash-alt')}/>
            </button>
            <button onClick={this.edit} className="btn btn-warning edit" disabled={true}>
                <FontAwesomeIcon icon={HC.helpers.faIcon('edit')}/>
            </button>
        </div>;
    }

    /**
     * Getting right view
     * @returns {*}
     */
    getView() {

        if (this.state.mediaId) {
            return this.thumbnailView();
        }

        return this.uploadView();
    }

    /**
     * Uploading file if it is present
     */
    componentDidMount() {

        if (this.props.file) {
            this.uploadFile();
        }
    }

    /**
     * Generating upload view
     * @returns {*[]}
     */
    uploadView() {
        return [
            <div key={0} className="percentage" ref="progress">{this.state.progress}</div>,
            <div key={1} className="spinner">
                <FontAwesomeIcon icon={HC.helpers.faIcon('spinner-third')} spin={true}/>
            </div>
        ]
    }

    /**
     * Upload file logic
     */
    uploadFile() {
        let formData = new FormData();
        formData.append('file', this.props.file);
        axios.post(this.props.uploadUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: progressEvent => {
                this.setState(
                    {
                        progress: ((progressEvent.loaded / progressEvent.total).toFixed(2) * 100).toFixed(0)
                    })
            }
        }).then((res) => {

            this.props.onChange({action:"uploaded", id:res.data.data.id});
            this.setState({mediaId: res.data.data.id});
        });
    }

    /**
     * Thumbnail view
     * @returns {*}
     */
    thumbnailView() {
        return <div className="thumbnail" style={{backgroundImage: "url(" + this.props.viewUrl + "/" + this.state.mediaId + "/90/90)"}}> </div>
    }

    /**
     * Removing component
     */
    remove ()
    {
        this.props.onChange({action:"remove", id:this.state.mediaId});
        this.setState({abandoned:true});
    }

    /**
     * Editing image meta
     */
    edit ()
    {
        console.log(this.state.mediaId);
    }
}