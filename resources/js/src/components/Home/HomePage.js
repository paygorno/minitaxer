import React from 'react';
import Header from './../Header';
import ActionsList from './Actions/ActionsList';
import AddActionModal from './Actions/AddActionModal/AddActionModal';

class HomePage extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            addActionModalVisible: false
        }
    }

    toggleAddActionModal(){
        this.setState({addActionModalVisible: !this.state.addActionModalVisible});
    }

    render(){
        return (
            <div>
                <Header />
                <div>
                    <div>Actions:</div>
                    <button onClick={this.toggleAddActionModal.bind(this)}>Add Action</button>
                </div>
                { this.state.addActionModalVisible ? <AddActionModal toggle={this.toggleAddActionModal.bind(this)}/> : null }
                <ActionsList />
            </div>
        );
    }
}

export default HomePage;