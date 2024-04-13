import { LOCAL_STORAGE_TOKEN } from "../constants";

export default {
  get(name) {
    return localStorage.getItem(name) || "";
  },
  set(name, token) {
    localStorage.setItem(name, token);
  },
  remove(name) {
    localStorage.removeItem(name);
  },
  reset(name) {
    localStorage.removeItem(name);
  },
  getAuth() {
    return {
      authorization: "Bearer " + this.get(LOCAL_STORAGE_TOKEN),
    };
  },
};
