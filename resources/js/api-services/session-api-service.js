import axios from '../axios.config';
const baseUrl = process.env.MIX_API_BASE_URL;

export default{
    addSession: (data) => axios.post(`${baseUrl}/session/add`,data),
    getSession: (data) => axios.get(`${baseUrl}/session/get`,data),
}