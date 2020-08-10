import React from 'react';
import { BrowserRouter, Switch, Redirect } from 'react-router-dom';
import AuthRoute from './AuthRoute';
import PrivateRoute from './PrivateRoute';
import HomePage from './Home/HomePage';
import ProfilePage from './Profile/ProfilePage';
import ReportPage from './Report/ReportPage';
import RegisterPage from './Auth/RegisterPage';
import LoginPage from './Auth/LoginPage';


function App() {
    return (
        <div className="container">
            <BrowserRouter>
                <Switch>
                    <PrivateRoute exact path="/" component={HomePage} />
                    <PrivateRoute exact path="/profile" component={ProfilePage} />
                    <PrivateRoute exact path="/report" component={ReportPage} />
                    <AuthRoute exact path="/register" component={RegisterPage} />
                    <AuthRoute exact path="/login" component={LoginPage} />
                    <Redirect from="*" to="/" />
                </Switch>
            </BrowserRouter>
        </div>
    );
}

export default App;
