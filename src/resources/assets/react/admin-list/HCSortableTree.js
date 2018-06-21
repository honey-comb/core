import React from 'react';
import HCAdminListCore from "./list/HCAdminListCore";
import SortableTree, {toggleExpandedForAll} from 'react-sortable-tree';
import 'react-sortable-tree/style.css';
import FAButton from "../form/buttons/FAButton";

export default class HCSortableTree extends HCAdminListCore {

    /**
     * initializing component
     * @param props
     */
    constructor(props) {

        super(props);

        this.state.treeData = [];
        this.innerChange = false;
        this.treeIndexing = [];
        this.labelKey = this.props.config.labelKey ? this.props.config.labelKey : 'label';

        this.expandAll = this.expandAll.bind(this);
        this.collapseAll = this.collapseAll.bind(this);
        this.formatTreeData = this.formatTreeData.bind(this);
        this.deleteAction = this.deleteAction.bind(this);
        this.actionCompleted = this.actionCompleted.bind(this);
        this.nodeMoved = this.nodeMoved.bind(this);

        this.bottomMargin = 265;
    }

    /**
     * Rendering view
     * @returns {*}
     */
    render() {

        if (!this.innerChange) {
            this.state.treeData = this.formatTreeData(this.props.records);
            this.innerChange = false;
        }

        return (
            <div id="sortable-tree" style={{height: this.state.listHeight}}>
                <SortableTree
                    treeData={this.state.treeData}
                    maxDepth={this.props.config.maxDepth}
                    onChange={treeData => {

                        this.innerChange = true;

                        this.setState({treeData})
                    }}
                    generateNodeProps={rowInfo => ({
                        buttons: [
                            <FAButton type={HC.helpers.buttonClass('danger')}
                                      icon={HC.helpers.faIcon('trash')}
                                      onPress={() => {
                                          this.deleteAction(rowInfo)
                                      }}/>,
                            <FAButton type={HC.helpers.buttonClass('warning')}
                                      icon={HC.helpers.faIcon('pen')}
                                      onPress={() => {
                                          this.editRecord(rowInfo)
                                      }}
                                      style={{marginLeft: 10}}/>
                        ],
                    })}
                    onMoveNode={this.nodeMoved}
                />
            </div>
        );
    }

    formatTreeData(list) {

        let formated = [];

        if (HC.helpers.isArray(list)) {

            list.map((value, i) => {

                this.treeIndexing.push(value['id']);

                let menu = {
                    title: HC.helpers.pathIndex(value, this.labelKey),
                    subtitle: value['url'],
                    id: value['id'],
                    expanded: true,
                    children: this.formatTreeData(value.children)
                };

                formated.push(menu)
            });
        }

        return formated;
    }

    expand(expanded) {
        this.setState({
            treeData: toggleExpandedForAll({
                treeData: this.state.treeData,
                expanded,
            }),
        });
    }

    expandAll() {
        this.expand(true);
    }

    collapseAll() {
        this.expand(false);
    }

    /**
     *  delete action function
     */
    deleteAction(e) {

        let params = {data: {list: [e.node.id]}};

        HC.react.loader.delete(this.props.config.url, params, this.actionCompleted, true);
    }

    /**
     * Deletion completed force reload
     */
    actionCompleted() {
        this.props.reload(true);
    }

    /**
     *
     */
    editRecord(e) {

        if (this.props.config.options && this.props.config.options.separatePage) {
            window.location.href = window.location.href + '/edit/' + this.props.id;
            return;
        }

        HC.react.popUp({
            url: HC.helpers.extendUrl(this.props.config.form, '-edit'),
            type: 'form',
            recordId: e.node.id,
            callBack: this.actionCompleted,
            scope: this
        });
    }

    nodeMoved(node) {

        let data = {
            children : []
        };

        if (node.path.length === 1) {
            data.parent = null;

            node.treeData.map((value, i) => {
                data.children[i] = value.id;
            });
        }
        else
        {
            data.parent = node.nextParentNode.id;

            node.nextParentNode.children.map((value, i) => {
                data.children[i] = value.id;
            });
        }

        HC.react.loader.patch(this.props.config.url, data, this.props.reload);
    }
}

HC.adminList.register('sortable-tree', HCSortableTree);