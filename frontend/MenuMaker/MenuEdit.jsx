import React from 'react';
import MenuForm from './MenuForm.jsx';

let MenuEdit = React.createClass({
    getInitialState: function() {
        return {
            enabled: false
        };
    },
    click: function() {
        this.setState({
            enabled: this.state.enabled ? false : true
        });
    },
    render: function() {
        let form = this.state.enabled ? (<MenuForm />) : null;
        let className = "menuedit" + (this.state.enabled ? " enabled" : "");
        return (
            <div className={className}>
                <button onClick={this.click}>Edit Menu</button>
                {form}
            </div>
        );
    }
});

export default MenuEdit;
