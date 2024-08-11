import axios from '../axios.config';
const baseUrl = process.env.MIX_API_BASE_URL;

export default{
    addReport: (data) => axios.post(`${baseUrl}/report/add`,data),
    getReport: (data) => axios.post(`${baseUrl}/report/get`,data),
    
    printReport: (data) => {
        return axios.post(`${baseUrl}/report/generate-report`, data, {
            responseType: 'blob',
        });
    },

}