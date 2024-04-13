import axios from "../utils/axios";

/**
 * API get File
 * @param  path
 */
export const getFile = (params) => {
  return axios.get("/file/" + params, {
    responseType: "blob",
  });
};
