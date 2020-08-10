import React from 'react';
import { Link } from 'react-router-dom';
import ErrorModal from './../ErrorModal';
import { connect } from 'react-redux';
import { attemptRegister, handleRegisterError } from './../../store/slices/register';

class RegisterPage extends React.Component {
    constructor(props){
        super(props);

        this.state = {
            firstName: '',
            lastName: '',
            email: '',
            password: '',
            passwordConfirmation: ''
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
       await this.props.attemptRegister({
           firstName: this.state.firstName,
           lastName: this.state.lastName,
           email: this.state.email,
           password: this.state.password,
           passwordConfirmation: this.state.passwordConfirmation
       });
       if (!this.props.registerErrored) this.props.history.push('/login');
   }

   render() {
        return (
            <div>
                <h2>Register</h2>
                {
                    this.props.registerErrored
                        ? <ErrorModal
                            error={this.props.registerError}
                            handle={this.props.handleRegisterError} />
                        : null
                }
                <form onSubmit={this.handleSubmit}>
                <div className="form-group">
                        <label>First Name</label>
                        <input 
                            type="text" 
                            name="firsName" 
                            value={this.state.firstName}
                            className="form-control"
                            onChange={this.handleChange}></input>
                    </div>
                    <div className="form-group">
                        <label>Last Name</label>
                        <input 
                            type="text" 
                            name="lastName" 
                            value={this.state.lastName}
                            className="form-control"
                            onChange={this.handleChange}></input>
                    </div>
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
                        <label>Confirm password</label>
                        <input 
                            type="password" 
                            name="passwordConfirmation" 
                            value={this.state.passwordConfirmation}
                            className="form-control"
                            onChange={this.handleChange}></input>
                    </div>
                    <div className="form-group">
                        <button type="submit">Register</button>
                    </div>
                </form>
                <Link to='/login'>
                    <button>Login</button>
                </Link>
            </div>);
    }
}

const mapDispatchToProps = dispatch => ({
    attemptRegister: registerData => dispatch(attemptRegister(registerData)),
    handleRegisterError: () => dispatch(handleRegisterError())
});

const mapStateToProps = state => ({
    registerErrored: state.register.registerErrored,
    registerError: state.register.registerError
});

export default connect(mapStateToProps, mapDispatchToProps)(RegisterPage);