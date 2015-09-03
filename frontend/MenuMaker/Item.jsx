import React from 'react';
import ItemForm from './ItemForm.jsx';

var Item = React.createClass({
    getInitialState: function() {
        return {
            edit: false
        };
    },
    showEdit: function() {
        console.log("clicked");
        this.setState({
            edit: this.state.edit ? false : true,
        });

    },
    delete: function() {
        var doDel = confirm("Are you sure you want to delete this item? This action can not be reversed.");
        if(doDel) {
            var parentNode = this.props.parentNode;

            $.ajax({
                type: 'DELETE',
                url: this.props.deleteAction,
                dataType: 'json',
                data: JSON.stringify({ item : this.props.data.item }),
                success: function() {
                    parentNode.loadMenu();
                }
            });
        }
    },
    itemSave: function() {
        this.setState({
            edit: false
        });
    },
    render: function() {
        var editForm = this.state.edit ? (
            <ItemForm action="http://localhost/MenuMaker/backend/item/update" data={this.props.data} onSave={this.itemSave}/>
        ) : "";
        return (
            <div className="menuitem" data-item={this.props.data.item} data-parent={this.props.data.parent}>
                {this.props.data.title}
                <button onClick={this.showEdit}>Edit</button>
                <button onClick={this.delete}>Delete</button>
                {editForm}
            </div>
        );
    }
});

export default Item;
