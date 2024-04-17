import axios from "../../utils/axios";

export const createAccount = (data) => {
  return axios.post(`account/create`, data);
};

export const getEmailByID = (data) => {
  return axios.get(`account`, { params: data });
};

export const createUser = (data) => {
  return axios.post(`user/create`, data);
};

export const getUserDetail = () => {
  return axios.get(`user/detail`);
};

export const updateUserDetail = (data) => {
  return axios.post(`user/update`, data);
};

export const updateUserDetailByID = (id, data) => {
  return axios.post(`user/${id}/update`, data);
};

export const updateAccount = (data) => {
  return axios.post(`account/update`, data);
};

export const getUserByProfileID = (data) => {
  return axios.get(`user/detail/by/profile_id`, { params: data });
};

export const forgetPassword = (data) => {
  return axios.post("/password/forgot", data);
};

export const verifyTokenResetPassword = (data) => {
  return axios.post("/password/reset/verify", data);
};

export const resetPassword = (data) => {
  return axios.post("/password/reset", data);
};

export const getGuestByToken = (data) => {
  return axios.get("/guest/detail/by/token", { params: data });
};
