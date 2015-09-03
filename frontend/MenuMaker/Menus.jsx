import React from 'react';
import Menu from './Menu.jsx';
import Item from './Item.jsx';
var Menus = React.createClass({
    getInitialState: function() {
        return {
            menus: [],
            items: []
        };
    },

    componentDidMount: function() {
        $.ajax({
            url: 'http://localhost/MenuMaker/backend/menu/list',
            dataType: 'json',
            success: function(result) {
                if(this.isMounted()) {
                    this.setState({
                        menus: result,
                        items: []
                    });
                }
            }.bind(this),
            error: function(err) {
                console.log(err);
            }
        });
    },
    render: function() {
        var menus = this.state.menus;
        var clicked = this.state.clicked ? "Clicked!" : "Unclicked";
        var items = this.state.items;
        return (
            <div className="menus">
                {menus.map(function(menu) {
                    return <Menu key={menu.menu} data={menu} />
                }.bind(this))}
            </div>
        );
    }
});

export default Menus;
