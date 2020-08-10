import React from 'react';
import Header from './../Header';
import { connect } from 'react-redux';
import { fetchProfile } from './../../store/slices/profile';
import Profile from './Profile';
import EditProfile from './EditProfile';

class ProfilePage extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            editingMode: false
        };
    }

    toggleEditing(){
        this.setState({editingMode: !this.state.editingMode});
    }

    componentDidMount(){
        if (!this.props.profileFetched)
            this.props.fetchProfile(this.props.accessToken); 
    }

    render(){
        return (
            <div>
                <Header />
                {
                    this.state.editingMode
                        ? <EditProfile 
                            profile={this.props.profile} 
                            toggle={this.toggleEditing.bind(this)}/>
                        : <Profile 
                            profile={this.props.profile} 
                            toggle={this.toggleEditing.bind(this)}/>
                }
            </div>
            );
    }
}

const mapStateToProps = state => ({
    profile: state.profile.profile,
    profileFetched: state.profile.profileFetched,
    accessToken: state.login.token
});

const mapDispatchToProps = dispatch => ({
    fetchProfile: async token => await dispatch(fetchProfile(token))
});

export default connect(mapStateToProps, mapDispatchToProps)(ProfilePage);