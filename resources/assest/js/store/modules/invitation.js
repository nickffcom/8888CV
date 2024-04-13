import { INVITATION_CONTENT } from "../../constants/index";
const invitation = {
  namespaced: true,
  state: {
    activeKey: { index: 1, subIndex: 1 },
    activeListInvitation: 1,
    isActive: false,
    showModalInvitation: false,
    titleInvite: INVITATION_CONTENT.INVITATION,
  },
  mutations: {
    setActive(state, { index, subIndex }) {
      state.activeKey = { index, subIndex };
    },
    setActiveListInvitation(state, activeListInvitation) {
      state.activeListInvitation = activeListInvitation;
    },
    isActive(state, isActive) {
      state.isActive = isActive;
    },
    setTitleInvite(state, titleInvite) {
      state.titleInvite = titleInvite;
    },
    setShowModalInvitation(state, showModalInvitation) {
      state.showModalInvitation = showModalInvitation;
    },
  },
};

export default invitation;
