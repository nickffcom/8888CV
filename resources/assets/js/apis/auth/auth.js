import {
  LOCAL_STORAGE_ISLOGIN,
  LOCAL_STORAGE_REFRESHTOKEN,
  LOCAL_STORAGE_TOKEN,
  LOCAL_STORAGE_USER_INFO,
  LOGIN_ROUTE,
} from "../../constants/index";
import axios from "../../utils/axios";

/**
 * API login api/login
 * @param { username, password } params
 */
export const login = (params) => {
  return axios.post("/login", params);
};
export const logout = () => {
  axios
    .get("/logout")
    .then((response) => {
      localStorage.removeItem(LOCAL_STORAGE_USER_INFO);
      localStorage.removeItem(LOCAL_STORAGE_REFRESHTOKEN);
      localStorage.removeItem(LOCAL_STORAGE_TOKEN);
      localStorage.removeItem("fullName");
      localStorage.setItem(LOCAL_STORAGE_ISLOGIN, false);
      window.location.href = LOGIN_ROUTE;
    })
    .catch((error) => {
      console.log(error);
    });
};
