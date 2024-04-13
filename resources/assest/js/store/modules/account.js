import { getFile } from "../../apis/getFile";
const account = {
  namespaced: true,
  state: {
    activeKey: { index: 1, subIndex: 1 },
    isActive: false,
    indexChoose: 0,
    indexChoosingStep: 0,
    information: { email: null, password: null },
    accountDetail: {},
  },
  mutations: {
    setActive(state, { index, subIndex }) {
      state.activeKey = { index, subIndex };
    },
    async setAccountDetail(state, data) {
      this.emitter.emit("isShowLoading", true);
      const promises = [];
      if (data.avatar != null) {
        promises.push(
          getFile(data.avatar)
            .then((result) => {
              data.avatar = URL.createObjectURL(result.data);
            })
            .catch((error) => {
              console.log(error);
            })
        );
      }
      if (data.small_avatar != null) {
        promises.push(
          getFile(data.small_avatar)
            .then((result) => {
              data.small_avatar = URL.createObjectURL(result.data);
            })
            .catch((error) => {
              console.log(error);
            })
        );
      }
      if (data.profile_image != null) {
        promises.push(
          getFile(data.profile_image)
            .then((result) => {
              data.profile_image = URL.createObjectURL(result.data);
            })
            .catch((error) => {
              console.log(error);
            })
        );
      }

      try {
        await Promise.all(promises);
        state.accountDetail = data;
      } catch (error) {
        console.log(error);
      }
    },
    isActive(state, isActive) {
      state.isActive = isActive;
    },
    setIndexChoose(state, indexChoose) {
      state.indexChoose = indexChoose;
    },
    setIndexChoosingStep(state, indexChoosingStep) {
      state.indexChoosingStep = indexChoosingStep;
    },
    setInforRegister(state, { email, password }) {
      state.information = { email, password };
    },
  },
};

export default account;
