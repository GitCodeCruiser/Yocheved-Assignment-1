import axios from '../axios.config';
const baseUrl = process.env.MIX_API_BASE_URL;

export default{
    Login : (data) => axios.post(`${baseUrl}/login`,data),
}