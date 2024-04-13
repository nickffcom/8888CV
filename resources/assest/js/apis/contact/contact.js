import axios from "../../utils/axios";

export const getListContact = () => {
  return axios.get(`contact/list`);
};
