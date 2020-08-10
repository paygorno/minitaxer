import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import reportAPI from '../../API/reportAPI';

const initialState = {
    reportData: {},
    reportDataFetched: false,
    reportDataFetchingErrored: false,
    reportDataFetchingError: null
};

const fetchReportData = createAsyncThunk(
    'report/fetchReportData',
    async ({token, period}) => {
        try {
            let response = await reportAPI.fetchReport(token, period);
            return response;
        } catch (error) {
            let err = {
                message: error.message,
                errors: error.errors
            };
            return thunkAPI.rejectWithValue(err);
        }
    }
);

const reportSlice = createSlice({
    name: 'report',
    initialState,
    reducers: {
        resetReportData: (state, action) => {
            state.reportData = {};
            state.reportDataFetched = false;
            state.reportDataFetchingError = null;
            state.reportDataFetchingErrored = false;
        },
        handleFetchingReportErrors: (state, action) => {
            state.reportDataFetchingError = null;
            state.reportDataFetchingErrored = false;
        }
    },
    extraReducers: {
        [fetchReportData.fulfilled]: (state, action) => {
            state.reportDataFetchingError = null;
            state.reportDataFetchingErrored = false;
            state.reportData = action.payload;
            state.reportDataFetched = true;
        },
        [fetchReportData.rejected]: (state, action) => {
            state.reportDataFetched = false;
            state.reportDataFetchingError = action.payload;
            state.reportDataFetchingErrored = true;
        }
    }
});

export const { resetReportData, handleFetchingReportErrors } = reportSlice.actions;
export { fetchReportData };

export default reportSlice.reducer
