import { createAsyncThunk, createSlice } from '@reduxjs/toolkit';
import actionsAPI from '../../API/actionsAPI';

const initialState = {
    currentDetailedAction: null,
    detailedActionRetrieved: false,
    actionFetchingErrored: false,
    actionFetchingError: null,
    actionDeletingErrored: false,
    actionDeleteError: null
};

const fetchByTypeId = createAsyncThunk(
    'actions/detailedAction/fetchByTypeId',
    async ({token, type, id}, thunkAPI) => {
        try {
            let response = await actionsAPI.fetchDetailedByTypeId(
                token,
                {type, id}
            );
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

const deleteByTypeId = createAsyncThunk(
    'actions/detailedAction/deleteByTypeId',
    async ({token, type, id}, thunkAPI) => {
        try {
            let response = await actionsAPI.deleteByTypeId(
                token,
                {type, id}
            );
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

const detailedActionSlice = createSlice({
    name: 'detailedAction',
    initialState,
    reducers: {
        handleDetailedActionFetchingErrors: (state) => {
            state.actionFetchingError = null;
            state.actionFetchingErrored = false;
        },
        handleActionDeletingErrors: (state) => {
            state.actionDeletingErrored = false;
            state.actionDeleteError = null;
        },
        clearCurrentDetailedAction: (state) => {
            state.currentDetailedAction = null;
        }
    },
    extraReducers: {
        [deleteByTypeId.fulfilled]: (state) => {
            state.actionDeletingErrored = false;
            state.actionDeleteError = null; 
        },
        [deleteByTypeId.rejected]: (state, action) => {
            state.actionDeletingErrored = true;
            state.actionDeleteError = action.payload;
        },
        [fetchByTypeId.fulfilled]: (state) => {
            state.actionFetchingError = null;
            state.actionFetchingErrored = false;
        },
        [fetchByTypeId.rejected]: (state, action) => {
            state.actionFetchingError = action.payload;
            state.actionFetchingErrored = true;
        }
    }
});

export const { 
    handleDetailedActionFetchingErrors,
    handleActionDeletingErrors,
    clearCurrentDetailedAction
} = detailedActionSlice.actions;
export { fetchByTypeId, deleteByTypeId };

export default detailedActionSlice.reducer;





