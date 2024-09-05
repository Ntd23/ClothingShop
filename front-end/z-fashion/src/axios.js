import axios from "axios";
axios.defaults.baseURL = "http://localhost:8000/api/";
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const auth = axios.create({
  baseURL: "http://localhost:8000/api/",
});

auth.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      auth.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);
auth.defaults.withCredentials = true;
auth.defaults.withXSRFToken = true;
export default axios;
export { auth };
