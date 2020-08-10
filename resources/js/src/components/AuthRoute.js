import React from 'react';
import { Route, Redirect } from 'react-router-dom';

const AuthRoute = ({ component: Component, ...rest }) => (
    <Route {...rest} render={props => (
        localStorage.getItem('token')
            ? <Redirect to={{ pathname: '/', state: { from: props.location } }} />
            : <Component {...props} />
    )} />
)

export default AuthRoute;