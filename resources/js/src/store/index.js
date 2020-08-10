import { configureStore } from '@reduxjs/toolkit'
import loginReducer from './slices/login';
import registerReducer from './slices/register';
import profileReducer from './slices/profile';
import actionsReducer from './slices/actions';
import detailedActionReducer from './slices/detailedAction';
import filterOptionsReducer from './slices/filterOptions';
import reportReducer from './slices/report';

const store = configureStore({
    reducer: {
        login: loginReducer,
        register: registerReducer,
        profile: profileReducer,
        actions: actionsReducer,
        detailedAction: detailedActionReducer,
        filterOptions: filterOptionsReducer,
        report: reportReducer
    }
});

export default store;
