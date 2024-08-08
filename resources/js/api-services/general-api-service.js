import axios from '../axios.config';

export default{
    getPaginatedData: (url) => axios.get(url),
    postPaginatedData: (url, data) => axios.post(url, data),
}