import React from 'react';
import { Link } from 'react-router-dom';
import ErrorModal from './../ErrorModal';
import { connect } from 'react-redux';
import { attemptLogin, handleLoginError } from './../../store/slices/login';

class LoginPage extends React.Component {
    constructor(props){
        super(props);

        this.state = {
            email: '',
            password: ''
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
   }

   handleChange(event) {
        let {name, value} = event.target;
        this.setState({[name]: value});
   }

   async handleSubmit(event) {
       event.preventDefault();
       await this.props.attemptLogin({
           email: this.state.email,
           password: this.state.password
       });
       if (this.props.loggedIn) this.props.history.push('/');
   }

   render(){
        return (
        <div>
            <h2>Login</h2>
            {
                this.props.loginErrored 
                    ? <ErrorModal 
                        error={this.props.loginError}
                        handle={this.props.handleLoginError} />
                    : null
            }
            <form onSubmit={this.handleSubmit}>
                <div className="form-group">
                    <label>Email</label>
                    <input 
                        type="text" 
                        name="email" 
                        value={this.state.email}
                        className="form-control"
                        onChange={this.handleChange}></input>
                </div>
                <div className="form-group">
                    <label>Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        value={this.state.password}
                        className="form-control"
                        onChange={this.handleChange}></input>
                </div>
                <div className="form-group">
                    <button type="submit">Login</button>
                </div>
            </form>
            <Link to='/register'>
                <button>Register</button>
            </Link>
        </div>);
   }
}

const mapDispatchToProps = dispatch => ({
    attemptLogin: async loginData => await dispatch(attemptLogin(loginData)),
    handleLoginError: () => dispatch(handleLoginError())
});

const mapStateToProps = state => ({
    loginErrored: state.login.loginErrored,
    loginError: state.login.loginError,
    loggedIn: state.login.loggedIn
});

export default connect(mapStateToProps, mapDispatchToProps)(LoginPage);