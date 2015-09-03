import React from 'react';

let MenuForm = React.createClass({
    render: function() {
        return (
            <div className="menuform">
                <input type="text" name="Title">Menu Title</input>
            </div>
        );
    }
});

export default MenuForm;
