import React from 'react';
import { connect } from 'react-redux';
import { fetchActionsPage } from './../../../store/slices/actions';
import { incrementPage } from './../../../store/slices/filterOptions';
import ShortenedAction from './ShortenedAction';

class ActionsList extends React.Component {
    constructor(props) {
        super(props);
    }

    async componentDidMount() {
        if (!this.props.actionsFetched){
            await this.loadMore();
        }
    }

    async loadMore() {
        await this.props.fetchActions({
            token: this.props.accessToken,
            filterOptions: this.props.filterOptions
        });
        this.props.incrementPage();
    }

    render() {
        return (
            <div>
                {
                    this.props.actions.map((action) => 
                    <ShortenedAction 
                        key={`${action.type}${action.id}`} 
                        action={action} />
                    )
                }
                <button onClick={this.loadMore.bind(this)} >More</button>   
            </div>
        );
    }
}

const mapStateToProps = state => ({
    actionsFetched: state.actions.actionsFetched,
    actions: state.actions.actions,
    accessToken: state.login.token,
    filterOptions: state.filterOptions
});

const mapDispatchToProps = dispatch => ({
    fetchActions: async ({token, filterOptions}) => await dispatch(fetchActionsPage({token, filterOptions})),
    incrementPage: () => dispatch(incrementPage())
});

export default connect(mapStateToProps, mapDispatchToProps)(ActionsList);