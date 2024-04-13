<template lang="">
  <ListInvitation
    :data="data"
    :title="title"
    :subTitle="subTitle"
    @update="getValue"
    :contentProp="content"
    @send="getRequest"
    @search="handleSearchUserRelated"
    placeholder="コンタクト名"
    :totalUserRelatedProp="totalUserRelated"
    :currentPageProp="currentPage"
    @changePage="updateCurrentPage"
    :isMixed="true"
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
import { getListContact } from "../../apis/contact/contact";
import { mapMutations, mapState } from "vuex";
import { inviteID } from "../../apis/invite/invite";
import { NOTIFICATION_MESSAGE } from "../../constants";
import { notification } from "ant-design-vue";
export default {
  name: "ContactList",
  components: {
    ListInvitation,
    ConfirmModal,
    SendInvitation,
  },
  created() {
    this.getList();
  },
  data() {
    return {
      content: "",
      title: "知り合い検索",
      subTitle: "すでに登録している知り合いを検索できます。",
      icon: [],
      data: [],
      totalUserRelated: null,
      currentPage: 1,
      showSendRequest: false,
      contentSendInvitation: null,
      idUserClick: null,
      idGuestClick: null,
      sendEmail: null,
      fullName: null,
      checkUserGuest: false,
    };
  },
  watch: {
    content(newVal) {
      if (!newVal) {
        this.getList();
      }
    },
  },
  computed: {
    ...mapState("meeting", ["id", "detailParticipants"]),
  },
  methods: {
    ...mapMutations("invitation", ["setShowModalInvitation"]),

    /**
     * show modal
     */
    getRequest(data) {
      this.fullName = data.data.user_contactor_id
        ? data.data.userName
        : data.data.guest_contactor.email;
      this.idUserClick = data.data.user_contactor_id;
      this.idGuestClick = data.data.guest_contactor_id;
      this.sendEmail = data.data.email;
      this.showSendRequest = data.status;
      this.setShowModalInvitation(false);
    },

    closeRequestUser(data) {
      this.showSendRequest = data;
      this.setShowModalInvitation(true);
    },
    acceptRequestUser(data) {
      let params = null;
      if (this.idUserClick)
        params = {
          user_id: this.idUserClick,
          message: this.contentSendInvitation,
        };
      else
        params = {
          guest_id: this.idGuestClick,
          message: this.contentSendInvitation,
        };
      this.emitter.emit("isShowLoading", true);
      inviteID(this.id, params)
        .then((res) => {
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
    /**
     * close modal
     */
    closeModalSendRequest() {
      this.setShowModalInvitation(true);
      this.showSendRequest = false;
    },
    getValue(data) {
      this.content = data;
    },
    updateCurrentPage(value) {
      this.currentPage = value;
      this.getList();
    },
    handleSearchUserRelated() {
      this.getList(this.content);
      this.currentPage = 1;
    },
    getList(keyword) {
      this.emitter.emit("isShowLoading", true);
      const params = keyword
        ? { keyword: keyword }
        : { page: this.currentPage };
      getListContact(params)
        .then((res) => {
          this.data = res.data.data.map((item) => ({
            ...item,
            id: item?.id,
            companyName:
              item?.guest_contactor == null
                ? item?.user_contactor?.company_name
                : "",
            userName:
              item?.guest_contactor == null
                ? `${item?.user_contactor?.last_name} ${item?.user_contactor?.first_name}`
                : "",
            code:
              item?.guest_contactor == null
                ? item?.user_contactor?.profile_id
                : "",
            email:
              item?.guest_contactor == null
                ? item?.user_contactor?.email
                : item?.guest_contactor?.email,
          }));

          this.data.forEach((item) => {
            this.icon.push({
              id: item?.user_contactor_id,
              guest_id: item?.guest_contactor_id,
              status: true,
            });
          });
          this.detailParticipants.forEach((i) => {
            this.icon.forEach((item) => {
              if (item.id && i.user_id == item?.id) {
                item.status = false;
              } else if (item.guest_id && i.guest_id == item?.guest_id) {
                item.status = false;
              }
            });
          });

          this.totalUserRelated = res?.data.total;
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          this.emitter.emit("isShowLoading", false);
          console.log("there was an error", err);
        });
    },
  },
};
</script>
<style lang="scss" scoped></style>
