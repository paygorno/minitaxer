import { API_REPORT } from './URLs';
import axios from 'axios';
 
export default class ReportAPI{
    static async fetchReport(
        accessToken,
        {
            startDate,
            endDate
        }
    ){
        try {
            let response = await axios.get(API_REPORT, {
                params: {
                    startDate,
                    endDate
                },
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            return response.data.data
        } catch (error) {
            throw Object.assign(new Error(), error.response.data);
        }
    }
}