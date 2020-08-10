import { createAsyncThunk, createSlice } from '@reduxjs/toolkit';
import profileAPI from '../../API/profileAPI';

const initialState = {
    profileFetched: false,
    profileFetchingErrored: false,
    profileFetchingError: null,
    profileUpdatingErrored: false,
    profileUpdatingError: null,
    profile: {}
};

const fetchProfile = createAsyncThunk(
    'profile/fetchProfile',
    async (token, thunkAPI) => {
        try {
            let response = await profileAPI.fetchProfile(token);
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

const updateProfile = createAsyncThunk(
    'profile/updateProfile',
    async ({token, profileData}, thunkAPI) => {
        try {
            let response = await profileAPI.updateProfile(
                token,
                profileData
            );
            return { response, profileData };
        } catch (error) {
            let err = {
                message: error.message,
                errors: error.errors
            };
            return thunkAPI.rejectWithValue(err);        }
    }
);

const profileSlice = createSlice({
    name: 'profile',
    initialState,
    reducers: {
        handleProfileFetchingErrors: (state) => {
            state.profileFetchingErrored = false;
            state.profileFetchingError = null;
        },
        handleProfileUpdatingErrors: (state) => {
            state.profileUpdatingErrored = false;
            state.profileUpdatingError = null;
        } 
    },
    extraReducers: {
        [fetchProfile.fulfilled]: (state, action) => {
            state.profileFetched = true;
            state.profileFetchingErrored = false;
            state.profileFetchingError = null;
            state.profile = action.payload;
        },
        [fetchProfile.rejected]: (state, action) => {
            state.profileFetched = false;
            state.profileFetchingErrored = true;
            state.profileFetchingError = action.payload;
        },
        [updateProfile.fulfilled]: (state, action) => {
            state.profileUpdatingErrored = false;
            state.profileUpdatingError = null;
            state.profile = action.payload.profileData;
        },
        [updateProfile.rejected]: (state, action) => {
            state.profileUpdatingErrored = true;
            state.profileUpdatingError = action.payload;
        }
    }
});

export const { handleProfileFetchingErrors, handleProfileUpdatingErrors } = profileSlice.actions;
export { fetchProfile, updateProfile };

export default profileSlice.reducer;