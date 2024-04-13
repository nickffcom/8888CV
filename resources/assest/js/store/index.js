import { createStore } from "vuex";
import account from "./modules/account";
import invitation from "./modules/invitation";
import meeting from "./modules/meeting";

export default createStore({
  modules: {
    account,
    invitation,
    meeting,
  },
});
