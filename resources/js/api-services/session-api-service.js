import axios from '../axios.config';
const baseUrl = process.env.MIX_API_BASE_URL;

export default{
    addSession: (data) => axios.post(`${baseUrl}/session/add`,data),
    getSessions: (data) => axios.get(`${baseUrl}/session/get`,data),
    getSession: (data) => axios.post(`${baseUrl}/session/get-session`,data),
    addMultipleSessions: (data) => axios.post(`${baseUrl}/session/add-multiple`, data),

    addSessionRating: (data) => axios.post(`${baseUrl}/session/add-rating`, data)
}