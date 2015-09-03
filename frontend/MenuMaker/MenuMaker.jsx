import React from 'react';
import Menus from './Menus.jsx';
//import Items from './Items.jsx';

class MenuMaker extends React.Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <div className="menuMaker">
                <Menus remote="http://localhost/MenuMaker/backend/menu/list" />
            </div>
        )
    }
}

export default MenuMaker;
