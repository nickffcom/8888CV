import axios from "../../utils/axios";

export const inviteEmail = (meetingID, data) => {
  return axios.post(`meeting/${meetingID}/invite`, data);
};

export const inviteID = (meetingID, data) => {
  return axios.post(`meeting/${meetingID}/invite/by_id`, data);
};

export const getMeetingLink = (dataID) => {
  return axios.get(`meeting/${dataID}/get/link`);
};

export const inviteLink = () => {
  return axios.get(`/invite/link`);
};

export const addContact = (data) => {
  return axios.post(`/contact/add`, data);
};

export const getUserByProfileID = (params) => {
  return axios.get(`/user/detail/by/profile_id`, { params });
};
