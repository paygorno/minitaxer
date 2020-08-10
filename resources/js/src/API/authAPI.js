import { API_LOGIN, API_REGISTER, API_LOGOUT } from './URLs';
import axios from 'axios';

export default class AuthAPI{
    static async login({email, password}){
        try {
            let response = await axios.post(API_LOGIN, {email, password});
            return response.data;
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }

    static async logout(accessToken){
        try {
            let response = await axios.get(API_LOGOUT, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            return response.data;
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }

    static async register({
        firstName,
        lastName,
        email,
        password,
        passwordConfirmation
    }){
        try {
            let response = await axios.post(API_REGISTER, {
                firstName,
                lastName, 
                email,
                password,
                password_confirmation: passwordConfirmation
            });
            return response.data;
        } catch (error) {
            Object.assign(new Error(), error.response.data);
        }
    }
}