import React from 'react';
import ItemForm from './ItemForm.jsx';

var NewItem = React.createClass({
    getInitialState: function() {
        return {
            clicked: false
        };
    },
    addNew: function() {
        if(!this.state.clicked) {
            this.setState({
                clicked: true
            });
        } else {
            this.setState({
                clicked: false
            });
        }
    },
    onSave: function() {
        console.log("saved");
        if(this.props.parent) {
            this.props.parent.loadMenu();
        }

        this.addNew();
    },
    render: function() {
        let placehold = {
            title: "",
            description: "",
            takeoutprice: 0,
            eatinprice: 0,
            parent: 0,
            menu: this.props.menu
        };

        let itemForm = this.state.clicked ? (<ItemForm data={placehold} action="http://localhost/MenuMaker/backend/item/create" onSave={this.onSave} />) : null;
        return (
            <div className="newitem">
                <button onClick={this.addNew}>Add New</button>
                {itemForm}
            </div>
        );
    }
});

export default NewItem;
