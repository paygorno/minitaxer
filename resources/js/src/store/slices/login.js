import { createAsyncThunk, createSlice } from '@reduxjs/toolkit';
import authAPI from '../../API/authAPI';

let token = localStorage.getItem('token');
const initialState = token 
    ? {
        loggedIn: true,
        loginErrored: false,
        loginError: null,
        token
    }
    : {
        loggedIn: false,
        loginErrored: false,
        loginError: null,
        token
    };

const attemptLogin = createAsyncThunk(
    'login/attemptLogin',
    async (loginData, thunkAPI) => {
        try {
            let response = await authAPI.login(loginData);
            localStorage.setItem('token', response.token);
            return response; 
        } catch (e) {
            let err = {
                message: e.message,
                errors: e.errors
            };
            return thunkAPI.rejectWithValue(err);
        }
    }
);

const attemptLogout = createAsyncThunk(
    'login/attemptLogout',
    async (token, thunkAPI) => {
        try {
            let response = await authAPI.logout(token);
            localStorage.removeItem('token');
            return response; 
        } catch (e) {
            let err = {
                message: e.message,
                errors: e.errors
            };
            return thunkAPI.rejectWithValue(err);;
        }
    }
);

const loginSlice = createSlice({
    name: 'login',
    initialState,
    reducers: {
        handleLoginError: (state, action) => {
            state.loginErrored = false;
            state.loginError = null;
        }
    },
    extraReducers: {
        [attemptLogin.fulfilled]: (state, action) => {
            state.loggedIn = true;
            state.loginErrored = false;
            state.loginError = null;
            state.token = action.payload.token;
        },
        [attemptLogin.rejected]: (state, action) => {
            state.loggedIn = false;
            state.loginErrored = true;
            state.loginError = action.payload;
            state.token = null;
        },
        [attemptLogout.fulfilled]: (state, action) => {
            state.loggedIn = false;
            state.loginErrored = false;
            state.loginError = null;
            state.token = action.payload.token;
        }
    },
});

export const { handleLoginError } = loginSlice.actions;
export { attemptLogin, attemptLogout };

export default loginSlice.reducer;

