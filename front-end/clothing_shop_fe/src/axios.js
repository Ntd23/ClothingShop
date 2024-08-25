import axios from "axios";

const instance= axios.create();
instance.interceptors.request.use(config=> {
  config.baseURL= 'http://localhost:8000/api/';
  const token= localStorage.getItem('token');
  if(token) {
    config.headers.Authorization= `Bearer ${token}`;
  }
  return config;
}, error=> {
  return Promise.reject(error);
});

axios.defaults.baseURL= 'http://localhost:8000/api';

export default axios;
