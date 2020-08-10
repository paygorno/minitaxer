import { API_PROFILE } from './URLs';
import axios from 'axios';

export default class ProfileAPI{
    static async fetchProfile(accessToken){
        try {
            let response = await axios.get(API_PROFILE, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            return response.data.data;
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }

    static async updateProfile(accessToken, {
        firstName,
        lastName,
        taxRate,
        forceExchange,
        forceExchangePrecentAmount,
        notificationPeriod
    }){
        try {
            let response = await axios.put(API_PROFILE, {
                firstName,
                lastName,
                taxRate,
                forceExchange,
                forceExchangePrecentAmount,
                notificationPeriod
            }, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            return response.data;
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }
}
