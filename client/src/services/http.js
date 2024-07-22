import axios from 'axios';
const axiosInstance = axios.create({
    baseURL:'http://localhost:8000/api',
    headers: {
        'Content-type': 'application/json',
        'Authorization': process.env.VUE_APP_AUTH_TOKEN,
    }
});

export default axiosInstance;