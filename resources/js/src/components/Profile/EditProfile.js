import React from 'react';
import { connect } from 'react-redux';
import { updateProfile, handleProfileUpdatingErrors } from './../../store/slices/profile';
import ErrorModal from '../ErrorModal';

class EditProfile extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            firstName: this.props.profile.firstName,
            lastName: this.props.profile.lastName,
            taxRate: this.props.profile.taxRate,
            forceExchange: this.props.profile.forceExchange,
            forceExchangeAmount: this.props.profile.forceExchangeAmount,
            notificationPeriod: this.props.profile.notificationPeriod
        };


    }

    handleChange(event) {
        let {name, value} = event.target;
        this.setState({[name]: value});
    }

    handleCheckboxChange(event) {
        let {name, checked} = event.target;
        this.setState({[name]: checked});
    }

    async handleSubmit(event) {
        event.preventDefault();
        await this.props.updateProfile({
            token: this.props.accessToken,
            profileData: {
                firstName: this.state.firstName,
                lastName: this.state.lastName,
                taxRate: this.state.taxRate,
                forceExchange: this.state.forceExchange,
                forceExchangeAmount: this.state.forceExchangeAmount,
                notificationPeriod: this.state.notificationPeriod
            }
        });
        if (!this.props.profileUpdatingErrored)
            this.props.toggle();
    }

    render() {
        return (
            <div>
                {
                    this.props.profileUpdatingErrored
                        ? <ErrorModal
                            error={this.props.profileUpdatingError}
                            handle={this.props.handleProfileUpdatingErrors} />
                        : null
                }
                <form className="form-group" onSubmit={this.handleSubmit.bind(this)}>
                    <div>
                        <label>First Name</label>
                        <input 
                            type="text" 
                            name="firstName" 
                            value={this.state.firstName}
                            className="form-control"
                            onChange={this.handleChange.bind(this)}></input>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input 
                            type="text" 
                            name="lastName" 
                            value={this.state.lastName}
                            className="form-control"
                            onChange={this.handleChange.bind(this)}></input>
                    </div>
                    <div>
                        <label>Tax Rate</label>
                        <input 
                            type="text" 
                            name="taxRate" 
                            value={this.state.taxRate}
                            className="form-control"
                            onChange={this.handleChange.bind(this)}></input>
                    </div>
                    <div>
                        <label>Force Exchange</label>
                        <input 
                            type="checkbox" 
                            name="forceExchange" 
                            checked={this.state.forceExchange}
                            className="form-control"
                            onChange={this.handleCheckboxChange.bind(this)}></input>
                    </div>
                    <div>
                        <label>Force Exchange Amount</label>
                        <input 
                            type="text" 
                            name="forceExchangeAmount" 
                            value={this.state.forceExchangeAmount}
                            className="form-control"
                            onChange={this.handleChange.bind(this)}></input>
                    </div>
                    <div>
                        <label>Notification Period</label>
                        <select 
                            type="text" 
                            name="notificationPeriod" 
                            value={this.state.notificationPeriod}
                            className="form-control"
                            onChange={this.handleChange.bind(this)}>
                            <option value="off">off</option>
                            <option value="quarter">quarter</option>
                            <option value="month">month</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit">Submit</button>
                    </div>
                    <div>
                        <button onClick={this.props.toggle}>Cancel</button>
                    </div>
                </form>
            </div>
        );
    }
}

const mapStateToProps = state => ({
    accessToken: state.login.token,
    profileUpdatingErrored: state.profile.profileUpdatingErrored,
    profileUpdatingError: state.profile.profileUpdatingError
});

const mapDispatchToProps = dispatch => ({
    updateProfile: async ({token, profileData}) => await dispatch(updateProfile({token, profileData})),
    handleProfileUpdatingErrors: () => dispatch(handleProfileUpdatingErrors)
});

export default connect(mapStateToProps, mapDispatchToProps)(EditProfile);