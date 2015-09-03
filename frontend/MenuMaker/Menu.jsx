import React from 'react';
import Item from './Item.jsx';
import NewItem from './NewItem.jsx';
import MenuActions from './MenuActions.jsx';

var Menu = React.createClass({
    getInitialState: function() {
        return {
            items: [],
            clicked: false
        };
    },
    componentDidMount: function() {

    },
    loadMenu: function() {
        var menu = $(this.getDOMNode()).data('menu');
        $.ajax({
            url: 'http://localhost/MenuMaker/backend/menu/view/' + menu,
            dataType: 'json',
            success: function(result) {
                if(this.isMounted()) {
                    this.setState({
                        items: result,
                        clicked: true
                    });
                }
            }.bind(this),
            error: function(err) {
                console.log(err);
            }
        });
    },
    showMenu: function(event) {
        if(event && event.target != this.getDOMNode && event.target.tagName != "H2") {
            event.stopPropagation();
            return null;
        }

        if(!this.state.clicked) {
            this.loadMenu();
        } else {
            this.setState({
                items: [],
                clicked: false
            });
        }
    },
    generate: function() {
        console.log("Generating");
    },
    render: function() {
        var items = this.state.items;
        let addNew = this.state.clicked ? (<NewItem menu={this.props.data.menu} parent={this} />) : null;
        let button = this.state.clicked ? (<MenuActions menu={this.props.data.menu} />) : null;
        return (
            <div className="menu" onClick={this.showMenu} data-menu={this.props.data.menu}>
                <h2>{this.props.data.title}</h2>
                {button}
                {items.map(function(item) {
                    return <Item data={item} key={item.item} parentNode={this} deleteAction="http://localhost/MenuMaker/backend/item/delete" />
                }.bind(this))}
                {addNew}
            </div>
        )
    }
});

export default Menu;
