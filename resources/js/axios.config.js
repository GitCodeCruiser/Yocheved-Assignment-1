import axios from 'axios';
import router from './router';

const instance = axios.create({
  timeout: 10000,
});

instance.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

instance.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token');
      router.push('/login'); // Adjust the route to your login page
      return Promise.reject(error);
    }
    return Promise.reject(error);
  }
);

export default instance;
