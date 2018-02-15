import React, {Component} from 'react'
import FontAwesomeIcon from "@fortawesome/react-fontawesome";

export default class Url extends Component{

    render () {
        const icon = this.props.config.external ? 'external-link' : 'link';
        const target = this.props.config.external ? '_blank' : '_self';
        const url = this.getUrl();

        return <a href={url} target={target}>
            <FontAwesomeIcon icon={HC.helpers.faIcon(icon)}/>
        </a>;
    }

    getUrl() {
        if (this.props.config.url !== '') {
            if (this.props.config.useId)
                return HC.helpers.extendUrl(this.props.config.url, this.props.id, true);
            else
                return this.props.config.url;
        } else {
            return HC.helpers.extendUrl(window.location.href, this.props.id, true);
        }
    }
}