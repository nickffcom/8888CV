<template lang="">
  <div class="content d-flex mt-20 flex-column">
    <div class="d-flex align-items-center mt-15 mb-30 position-relative">
      <div>
        <img :src="SINGLE_USER" alt="" class="h-35-px" />
        <span class="fz-16 home__right-title mt-15 ml-15">参加者チャット</span>
        <img :src="QUESTION_MARK" alt="" class="h-25-px" />
      </div>
      <div class="home__right-line bg-color-grey2 flex-grow-1 mr-20 ml-20" />
      <slot name="iconAdd ">
        <div v-if="!hideButtonProp">
          <div
            class="d-flex align-items-center"
            v-if="editParticipantsProps.length != 0"
          >
            <div
              :class="[
                'd-flex',
                editParticipantsProps.length > 5 ? 'mr-10' : 'mr-20',
              ]"
            >
              <div v-for="(user, index) in editParticipantsProps" :key="index">
                <img
                  v-if="index <= 4"
                  :src="!user.avatar ? USER_IMAGE : user.avatar"
                  alt=""
                  srcset=""
                  class="h-30-px w-30-px list-avatar"
                  :style="{ zIndex: index }"
                />
              </div>
              <div v-if="editParticipantsProps.length > 5" class="plus-circle">
                +{{ editParticipantsProps.length - 5 }}
              </div>
            </div>
            <div class="d-flex cur-p" v-if="isRoleManager">
              <div
                class="home__right-icon text-white mr-10"
                @click="handleOpenAddParticipantModal"
              >
                +
              </div>
              <span
                class="text-global text-underline"
                @click="handleOpenLinkInviteModal"
              >
                メンバー追加
              </span>
            </div>
          </div>
        </div>
      </slot>
      <div
        class="invite-link-modal"
        v-if="isOpenLinkInviteModal"
        ref="inviteLinkModal"
      >
        <i
          class="fas fa-times text-grey1 fz-14 p-5 position-absolute top-3-px right-10-px cur-p"
          @click="closeModalLinkInvite"
        />
        <div class="fz-16 font-weight-600">ミーティングに招待する</div>
        <div>リンクを共有することで、ミーティングに招待できます。</div>
        <div class="d-flex bottom-link">
          <div class="invite-link-box" :style="{ opacity: isCopied ? 0.5 : 1 }">
            {{ linkMeeting }}
          </div>
          <div
            @click="onCopyMeetingLink"
            class="h-35-px status-primary-outline text-global bg-color-white radius-10-px cur-p w-140-px"
          >
            リンクコピー
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex ml-20">
      <slot name="listchat">
        <div
          @click="handleShowListChat"
          class="home__right-text w-120-px text-underline text-grey1 fz-14 d-flex align-items-center my-auto h-fit-content cur-p"
        >
          チャット一覧
        </div>
      </slot>
      <div
        v-if="dataProps.length == 0"
        class="fz-14 text-grey1 text-center w-100"
      >
        現在、参加者がいません。
      </div>
      <div v-if="dataProps.length != 0" class="d-flex flex-wrap gap-10 w-100">
        <div
          v-for="(item, index) in sortedUsersByDate"
          :key="index"
          :class="[
            'chat d-flex justify-content-start pl-10 align-items-center h-70-px ',
            { active: isActive(index) },
            { 'cur-p': item.checkEmail != currentEmail.email },
            { 'border-none': isActive(index) },
            extendClass,
          ]"
          @click="handleClick(item, index)"
        >
          <slot name="icon">
            <div
              v-if="currentParticipantRole"
              :class="[
                item.status == STATUS_LIST_CHATS.operator && 'd-none',
                isMatchedRoute('HomeByLinkID') && 'd-none',
              ]"
              class="position-absolute top-0-px right-10-px chat-icon-close"
              @click.stop="handleDelete(item, index)"
            >
              <i class="fas fa-times text-grey1 fz-14 p-5" />
            </div>
          </slot>
          <div
            :class="[
              'chat-status w-fit-content',
              {
                'chat-status-author': item.status == STATUS_LIST_CHATS.operator,
                'chat-status-seen': item.status == STATUS_LIST_CHATS.seen,
              },
            ]"
          >
            {{ item.status }}
          </div>
          <div>
            <Avatar
              :imgUrl="!item.avatar ? USER_IMAGE : item.avatar"
              externalClass="w-35-px h-35-px"
            />
          </div>
          <div class="d-flex flex-column align-items-start">
            <span class="ml-15 text-grey1 fz-12 chat-company">
              {{ item.company }}
            </span>
            <div class="d-flex">
              <div class="text-grey1 chat-name">
                {{ item.name }}
              </div>
              <div
                class="text-grey1 fz-14"
                v-if="item.position == POSITION.guest"
              >
                (ゲスト)
              </div>
              <div
                class="text-grey1 fz-14"
                v-else-if="item.position == POSITION.member"
              >
                (会員)
              </div>
            </div>
          </div>
          <div v-if="showNumber">
            <span
              v-if="item.number != 0"
              class="chat-number w-20-px d-flex align-items-center justify-content-center"
            >
              {{ item.number }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-40 w-100 d-flex align-items-center justify-content-center">
      <div
        v-if="!meetingDetail?.current_participant_id && linkID"
        class="joining-button d-flex align-items-center justify-content-center text-global cur-p"
        @click="handleJoinMeeting"
      >
        + このミーティングに参加する
      </div>
    </div>
  </div>
  <ConfirmModal
    :showModal="deleted"
    @closeModal="closeModalDelete"
    @save="closeModalDelete"
    :showFooter="false"
    :widthCustom="450"
  >
    <template #icon>
      <div class="text-center">
        <img :src="TRASH_IMAGE" class="w-55-px" />
      </div>
    </template>
    <template #content>
      <div class="fz-16 text-modal">削除しますか?</div>
    </template>
    <template #select>
      <div
        class="d-flex justify-content-between align-items-center mt-3 gap-180"
      >
        <div class="d-flex gap-50 mt-20">
          <div class="text-underline cur-p fz-16" @click="closeModalDelete">
            <span class="text-grey1 fz-14">キャンセル</span>
          </div>
          <button
            @click="handleAccept"
            class="min-w-120-px btn btn-primary h-40-px radius-12-px"
          >
            <span
              class="d-flex align-items-center justify-content-center gap-5"
            >
              <div>OK</div>
            </span>
          </button>
        </div>
      </div>
    </template>
  </ConfirmModal>

  <ConfirmModal
    :showModal="showAddParticipantModal"
    @closeModal="handleCloseAddParticipantModal"
    @save="handleCloseAddParticipantModal"
    :showFooter="false"
    :widthCustom="700"
    title="メンバー追加"
  >
    <template #content>
      <AddParticipants
        :participantsProps="editParticipantsProps"
        :linkMeetingProps="linkMeeting"
        :isCopiedProps="isCopied"
        :meetingDetailProps="meetingDetail"
        :isShowModal="showAddParticipantModal"
        @copyLink="onCopyMeetingLink"
        @close="handleCloseAddParticipantModal"
        @update="handleUpdateRoleParticipant"
        @goToEdit="handleOpenEditParticipantModal"
      />
    </template>
  </ConfirmModal>

  <ConfirmModal
    :showModal="showEditParticipantModal"
    @closeModal="handleClosEditParticipantModal"
    @save="handleClosEditParticipantModal"
    :showFooter="false"
    :widthCustom="700"
    title="メンバー追加"
  >
    <template #content>
      <EditParticipants
        :participantsProps="editParticipantsProps"
        :linkMeetingProps="linkMeeting"
        :isCopiedProps="isCopied"
        :meetingDetailProps="meetingDetail"
        :isShowModal="showAddParticipantModal"
        :suggestList="usersInfor"
        @close="handleClosEditParticipantModal"
        @update="handleUpdateRoleParticipant"
      />
    </template>
  </ConfirmModal>
</template>
<script>
import {
  STATUS_LIST_CHATS,
  POSITION,
  NOTIFICATION_MESSAGE,
} from "../../../constants/index";
import {
  USER_IMAGE,
  QUESTION_MARK,
  SINGLE_USER,
  TRASH_IMAGE,
} from "../../../constants/imageConst";
import { mapMutations, mapState } from "vuex";
import { formatTime } from "../../../utils/formatDateTime";
import ConfirmModal from "../../../components/ConfirmModal.vue";
import AddParticipants from "./AddParticipants.vue";
import EditParticipants from "./EditParticipants.vue";
import Avatar from "../../../components/Avatar.vue";
import {
  deleteEmail,
  deleteParticipant,
  getListUserRelated,
  updateMeeting,
  updateParticipantRole,
  joiningMeeting,
} from "../../../apis/meeting/meeting";
import { notification } from "ant-design-vue";

export default {
  name: "ListInforChat",
  components: { ConfirmModal, AddParticipants, EditParticipants, Avatar },
  props: {
    dataProps: { type: Array, default: () => [] },
    showNumber: { type: Boolean, default: true },
    hideButtonProp: { type: Boolean, default: false },
    extendClass: { type: String, default: "" },
    meetingDetailProps: { type: Object },
    isRoleManager: { type: Boolean, default: false },
  },
  created() {
    this.TRASH_IMAGE = TRASH_IMAGE;
    this.USER_IMAGE = USER_IMAGE;
    this.STATUS_LIST_CHATS = STATUS_LIST_CHATS;
    this.POSITION = POSITION;
    this.QUESTION_MARK = QUESTION_MARK;
    this.SINGLE_USER = SINGLE_USER;
    this.formatTime = formatTime;
    this.meetingDetail = this.meetingDetailProps;
    this.currentEmail = JSON.parse(localStorage.getItem("USER_INFO"));
    this.linkMeeting = window.location.href;
    this.editParticipantsProps = this.dataProps.filter(
      (item) =>
        typeof item.isDeletedCreator !== "undefined" || !item?.isDeletedCreator
    );
    this.handleGetRelatedUser();
    this.linkID = this.$route.params.link_id;
  },
  watch: {
    meetingDetailProps(newVal) {
      this.meetingDetail = newVal;
      this.linkMeeting =
        window.location.host +
        "/meeting/" +
        newVal.link_id +
        "/" +
        newVal?.user?.profile_id;
    },
    dataProps: {
      handler: function (newVal, oldVal) {
        this.editParticipantsProps = newVal.filter(
          (item) =>
            typeof item.isDeletedCreator !== "undefined" ||
            !item?.isDeletedCreator
        );
      },
      deep: true,
    },
    usersInfor(newVal) {
      this.usersInfor = newVal;
    },
  },
  data() {
    return {
      deleted: false,
      participantStatus: null,
      indexDeleted: null,
      currentEmail: "",
      meetingDetail: {},
      linkMeeting: null,
      isCopied: false,
      isOpenLinkInviteModal: false,
      showAddParticipantModal: false,
      showEditParticipantModal: false,
      editParticipantsProps: [],
      linkID: null,
      usersInfor: null,
    };
  },
  emits: [
    "add",
    "clicked",
    "delete",
    "showListChat",
    "deletedParticipant",
    "updatedRole",
    "getListParticipant",
  ],
  computed: {
    ...mapState("meeting", ["checkActive"]),
    sortedUsersByDate() {
      let createdIDItems = this.dataProps.filter((item) => item.createdID);
      let otherItems = this.dataProps
        .filter((item) => !item.createdID)
        .sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

      return [...createdIDItems, ...otherItems];
    },
    currentParticipantRole() {
      let participantRole = null;
      if (this.meetingDetailProps.current_participant_id) {
        this.dataProps.forEach((item) => {
          if (item.id == this.meetingDetailProps.current_participant_id) {
            participantRole = item.role;
          }
        });
      }
      return participantRole;
    },
  },
  methods: {
    ...mapMutations("meeting", ["setCheckActive"]),
    /**
     * Update participant
     */
    async handleUpdateRoleParticipant(emiterData) {
      this.emitter.emit("isShowLoading", true);
      const dataUpdate = {
        meeting_id: this.meetingDetail?.id,
        data_update: emiterData.dataUpdate,
      };

      const promises = [];
      if (
        emiterData.dataNewParticipant &&
        emiterData.dataNewParticipant.length !== 0
      ) {
        let formData = new FormData();
        for (let i = 0; i < emiterData.dataNewParticipant.length; i++) {
          formData.append(
            `emails[${i}]`,
            emiterData.dataNewParticipant[i].checkEmail
          );
        }

        promises.push(
          updateMeeting(this.meetingDetail?.id, formData).catch((error) => {
            console.log(error);
          })
        );
      }

      if (emiterData.dataDelete && emiterData.dataDelete.length !== 0) {
        const dataDelete = {
          meeting_id: this.meetingDetail?.id,
          participant_ids: emiterData.dataDelete,
        };

        promises.push(
          deleteParticipant(dataDelete).catch((error) => {
            console.log(error);
          })
        );
      }

      try {
        // Wait for both delete and update operations to complete
        await Promise.all(promises);

        await updateParticipantRole(dataUpdate);
        notification.success({
          message: NOTIFICATION_MESSAGE.UPDATE_SUCCESS,
        });
        this.$emit("updatedRole");
      } catch (error) {
        notification.error({ message: NOTIFICATION_MESSAGE.UPDATE_FAIL });
        console.log(error);
      } finally {
        this.emitter.emit("isShowLoading", false);
      }
    },
    /**
     * Open add participant modal
     */
    handleOpenAddParticipantModal() {
      this.showAddParticipantModal = true;
    },

    /**
     * Open close participant modal
     */
    handleCloseAddParticipantModal() {
      this.showAddParticipantModal = false;
    },

    /**
     * Open open edit participant modal
     */
    handleOpenEditParticipantModal() {
      this.showAddParticipantModal = false;
      this.showEditParticipantModal = true;
    },

    /**
     * Open close edit participant modal
     */
    handleClosEditParticipantModal() {
      this.showAddParticipantModal = true;
      this.showEditParticipantModal = false;
    },

    /**
     *  Check if the route name matches the route being displayed.
     *  @returns {boolean} - Returns true if the route name matches the current route, false otherwise.
     */
    isMatchedRoute(routeName) {
      return this.$route.matched.some(({ name }) => {
        return name == routeName;
      });
    },

    /**
     * Open list chat
     */
    handleShowListChat() {
      this.$emit("showListChat");
    },
    isActive(index) {
      return index == this.checkActive ? "active" : "";
    },

    /**
     * Delete participant
     */
    handleDelete(data, index) {
      this.participantStatus = data;
      this.indexDeleted = index;
      this.deleted = true;
    },

    /**
     * Close delete participant modal
     */
    closeModalDelete() {
      this.deleted = false;
    },

    /**
     * Accept delete participant
     */
    handleAccept() {
      this.$emit("delete", this.indexDeleted);
      this.emitter.emit("isShowLoading", true);
      deleteEmail(
        this.participantStatus?.meeting_id,
        this.participantStatus?.id
      )
        .then((res) => {
          this.emitter.emit("isShowLoading", false);
        })
        .catch((error) => {
          console.log(error);
          this.emitter.emit("isShowLoading", false);
        });
      this.deleted = false;
    },

    /**
     * Close link invite modal
     */
    closeModalLinkInvite() {
      this.isOpenLinkInviteModal = false;
    },

    /**
     * Copy meeting link detail
     */
    async onCopyMeetingLink() {
      const copyLink = this.linkMeeting;
      // Navigator clipboard api needs a secure context (https)
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(copyLink);
        this.isCopied = true;
        notification.info({
          message: NOTIFICATION_MESSAGE.COPY_SUCCESS,
        });
        setTimeout(() => {
          this.isCopied = false;
        }, 2000);
      } else {
        // Use the 'out of viewport hidden text area' trick
        const textArea = document.createElement("textarea");
        textArea.value = copyLink;

        // Move textarea out of the viewport so it's not visible
        textArea.style.position = "absolute";
        textArea.style.left = "-999999px";

        document.body.prepend(textArea);
        textArea.select();
        try {
          document.execCommand("copy");
          this.isCopied = true;
          setTimeout(() => {
            this.isCopied = false;
          }, 2000);
        } catch (error) {
          console.error(error);
        } finally {
          textArea.remove();
        }
      }
    },

    /**
     * Open link invite modal
     */
    handleOpenLinkInviteModal() {
      this.isOpenLinkInviteModal = true;
    },

    /**
     * Handle join as a member in meeting
     */
    async handleJoinMeeting() {
      this.emitter.emit("isShowLoading", true);

      try {
        const data = {
          link_id: this.linkID,
        };
        await joiningMeeting(data);
        this.$emit("getListParticipant");
        this.emitter.emit("isShowLoading", false);
      } catch (error) {
        this.emitter.emit("isShowLoading", false);

        console.log(error);
      }
    },
    handleClick(item, index) {
      if (!item.name?.includes("(ゲスト)")) {
        const userInfo = JSON.parse(localStorage.getItem("USER_INFO"));
        if (userInfo) {
          const email = userInfo.email;
          const tempID = item.data.participants.find((i) => i.email == email);
          if (email != item.checkEmail) {
            this.setCheckActive(index);
            this.$emit("clicked", {
              data: item,
              senderID: tempID ? tempID.id : null,
            });
          } else {
            if (!tempID) {
              notification.error({
                message: NOTIFICATION_MESSAGE.CREATOR_WHITOUT_CHAT,
              });
            }
          }
        }
      } else {
        notification.error({ message: NOTIFICATION_MESSAGE.GUEST_ERROR_CHAT });
      }
    },
    /**
     * get related user for recommend list
     */
    handleGetRelatedUser() {
      const props = "";
      getListUserRelated(props).then((res) => {
        this.usersInfor = res.data.data;
      });
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";
.list-avatar {
  margin-right: -12px;
  border-radius: 50px;
  border: 2px solid $white;
  object-fit: cover;
}
.plus-circle {
  width: 30px;
  height: 30px;
  background-color: $grey1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: $white;
  z-index: 6;
  border-radius: 50px;
  border: 2px solid $white;
  font-size: 12px;
  padding-top: 2px;
}

.invite-link-modal {
  position: absolute;
  min-height: 150px;
  max-width: 580px;
  border: 2px solid #e6e6e6;
  width: 100%;
  right: 0px;
  border-radius: 12px;
  top: 40px;
  background: white;
  z-index: 10;
  padding: 15px;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
}

.bottom-link {
  align-items: center;

  @include res_max-width(897) {
    flex-direction: column;
    align-items: normal;
  }
}
.joining-button {
  height: 40px;
  width: 70%;
  border: 2px dotted #eee;
  border-radius: 10px;
}
</style>
