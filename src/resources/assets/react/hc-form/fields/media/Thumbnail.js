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
            abandoned: false,
            hideDelete: this.props.hideDelete,
            hideEdit: this.props.hideEdit,
            disableEdit: true,
            mediaId: this.props.mediaId
        };

        if (!this.state.hideEdit) {
            if (this.props.editUrl) {
                this.state.disableEdit = false;
            }
        }

        this.remove = this.remove.bind(this);
        this.edit = this.edit.bind(this);
        this.showButtons = this.showButtons.bind(this);
        this.hideButtons = this.hideButtons.bind(this);
    }

    /**
     * Rendering content
     * @returns {*}
     */
    render() {

        if (this.state.abandoned)
            return null;

        return <div className="hc-media" onMouseOver={this.showButtons} onMouseOut={this.hideButtons}>
            {this.getView()}
            <button ref="remove" onClick={this.remove} className="btn btn-danger remove" hidden={this.state.hideDelete}>
                <FontAwesomeIcon icon={HC.helpers.faIcon('trash-alt')}/>
            </button>
            <button ref="edit" onClick={this.edit} className="btn btn-warning edit" disabled={this.state.disableEdit}
                    hidden={this.state.hideEdit}>
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

        if (this.props.file)
            return this.uploadView();

        return this.nothingView();
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
     *
     * @param nextProps
     * @param nextState
     */
    componentWillUpdate(nextProps, nextState) {

        if (nextState.mediaId == null) {
            nextState.mediaId = nextProps.mediaId;
        }
    }

    componentDidUpdate ()
    {
        this.state.mediaId = null;
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

            this.props.onChange({action: "uploaded", id: res.data.data.id});
            this.setState({mediaId: res.data.data.id});
        });
    }

    /**
     * Thumbnail view
     * @returns {*}
     */
    thumbnailView() {
        return <div className="thumbnail"
                    style={{backgroundImage: "url(" + this.props.viewUrl + "/" + this.state.mediaId + "/90/90)"}}/>
    }

    /**
     * Removing component
     */
    remove() {
        this.props.onChange({action: "remove", id: this.state.mediaId});
        this.setState({abandoned: true, mediaId: null});
    }

    /**
     * Editing image meta
     */
    edit() {
        HC.react.popUp({
            url: this.props.editUrl,
            type: "form",
            recordId: this.state.mediaId,
        });
    }

    /**
     * showing buttons
     */
    showButtons() {
        this.refs.remove.style.opacity = 1;
        this.refs.edit.style.opacity = 1;
    }

    /**
     * Hiding buttons
     */
    hideButtons() {
        this.refs.remove.style.opacity = 0.1;
        this.refs.edit.style.opacity = 0.1;
    }

    /**
     * Nothing view
     * @returns {*}
     */
    nothingView() {
        return <div className="thumbnail empty">
            <FontAwesomeIcon icon={HC.helpers.faIcon('image')}/>
        </div>;
    }
}