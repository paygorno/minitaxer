import { createAsyncThunk, createSlice } from '@reduxjs/toolkit';
import actionsAPI from '../../API/actionsAPI';
import { connect } from 'react-redux';

const initialState = {
    actions: [],
    actionsFetched: false,
    actionsFetchingErrored: false,
    actionsFetchingError: null,
    actionCreationErrored: false,
    actionCreationError: null
};

const fetchActionsPage = createAsyncThunk(
    'actions/fetchActionsFiltered',
    async ({token, filterOptions}, thunkAPI) => {
        try {
            let response = await actionsAPI.fetchFiltered(token, filterOptions);
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

const createAction = createAsyncThunk(
    'actions/createAction',
    async ({token, actionData}, thunkAPI) => {
        let data = actionData;
        try {
            let response = await actionsAPI.createAction(token, data);
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

const actionsSlice = createSlice({
    name: 'actions',
    initialState,
    reducers: {
        handleActionsPageFetchingErrors: (state) => {
            state.actionsFetchingErrored = false;
            state.actionsFetchingError = null;
        },
        handleActionCreationErrors: (state) => {
            state.actionCreationErrored = false;
            state.actionCreationError = null;
        },
        resetActions: (state) => {
            state.actions = [];
        }
    },
    extraReducers: {
        [createAction.fulfilled]: (state) => {
            state.actionCreationError = null;
            state.actionCreationErrored = false;
        },
        [createAction.rejected]: (state, action) => {
            state.actionCreationError = action.payload;
            state.actionCreationErrored = true;
        },
        [fetchActionsPage.fulfilled]: (state, action) => {
            state.actionsFetched = true;
            state.actionsFetchingErrored = false;
            state.actionsFetchingError = null;
            state.actions = state.actions.concat(action.payload);
        },
        [fetchActionsPage.rejected]: (state, action) => {
            state.actionsFetchingErrored = true;
            state.actionsFetchingError = action.payload;
        }
    }
});

export const {
    handleActionsPageFetchingErrors,
    handleActionCreationErrors,
    resetActions
} = actionsSlice.actions;
export { fetchActionsPage, createAction };

export default actionsSlice.reducer;