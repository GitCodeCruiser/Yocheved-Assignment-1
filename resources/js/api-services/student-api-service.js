import axios from '../axios.config';
const baseUrl = process.env.MIX_API_BASE_URL;

export default{
    addStudent : (data) => axios.post(`${baseUrl}/student/add`,data),
    getStudents: (data) => axios.get(`${baseUrl}/student/get`,data),

    addAvailability : (data) => axios.post(`${baseUrl}/availability/add`,data),
    getAvailability: (data) => axios.post(`${baseUrl}/availability/get`,data),
}