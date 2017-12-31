import React, { Component } from 'react';

export default class HCBaseComponent extends Component {
    getConfiguration() {
        return {oneWay:true};
    }
}