<template lang="">
  <ListInvitation
    :data="data"
    :title="title"
    :subTitle="subTitle"
    :contentProp="content"
    @search="handleSearchUserRelated"
    placeholder="お名前、ChatMeeting ID、 メールアドレスを入力"
    @send="getRequest"
    :totalUserRelatedProp="totalUserRelated"
    :currentPageProp="currentPage"
    @changePage="updateCurrentPage"
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
import { mapMutations, mapState } from "vuex";
import { getListUserRelated } from "../../apis/meeting/meeting";
import { inviteID } from "../../apis/invite/invite";
import { notification } from "ant-design-vue";
import { NOTIFICATION_MESSAGE } from "../../constants";
export default {
  name: "FindingAcquaintances",
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
      contentSendInvitation: null,
      title: "知り合い検索",
      subTitle: "ChatMeeting に登録している知り合いを検索できます。",
      showSendRequest: false,
      data: [],
      totalUserRelated: null,
      currentPage: 1,
      idUserClick: null,
      sendEmail: null,
      fullName: null,
    };
  },
  computed: {
    ...mapState("meeting", ["id", "detailParticipants"]),
  },
  methods: {
    ...mapMutations("invitation", ["setShowModalInvitation"]),
    closeRequestUser(data) {
      this.showSendRequest = data;
      this.setShowModalInvitation(true);
    },
    acceptRequestUser(data) {
      const params = {
        user_id: this.idUserClick,
        message: this.contentSendInvitation,
      };
      this.emitter.emit("isShowLoading", true);
      inviteID(this.id, params)
        .then((res) => {
          notification.success({
            message: NOTIFICATION_MESSAGE.INVITE_SUCCESS,
          });
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
      let temp = [];
      getListUserRelated(searchByContent).then((res) => {
        this.data = res.data.data.map((item) => ({
          ...item,
          id: item?.id,
          companyName: item?.company_name,
          userName: `${item?.last_name} ${item?.first_name}`,
          code: item?.profile_id,
        }));
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
      this.fullName = data.data.last_name + " " + data.data.first_name;
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
