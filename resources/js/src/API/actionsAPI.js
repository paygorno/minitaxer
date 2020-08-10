import { API_ACTIONS } from './URLs';
import axios from 'axios';

export default class ActionsAPI{
    static async fetchFiltered(
        accessToken, 
        {
            types = [],
            startDate = null,
            endDate = null,
            currencies = [],
            page
        }
    ){
        try {
            let response = await axios.get(API_ACTIONS, {
                params: {
                    page,
                    types: types.length ? types.join(',') : null,
                    startDate,
                    endDate,
                    currencies: currencies.length ? currencies.join(',') : null
                },
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            return response.data.data;
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }

    static async fetchDetailedByTypeId(
        accessToken,
        {
            type,
            id
        }
    ){
        try {
            let response = axios.get(`${API_ACTIONS}/${type}${id}`, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            return response.data;
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }

    static async createAction(
        accessToken, 
        {
            type,
            currencyCode,
            amount,
            date,
            rate = null
        }
    ){
        try {
            let response = await axios.post(API_ACTIONS, {
                type,
                currencyCode,
                amount,
                date,
                rate
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

    static async deleteByTypeId(
        accessToken,
        {
            type,
            id
        }
    ){
        try {
            let response = axios.delete(`${API_ACTIONS}/${type}${id}`, {
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