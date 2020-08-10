import { createSlice } from '@reduxjs/toolkit';

const initialState = {
    page: 1,
    types: ['income', 'exchange', 'forceExchange'],
    startDate: '',
    endDate: '',
    currencies: ['UAH', 'USD']
};

const filterOptionsSlice = createSlice({
    name: 'filterOptoins',
    initialState,
    reducers: {
        updateFilterOptions: (state, action) => {
            state.types = action.payload.types;
            state.startDate = action.payload.startDate;
            state.startDate = action.payload.startDate;
            state.currencies = action.payload.currencies;
        },
        resetFilterOptions: () => {
            return initialState;
        },
        incrementPage: (state) => {
            state.page++;
        },
        resetPage: (state) => {
            state.page = 1;
        }
    }
});

export const {
    updateFilterOptions,
    resetFilterOptions,
    incrementPage,
    resetPage
} = filterOptionsSlice.actions;

export default filterOptionsSlice.reducer;