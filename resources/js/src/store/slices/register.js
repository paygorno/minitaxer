import { createAsyncThunk, createSlice } from '@reduxjs/toolkit';
import authAPI from '../../API/authAPI';
import { create } from 'lodash';

const initialState = {
    registerErrored: false,
    registerError: null
};

const attemptRegister = createAsyncThunk(
    'register/attemptRegister',
    async (registerData, thunkAPI) => {
        try {
            let response = await authAPI.register(registerData);
            return response;
        } catch (error) {
            let err = {
                message: error.message,
                errors: error.errors
            };
            return thunkAPI.rejectWithValue(err);        }
    }
);

const registerSlice = createSlice({
    name: 'register',
    initialState,
    reducers: {
        handleRegisterError: (state) => {
            state.registerErrored = false;
            state.registerError = null;
        }
    },
    extraReducers: {
        [attemptRegister.fulfilled]: (state) => {
            state.registerErrored = false;
            state.registerError = null;
        },
        [attemptRegister.rejected]: (state, action) => {
            state.registerErrored = true;
            state.registerError = action.payload;
        }
    }
});

export const { handleRegisterError } = registerSlice.actions;
export { attemptRegister };

export default registerSlice.reducer;