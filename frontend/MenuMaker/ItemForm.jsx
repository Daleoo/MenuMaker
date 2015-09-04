import React from 'react';

var ItemForm = React.createClass({
    getInitialState: function() {
        return {
            menus: [],
            items: []
        };

        $('form').submit(function(e) {
            e.preventDefault();
        });
    },
    componentDidMount: function() {
        $.ajax({
            url: 'http://localhost/MenuMaker/backend/menu/list',
            dataType: 'json',
            success: function(result) {
                if(this.isMounted()) {
                    this.setState({
                        menus: result,
                        items: this.state.items
                    })
                }
            }.bind(this),
            error: function(err) {
                console.log(err);
            }
        });
        $.ajax({
            url: 'http://localhost/MenuMaker/backend/item/list/0',
            dataType: 'json',
            success: function(result) {
                if(this.isMounted()) {
                    this.setState({
                        menus: this.state.menus,
                        items: result
                    });
                }
            }.bind(this),
            error: function(err) {
                console.log(err);
            }
        });
    },
    save: function() {
        var vals = {};
        $(this.getDOMNode()).children('input,select,textarea').each(function(child) {
            if(!$(this).val()) {
                vals[$(this).attr('name')] = "0";
            } else {
                vals[$(this).attr('name')] = $(this).val();
            }
        });


        console.log(vals);
        let onSave = this.props.onSave;
        let self = this;
        $.ajax({
            method: 'PUT',
            url: this.props.action,
            dataType: 'json',
            data: JSON.stringify(vals),
            success: function(res) {
                if(onSave) {
                    onSave();
                }
            }
        });
    },
    render: function() {
        let takeOutPrice = parseFloat(this.props.data.takeoutprice ? this.props.data.takeoutprice : 0).toFixed(2);
        let eatInPrice = parseFloat(this.props.data.eatinprice).toFixed(2);
        let id = this.props.data.item ? this.props.data.item : 0;
        let parentItem = this.props.data.parent;
        console.log(this.props.data.parent);
        return (<div className="itemform">
            <input type="hidden" name="item" value={id} />
            <label for="title">Title</label>
            <input type="text" name="title" id="title" defaultValue={this.props.data.title} />
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" cols="50" defaultValue={this.props.data.description}></textarea>
            <label for="eatin">Allow Eat In?</label>
            <select name="eatin" id="eatin" defaultValue={this.props.data.eatin}>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <label for="eatinprice">Eat In Price</label>
            <input type="text" name="eatinprice" defaultValue={eatInPrice} id="eatinprice" />
            <label for="takeout">Allow Take Out?</label>
            <select name="takeout" id="takeout" defaultValue={this.props.data.takeout}>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <label for="takeoutprice">Take Out Price</label>
            <input type="text" name="takeoutprice" defaultValue={takeOutPrice} id="takeoutprice" />
            <label for="parent">Parent Item</label>
            <select name="parent" defaultValue={this.props.data.parent} id="parent">
                <option value="0">No Parent</option>

                    {this.state.items.map(function(item) {
                        if(parentItem == item.item) {
                            return (<option value={item.item} key={item.item} selected>{item.title}</option>);
                        }
                        return (<option value={item.item} key={item.item}>{item.title}</option>);
                    })}
            </select>
            <label for="menu">Menu</label>
            <select name="menu" value={this.props.data.menu} id="menu">
                <option value="0">No Menu</option>
                    {this.state.menus.map(function(menu) {
                        return <option value={menu.menu} key={menu.menu}>{menu.title}</option>
                    })}
            </select>
            <button onClick={this.save}>Save</button>
        </div>
        );
    }
});

export default ItemForm;
