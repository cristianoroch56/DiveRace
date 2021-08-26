import axios from "axios";

//const API_URL = 'http://68.183.80.245/dive_race/wp-json/custom-api/v1';
//const API_URL = "https://diverace.wpengine.com/wp-json/custom-api/v1";
const API_URL = 'https://diverace.chillybin.biz/wp-json/custom-api/v1';


const AuthService = {
  login: function (email, password) {
    return axios.post(API_URL + "/wp_login_api", { username: email, password: password});
  },
  register: function (register_data) {
    return axios.post(API_URL + "/wp_singup_api", register_data);
  },
  forgotPassword: function (email) {
    return axios.post(API_URL + "/forgot_password", {useremail: email});
  },
  logout: function () {
    localStorage.removeItem("token");
  },
  getToken: function () {
    return localStorage.getItem("token");
  },
  saveToken: function (token) {
    localStorage.setItem("token", token);
  },
  authHeader: function () {
    return { Authorization: this.getToken() };
  },
  getVessels: function () {
    return axios.post(API_URL + "/get_vessel_data");
  },
};

export default AuthService;
