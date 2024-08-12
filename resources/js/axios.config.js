import axios from 'axios';
import router from './router';

// Create an Axios instance with a 10-second timeout
const instance = axios.create({
  timeout: 10000,
});

// Interceptor to attach the Authorization header with the token for each request
instance.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`; // Add token to headers
    }
    return config;
  },
  error => {
    return Promise.reject(error); // Handle request errors
  }
);

// Interceptor to handle responses, particularly 401 Unauthorized errors
instance.interceptors.response.use(
  response => response, // Pass through successful responses
  error => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token'); // Remove token if unauthorized
      router.push('/login'); // Redirect to login page
      return Promise.reject(error);
    }
    return Promise.reject(error); // Handle other response errors
  }
);

export default instance;
