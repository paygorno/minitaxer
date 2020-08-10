import React from 'react';
import IncomeForm from './IncomeForm';
import ExchangeForm from './ExchangeForm';
import ForceExchangeForm from './ForceExchangeForm';
import ErrorModal from '../../../ErrorModal';
import { connect } from 'react-redux';
import { fetchActionsPage, createAction, handleActionCreationErrors, resetActions } from './../../../../store/slices/actions';
import { resetPage } from './../../../../store/slices/filterOptions';


class AddActionModal extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            type: '',
            amount: '',
            currencyCode: '',
            date: '',
            rate: ''
        };
    }

    handleTypeChange(event) {
        this.setState({
            type: event.target.value,
            amount: '',
            currencyCode: '',
            date: '',
            rate: ''
        });
    }

    handleChange(event) {
        let {name, value} = event.target;
        this.setState({[name]: value})
    }

    async handleSubmit(event) {
        event.preventDefault();
        await this.props.createAction({
            token: this.props.accessToken,
            actionData: this.state
        });
        if (!this.props.actionCreationErrored){
            this.props.resetActions();
            this.props.resetPage();
            await this.props.fetchActions({
                token: this.props.accessToken,
                filterOptions: this.props.filterOptions
            });
            this.props.toggle();
        }
    }

    close() {
        this.props.handleActionCreationErrors();
        this.props.toggle();
    }

    getFormComponent() {
        switch (this.state.type) {
            case 'income':
                return <IncomeForm handleChange={this.handleChange.bind(this)} formState={this.state} />;
            case 'exchange':
                return <ExchangeForm handleChange={this.handleChange.bind(this) } formState={this.state}  />;
            case 'forceExchange':
                return <ForceExchangeForm handleChange={this.handleChange.bind(this)} formState={this.state}  />;
            default:
                return <div></div>;
        }
    }

    render() {
        return (
            <div>
                <h2>Add Action</h2>
                {
                    this.props.actionCreationErrored
                        ? <ErrorModal
                            error={this.props.actionCreationError}
                            handle={this.props.handleActionCreationErrors} />
                        : null
                }
                <form onSubmit={this.handleSubmit.bind(this)}>
                    <div>
                        <label>Action</label>
                        <select 
                            name="type" 
                            value={this.state.type}
                            className="form-control"
                            onChange={this.handleTypeChange.bind(this)}>
                            <option value="">Select</option>
                            <option value="income">Icnome</option>
                            <option value="exchange">Exchange</option>
                            <option value="forceExchange">Force Exchange</option>
                        </select>
                    </div>
                    {this.getFormComponent()}
                    <div>
                        <button type="submit">Submit</button>
                    </div>
                    <div>
                        <button onClick={this.close.bind(this)}>Cancel</button>
                    </div>
                </form>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    accessToken: state.login.token,
    actionCreationErrored: state.actions.actionCreationErrored,
    actionCreationError: state.actions.actionCreationError,
    filterOptions: state.filterOptions
});

const mapDispatchToprops = dispatch => ({
    fetchActions: async ({token, filterOptions}) => 
        await dispatch(fetchActionsPage({token, filterOptions})),
    createAction: async ({token, actionData}) => 
        await dispatch(createAction({token, actionData})),
    resetActions: () => dispatch(resetActions()),
    handleActionCreationErrors: () => dispatch(handleActionCreationErrors()),
    resetPage: () => dispatch(resetPage())
});

export default connect(mapStateToProps, mapDispatchToprops)(AddActionModal);