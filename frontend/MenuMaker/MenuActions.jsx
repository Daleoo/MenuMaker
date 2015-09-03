import React from 'react';
import MenuEdit from './MenuEdit.jsx';

let MenuActions = React.createClass({
    getInitialState: function() {
        return {
            clicked: false
        }
    },
    generateEatIn: function() {
        //Generate an eat in menu
        window.location.href = 'http://localhost/MenuMaker/backend/menu/generate/eatin/'+this.props.menu;
    },
    generateTakeOut: function() {
        window.location.href = 'http://localhost/MenuMaker/backend/menu/generate/takeout/'+this.props.menu;
    },
    showActions: function() {
        this.setState({
            clicked: this.state.clicked ? false : true
        });
    },
    render: function() {
        let actionlist = null;
        if(this.state.clicked) {
            actionlist = (
                <div className="actionlist">
                    <button onClick={this.updateMagento}>Update Magento</button>
                    <button onClick={this.generateEatIn}>Generate Eat In Menu</button>
                    <button onClick={this.generateTakeOut}>Generate Takeout Menu</button>
                    <MenuEdit />
                </div>
            );
        }
        return (
            <div className="menuactions">
                <button onClick={this.showActions}>Menu Actions</button>
                {actionlist}
            </div>
        );
    }
});

export default MenuActions;
