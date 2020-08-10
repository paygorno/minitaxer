import React from 'react';
import Header from './../Header';
import ReportForm from './ReportForm';
import Report from './Report';
import ErrorModal from '../ErrorModal';
import { connect } from 'react-redux';
import { fetchReportData, resetReportData, handleFetchingReportErrors } from './../../store/slices/report';

class ReportPage extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            startDate: '',
            endDate: ''
        };
    }

    componentDidMount() {
        this.props.resetReportData();
    }

    handleChange(event) {
        let {name, value} = event.target;
        this.setState({[name]: value});
    }

    async handleSubmit(event) {
        event.preventDefault();
        await this.props.fetchReportData({
            token: this.props.accessToken,
            period: this.state
        });
    }

    render() {
        return (
        <div>
            <Header />
            <h2>Report</h2>
            {
                this.props.reportDataFetchingError
                    ? <ErrorModal
                        error={this.props.reportDataFetchingError}
                        handle={this.props.handleFetchingReportErrors} />
                    : null
            }
            <div>
                {
                    this.props.reportDataFetched 
                        ? <Report
                            reportData={this.props.reportData} />
                        : <ReportForm 
                            handleChange={this.handleChange.bind(this)}
                            handleSubmit={this.handleSubmit.bind(this)} 
                            formState={this.state}/>

                }
            </div>
        </div>
        );
    }
}

const mapStateToProps = state => ({
    reportData: state.report.reportData,
    accessToken: state.login.token,
    reportDataFetched: state.report.reportDataFetched,
    reportDataFetchingError: state.report.reportDataFetchingError
});

const mapDispatchToProps = dispatch => ({
    fetchReportData: async ({token, period}) => dispatch(fetchReportData({token, period})),
    resetReportData: () => dispatch(resetReportData()),
    handleFetchingReportErrors: () => dispatch(handleFetchingReportErrors())
});

export default connect(mapStateToProps, mapDispatchToProps)(ReportPage);