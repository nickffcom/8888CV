<template lang="">
  <ListInvitation
    :icon="icon"
    :data="data"
    :title="title"
    :subTitle="subTitle"
    :contentProp="content"
    @search="handleSearchUserRelated"
    placeholder="コンタクト名"
    @send="getRequest"
    :totalUserRelatedProp="totalUserRelated"
    :currentPageProp="currentPage"
    @changePage="updateCurrentPage"
    :isGuest="true"
  />
  <ConfirmModal
    :showModal="showSendRequest"
    @closeModal="closeModalSendRequest"
    @save="closeModalSendRequest"
    :showFooter="false"
    :widthCustom="700"
    title="コンタクトを依頼する"
  >
    <template #content>
      <SendInvitation
        :contentProp="contentSendInvitation"
        :fullName="fullName"
        @update="getData"
        @close="closeRequestUser"
        @accept="acceptRequestUser"
      />
    </template>
  </ConfirmModal>
</template>
<script>
import ConfirmModal from "../../components/ConfirmModal.vue";
import ListInvitation from "../../components/ListInvitation.vue";
import SendInvitation from "./SendInvitation.vue";
import { getListGuestRelated, inviteUser } from "../../apis/meeting/meeting";
import { inviteID } from "../../apis/invite/invite";
import { notification } from "ant-design-vue";
import { NOTIFICATION_MESSAGE } from "../../constants";
import { mapMutations, mapState } from "vuex";
export default {
  name: "Lobby",
  components: {
    ListInvitation,
    SendInvitation,
    ConfirmModal,
  },
  created() {
    this.getListRelated();
  },
  data() {
    return {
      content: "",
      title: "未追加検索",
      subTitle: "まだ追加登録していないメンバーを検索できます。",
      icon: [],
      showSendRequest: false,
      data: [],
      totalUserRelated: null,
      currentPage: 1,
      contentSendInvitation: "",
      idUserClick: null,
      sendEmail: null,
      fullName: null,
    };
  },
  computed: {
    ...mapState("meeting", ["id"]),
  },
  methods: {
    ...mapMutations("invitation", ["setShowModalInvitation"]),
    closeRequestUser(data) {
      this.showSendRequest = data;
      this.setShowModalInvitation(true);
    },
    acceptRequestUser(data) {
      let formData = new FormData();
      let userInfo = JSON.parse(localStorage.getItem("USER_INFO"));
      formData.append("message", this.contentSendInvitation);
      formData.append(`emails[0]`, this.sendEmail);
      formData.append(`invite_user_profile_id`, userInfo.profile_id);
      this.emitter.emit("isShowLoading", true);
      inviteUser(formData)
        .then((res) => {
          this.icon.forEach((item) => {
            if (item.id === this.idUserClick) {
              item.status = false;
            }
          });
          notification.success({
            message: NOTIFICATION_MESSAGE.INVITE_SUCCESS,
          });
          this.contentSendInvitation = "";
          this.emitter.emit("isShowLoading", false);
        })
        .catch((res) => {
          notification.error({ message: NOTIFICATION_MESSAGE.INVITE_FAIL });
          this.emitter.emit("isShowLoading", false);
        });
      this.showSendRequest = !data;
      this.setShowModalInvitation(true);
    },
    getData(data) {
      this.contentSendInvitation = data;
    },
    getListRelated(keyword) {
      this.emitter.emit("isShowLoading", true);
      const searchByContent = keyword
        ? `keyword=${keyword}`
        : `page=${this.currentPage}`;
      getListGuestRelated(searchByContent).then((res) => {
        this.data = res.data.data.map((item) => ({
          ...item,
          email: item?.email,
        }));
        this.data.forEach((item) => {
          this.icon.push({ id: item.id, status: true });
        });
        this.totalUserRelated = res?.data.total;
        this.emitter.emit("isShowLoading", false);
      });
    },
    updateCurrentPage(value) {
      this.currentPage = value;
      this.getListRelated();
    },
    handleSearchUserRelated(keyword) {
      this.getListRelated(keyword);
      this.currentPage = 1;
    },
    /**
     * show modal
     */
    getRequest(data) {
      this.fullName = data.data.email;
      this.idUserClick = data.data.id;
      this.sendEmail = data.data.email;
      this.showSendRequest = data.status;
      this.setShowModalInvitation(false);
    },
    /**
     * close modal
     */
    closeModalSendRequest() {
      this.setShowModalInvitation(true);
      this.showSendRequest = false;
    },
  },
};
</script>
<style lang="scss" scoped></style>
