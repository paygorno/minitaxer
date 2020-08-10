import React from 'react';
import { Link } from 'react-router-dom';

const Header = () => {
    return (
        <div>
            <Link to="/">
                <div>
                    Minitaxer
                </div>                
            </Link>
            <Link to="/report">
                <div>
                    Report
                </div>                
            </Link>
            <Link to="/profile">
                <div>
                    Profile
                </div>                
            </Link>
        </div>
    );
};

export default Header; 