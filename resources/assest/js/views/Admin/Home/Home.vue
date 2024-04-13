<template lang="">
  <div class="d-flex home create-meeting">
    <ListMeeting
      :data="data"
      :showDate="false"
      :isFontWeight="true"
      @clicked="getInforDetailMeeting"
      :meetingIdProp="meetingId"
    />
    <div v-if="id != null" class="home__right ml-40">
      <!-- Header -->
      <HomeHeader
        linkCopy="打ち合わせリンクコピーする"
        :avatar="!creatorAvatar ? USER_IMAGE : creatorAvatar"
        :company="companyName"
        :name="fullName"
        :date="date"
        :time="time"
        :meetingDetailProps="meetingDetail"
      />
      <InforMeeting
        :hideButtonProp="
          this.currentParticipantRole !== PARTICIPANT_ROLE.manager
        "
        :data="dataInforMeeting"
        @show="showCancelMeeting"
        @edit="goToEdit"
      />
      <!-- todo -->
      <Todo
        :data="dataTodo"
        @getValue="setValueTodo"
        :hideButtonProp="
          this.currentParticipantRole !== PARTICIPANT_ROLE.manager
        "
      />
      <!--participants -->
      <ListInforChat
        :dataProps="dataStatus"
        :meetingDetailProps="meetingDetail"
        @delete="handleDeleted"
        @add="handleAdd"
        @clicked="handleGoToChat"
        @showListChat="handleShowListChat"
        @deletedParticipant="handleFetchNewParticipant"
        @updatedRole="handleFetchNewParticipant"
        :isRoleManager="currentParticipantRole == PARTICIPANT_ROLE.manager"
        @getListParticipant="getMeetingByLinkID"
      />
      <!-- detail chat -->
      <div class="mt-30">
        <div class="d-flex">
          <img :src="MESSAGE" alt="" srcset="" class="h-30-px mr-20" />
          <span class="fz-16 home__right-title mt-5">チャット詳細</span>
        </div>
        <div
          v-if="checkActive != null || showListChat == true"
          class="mt-20 participant"
        >
          <div>
            <Avatar
              v-if="isChatAll"
              class="h-35-px w-35-px"
              :img-url="currentUserAvatar ?? USER_IMAGE"
            />
            <Avatar
              v-else
              class="h-35-px w-35-px"
              :img-url="currentUserAvatarChat ?? USER_IMAGE"
            />
          </div>
          <div class="d-flex flex-column ml-20" v-if="showListChat == false">
            <span
              v-if="receiverCompany != null"
              class="participant__company fz-12 text-grey1"
            >
              {{ receiverCompany }}
            </span>
            <span v-else class="participant__company fz-12 text-grey1">
              会社データなし
            </span>
            <span
              v-if="receiverName != null"
              class="participant__name text-grey1"
            >
              {{ receiverName }}
            </span>
            <span v-else class="participant__name text-grey1">
              氏名データなし
            </span>
          </div>
          <div
            class="participant__content d-flex flex-column ml-30 flex-grow-1"
          >
            <div class="d-flex">
              <span v-if="timeAttached != null" class="text-grey1 fz-15">
                {{ timeAttached }}
              </span>
              <span v-else class="text-grey1 fz-15">時間なし</span>
            </div>
            <span class="text-grey1">
              打ち合わせ前の質問などは下記のチャットへメッセージしてください。
            </span>
            <div class="d-flex gap-10 mt-10 flex-wrap">
              <div v-for="(item, index) in displayedImages" :key="index">
                <div
                  :class="[
                    { 'file-user': index === 4 && remainingImages >= 1 },
                  ]"
                >
                  <PdfPreview
                    height="150"
                    :src="item?.file_path"
                    v-if="
                      item?.mime_type === 'application/pdf' ||
                      item?.mime_type === 'pdf'
                    "
                    class="h-150-px w-150-px image-item"
                  />
                  <img
                    v-else
                    :src="item?.file_path"
                    alt=""
                    srcset=""
                    class="h-150-px w-150-px image-item"
                  />
                  <div
                    v-if="index === 4 && remainingImages >= 1"
                    @click="showImageChat"
                    class="fz-24 overlay-text text-white cur-p"
                  >
                    すべてを見る
                  </div>
                  <div
                    v-if="index === 4 && remainingImages >= 1"
                    class="image-length fz-30"
                  >
                    + {{ remainingImages }}
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-20">
              <div class="d-flex flex-grow-1 justify-content-between">
                <span class="text-black1">チャット質問</span>
                <div v-if="!isChatAll">
                  <span
                    v-if="
                      renderUnreadMess != 0 && renderUnreadMess != undefined
                    "
                    class="participant__number h-20-px w-20-px d-flex align-items-center justify-content-center"
                  >
                    {{ renderUnreadMess }}
                  </span>
                </div>

                <div class="participant__spacer" />
                <div @click="handleClick">
                  <img
                    :src="isToggle ? ARROW_UP : ARROW_DOWN"
                    alt=""
                    :class="[
                      'toggle-sidebar-icon',
                      isToggle && 'logo-xl-btn-collapse',
                      'h-25-px participant__arrow',
                    ]"
                  />
                </div>
              </div>
            </div>
            <!-- chat -->
            <div
              ref="chatBody"
              :class="[
                'participant-chat__body h-500-px fz-16 overflow-auto mr-8 d-flex flex-column',
                isToggle && 'd-none',
              ]"
            >
              <div v-if="conversationDetail.length > 0">
                <div
                  class="participant-chat__content"
                  v-for="(chat, key) in conversationDetail"
                  :key="chat.id"
                >
                  <div
                    class="text-grey1 participant-chat__date mt-10 d-flex flex-row justify-content-center align-items-center"
                    v-if="
                      formatDate(conversationDetail[key]?.created_at) !=
                      formatDate(conversationDetail[key + 1]?.created_at)
                    "
                  >
                    {{ renderDate(formatDate(chat.created_at)) }}
                  </div>
                  <div class="participant-chat__chat-screen pt-30 pr-30 pl-30">
                    <div
                      class="participant-chat__inner-chat d-flex"
                      v-if="chat.sender_id != senderId"
                    >
                      <Avatar
                        class="h-35-px w-35-px"
                        :img-url="
                          getReceiverAvatar(chat.sender_id) ?? USER_IMAGE
                        "
                      />
                      <div class="participant-chat__message">
                        <div class="d-flex flex-column ml-20">
                          <span
                            v-if="receiverCompany != null"
                            class="participant__company fz-12 text-grey1"
                          >
                            {{ receiverCompany }}
                          </span>
                          <span
                            v-else-if="chat.company && receiverCompany == null"
                            class="participant__company fz-12 text-grey1"
                          >
                            {{ chat.company }}
                          </span>
                          <span
                            v-else-if="!chat.company && receiverCompany == null"
                            class="participant__company fz-12 text-grey1"
                          >
                            {{ chat.email }}
                          </span>
                          <span
                            v-else
                            class="participant__company fz-12 text-grey1"
                          >
                            会社データなし
                          </span>
                          <div class="d-flex">
                            <span
                              v-if="receiverName != null"
                              class="participant__name text-grey1 fz-14"
                            >
                              {{ receiverName }}
                            </span>
                            <span
                              v-else-if="chat.name && receiverName == null"
                              class="participant__company fz-12 text-grey1"
                            >
                              {{ chat.name }}
                            </span>
                            <span
                              v-else
                              class="participant__name text-grey1 fz-14"
                            >
                              氏名データなし
                            </span>
                            <div
                              class="participant-chat__time fz-14 text-grey1 ml-5"
                            >
                              {{ formatTime(chat.created_at) }}
                            </div>
                          </div>
                        </div>
                        <div
                          class="participant-chat__txt-sender p-10 fz-14 ml-20 text-white d-inline-block"
                          v-if="chat.message"
                        >
                          {{ chat.message }}
                        </div>

                        <figure
                          class="participant-chat__file"
                          v-if="chat?.conversation_files"
                        >
                          <div
                            v-for="(file, key) in chat?.conversation_files"
                            :key="key"
                            class="w-150-px h-150px mb-5"
                          >
                            <PdfPreview
                              height="150"
                              :src="file?.file_path"
                              v-if="checkPDF(file)"
                              class="h-150-px w-150-px image-item"
                            />
                            <img
                              v-else
                              :src="file?.file_path"
                              alt=""
                              srcset=""
                              class="h-150-px w-150-px image-item"
                            />
                          </div>
                        </figure>
                      </div>
                    </div>
                    <!-- sender -->
                    <div
                      class="participant-chat__user-chat d-flex flex-row-reverse"
                      v-if="chat.sender_id == senderId"
                    >
                      <Avatar
                        class="h-35-px w-35-px"
                        :img-url="currentUserAvatar ?? USER_IMAGE"
                      />
                      <div class="participant-chat__message mr-20">
                        <div class="d-flex ml-20 justify-content-end">
                          <div
                            class="participant-chat__time fz-14 text-grey1 ml-5"
                          >
                            {{ formatTime(chat.created_at) }}
                          </div>

                          <span class="participant__name text-grey1 fz-14">
                            自分
                          </span>
                        </div>
                        <div class="d-flex justify-content-end">
                          <div
                            class="participant-chat__txt-receiver p-10 fz-14 text-white d-inline-block"
                            v-if="chat.message"
                          >
                            {{ chat.message }}
                          </div>
                        </div>
                        <figure
                          class="participant-chat__file"
                          v-if="chat?.conversation_files"
                        >
                          <div
                            v-for="(file, subIndex) in chat?.conversation_files"
                            :key="subIndex"
                            class="w-150-px h-150px mb-5"
                          >
                            <PdfPreview
                              height="150"
                              :src="file?.file_path"
                              v-if="checkPDF(file)"
                              class="h-150-px w-150-px image-item"
                            />
                            <img
                              v-else
                              :src="file?.file_path"
                              srcset=""
                              class="h-150-px w-150-px image-item"
                            />
                          </div>
                        </figure>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-auto text-black1" v-else>
                メッセージはありません。
              </div>
            </div>
            <div class="d-flex overflow-auto">
              <div class="d-flex flex-wrap" v-if="upload_files.length > 0">
                <div
                  v-for="(file, key) in upload_files"
                  :key="key"
                  class="p-3 text-white"
                >
                  <div
                    v-if="
                      file.type === 'pdf' || file.type === 'application/pdf'
                    "
                    class="w-100-px h-100-px bg-color-primary pl-10 radius-5-px position-relative"
                  >
                    <i class="fa-solid fa-file-lines fz-20 pt-20" />
                    <div
                      class="text-overflow-ellipsis white-space-nowrap overflow-hidden font-weight-bold"
                    >
                      {{ file.file.name }}
                    </div>
                    <div class="fz-12">{{ file.file.size }} KB</div>
                    <i
                      class="c-chat__remove-file-icon fa-solid fa-xmark w-15-px h-15-px position-absolute cur-p d-flex align-items-center bg-color-grey3 radius-10-px"
                      @click="removeUploadFile(key)"
                    />
                  </div>
                  <div
                    v-else-if="
                      file.type === 'image' ||
                      file.type === 'image/png' ||
                      file.type === 'image/jpg' ||
                      file.type === 'image/gif' ||
                      file.type === 'image/jpeg'
                    "
                    class="p-3 text-white"
                  >
                    <figure
                      class="c-chat__image-preview w-100-px h-100-px m-0 position-relative"
                    >
                      <img class="preview w-100 h-100" :src="file.file" />
                      <i
                        class="c-chat__remove-file-icon fa-solid fa-xmark w-15-px h-15-px position-absolute cur-p text-white d-flex align-items-center bg-color-grey3 radius-10-px"
                        @click="removeUploadFile(key)"
                      />
                    </figure>
                  </div>
                </div>
              </div>
            </div>
            <!-- input chat -->
            <div
              v-if="!isChatAll"
              ref="inputChat"
              :class="[
                'participant-chat__input position-relative pt-10',
                isToggle ? 'd-none' : 'd-flex',
                showListChat == true && 'd-none',
              ]"
            >
              <div class="participant-chat__action d-flex">
                <input
                  type="file"
                  ref="imgagePDF"
                  multiple="multiple"
                  accept="image/png, image/gif, image/jpeg, application/pdf"
                  class="d-none"
                  @change="uploadFile($event)"
                />
              </div>

              <textarea
                class="participant-chat__input-chat h-30-px fz-14 w-100 radius-10-px pl-10 pr-10"
                spellcheck="false"
                placeholder="トーク入力をお願いします。"
                ref="chatContent"
                @keydown.enter.shift.ctrl.exact.prevent="dropLine"
                @keydown.enter.exact.prevent="handleSendChat"
                @keyup="resizeInput"
              />

              <figure class="participant-chat__icon-send w-30-px ml-10 cur-p">
                <img
                  :src="DOCUMENT"
                  alt=""
                  srcset=""
                  class="h-35-px"
                  @click="$refs.imgagePDF.click()"
                />
              </figure>
              <figure class="participant-chat__icon-send w-30-px ml-10 cur-p">
                <img :src="PAPER_PLANE" alt="" @click="handleSendChat" />
              </figure>
            </div>
          </div>
        </div>
        <div v-else class="text-grey1 fz-14 p-140 text-center">
          参加者をクリックしてチャット確認お願いします。
        </div>
      </div>
    </div>
    <div v-else class="w-100">
      <div
        class="d-flex flex-column justify-content-center error-layout not-found fullscreen text-center"
      >
        <div class="icon-wrapper">
          <img
            :src="NOT_FOUND"
            alt="404"
            class="icon w-140-px d-block mx-auto"
          />
        </div>
        <div class="error-title font-weight-bold text-center fz-20">
          <div>本日のミーティングは作成されていません。</div>
        </div>
        <button
          @click="handleGoToCreateMeeting"
          class="d-flex justify-content-center align-items-center w-100 max-w-300-px fz-15 pp-button action-button positive md flex primary draft-0"
        >
          ミーティング作成
        </button>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <ConfirmModal
    :showModal="showModalCancelMeeting"
    @closeModal="closeCancelMeeting"
    @save="closeCancelMeeting"
    :showFooter="false"
    :widthCustom="450"
  >
    <template #icon>
      <div class="text-center">
        <img :src="TRASH_IMAGE" class="w-55-px" />
      </div>
    </template>
    <template #content>
      <div class="fz-16 text-modal">本当にミーティングを削除しますか?</div>
    </template>
    <template #select>
      <div
        class="d-flex justify-content-between align-items-center mt-3 gap-180"
      >
        <div class="d-flex gap-50 mt-20">
          <div class="text-underline cur-p fz-16" @click="closeCancelMeeting">
            <span class="text-grey1 fz-14">キャンセル</span>
          </div>
          <button
            @click="saveCancelMeeting"
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
  <div class="modal d-flex flex-column" v-if="showListImageChat">
    <div class="h-100 overflow-y-auto">
      <div class="modal-overlay" @click="closeImageChat" />
      <div class="modal-body d-flex flex-wrap h-150-px">
        <div
          v-for="(item, index) in totalImages"
          :key="index"
          style="z-index: 1001; height: 150px"
          class="modal-image"
        >
          <PdfPreview
            height="150"
            :src="item?.file_path"
            v-if="
              item.mime_type === 'application/pdf' || item.mime_type === 'pdf'
            "
          />
          <img
            v-else
            :src="item?.file_path"
            style="object-fit: contain; height: 150px"
          />
        </div>
      </div>
    </div>
    <button
      class="h-40-px btn-outline btn fz-14 radius-5-px ml-auto w-100-px z-index-1000 mb-10 mr-5"
      @click="closeImageChat"
    >
      <span class="text-grey1 fz-14">キャンセル</span>
    </button>
  </div>
</template>
<script>
import ListMeeting from "../../../components/Admin/ListMeeting.vue";
import Avatar from "../../../components/Avatar.vue";
import {
  NOTIFICATION_MESSAGE,
  STATUS_LIST_CHATS,
  INVITATION_CONTENT,
  PARTICIPANT_ROLE,
  LOCATION_VALUE,
} from "../../../constants/index";
import ListInforChat from "./ListInforChat.vue";
import Todo from "./Todo.vue";
import InforMeeting from "./InforMeeting.vue";
import ConfirmModal from "../../../components/ConfirmModal.vue";
import {
  formatYMDhm,
  formatYMD,
  formatTime,
} from "../../../utils/formatDateTime";
import { socketChatType, SOCKET_KIND } from "../../../constants/socketConst";
import {
  USER_IMAGE,
  ARROW_DOWN,
  ARROW_UP,
  DEFAULT_FILE,
  PAPER_PLANE,
  DOCUMENT,
  TRASH_IMAGE,
  EXAMPLE_3,
  MESSAGE,
  EXAMPLE_1,
  EXAMPLE_2,
  NOT_FOUND,
} from "../../../constants/imageConst";
import HomeHeader from "./HomeHeader.vue";
import moment from "moment";
import PdfPreview from "../../../components/PdfPreview.vue";
import { mapMutations, mapState } from "vuex";
import {
  getListMeeting,
  getDetailMeeting,
  getDetailMeetingByLinkID,
  updateMeeting,
  deleteMeeting,
  updateStatusParticipant,
} from "../../../apis/meeting/meeting";
import {
  createChat,
  getDetailChat,
  getRoomID,
  seenedMessages,
  getAllListChat,
} from "../../../apis/chat/chat";
import { getFile } from "../../../apis/getFile";
import { notification } from "ant-design-vue";
import { MEETING_STATUS } from "../../../constants/index";
import { getNickname } from "../../../utils/helper";
import { getUserDetail } from "../../../apis/account/user";

export default {
  name: "Home",
  components: {
    ListMeeting,
    Avatar,
    ListInforChat,
    HomeHeader,
    PdfPreview,
    InforMeeting,
    ConfirmModal,
    Todo,
  },
  created() {
    this.INVITATION_CONTENT = INVITATION_CONTENT;
    this.formatYMD = formatYMD;
    this.formatYMDhm = formatYMDhm;
    this.formatTime = formatTime;
    this.DOCUMENT = DOCUMENT;
    this.NOT_FOUND = NOT_FOUND;
    this.USER_IMAGE = USER_IMAGE;
    this.DEFAULT_FILE = DEFAULT_FILE;
    this.PAPER_PLANE = PAPER_PLANE;
    this.ARROW_DOWN = ARROW_DOWN;
    this.ARROW_UP = ARROW_UP;
    this.STATUS_LIST_CHATS = STATUS_LIST_CHATS;
    this.TRASH_IMAGE = TRASH_IMAGE;
    this.EXAMPLE_3 = EXAMPLE_3;
    this.MESSAGE = MESSAGE;
    this.EXAMPLE_1 = EXAMPLE_1;
    this.EXAMPLE_2 = EXAMPLE_2;
    this.MEETING_STATUS = MEETING_STATUS;
    this.PARTICIPANT_ROLE = PARTICIPANT_ROLE;
    this.LOCATION_VALUE = LOCATION_VALUE;
    this.linkId = this.$route.params.link_id;
    if (this.linkId) {
      this.$router.push(`/meeting/${this.linkId}`);
      this.getMeetingByLinkID();
    } else {
      this.$router.push("/");
      this.getListMeetings();
    }
    if (this.$route.params.id) {
      this.getInforDetailMeeting(this.$route.params.id);
    }
  },
  mounted() {
    this.sockets.subscribe("signal", (data) => {
      if (data.type == socketChatType.createConversation) {
        if (data.sender_id != this.senderId) {
          this.dataStatus.forEach((item) => {
            if (
              item.id == data.sender_id &&
              this.senderId == data?.receiver_id
            ) {
              item.number = item.number + 1;
              const newMessage = {
                idUser: data.sender_id,
                seened: item.number,
              };
              const filteredMessages = this.unreadMessage.filter(
                (message) => message.idUser !== data.sender_id
              );
              this.unreadMessage = [newMessage, ...filteredMessages];
            }
            return;
          });
          if (this.roomID == data?.room) {
            const apis = [];
            if (data.conversation_files.length != 0) {
              data.conversation_files.forEach((file) => {
                apis.push(
                  getFile(file.file_path).then((result) => {
                    if (file.mime_type.includes("image")) {
                      file.file_path = URL.createObjectURL(result.data);
                      this.totalImages.push({
                        mime_type: file.mime_type,
                        file_path: file.file_path,
                      });
                      this.displayedImages = this.totalImages.slice(0, 5);
                      this.remainingImages = this.totalImages.slice(5).length;
                    } else if (file.mime_type.includes("pdf")) {
                      const filePDF = new File([result.data], file.file_name, {
                        type: "application/pdf",
                      });
                      file.file_path = filePDF;
                      this.totalImages.push({
                        mime_type: file.mime_type,
                        file_path: file.file_path,
                      });
                      this.displayedImages = this.totalImages.slice(0, 5);
                      this.remainingImages = this.totalImages.slice(5).length;
                    }
                  })
                );
              });
            }
            Promise.allSettled(apis).then(() => {
              this.conversationDetail.unshift(data);
              this.$nextTick(() => {
                this.scrollToTop();
              });
            });
          }
        }
      }
    });
    const data = {
      kind: SOCKET_KIND.chat,
    };
    this.$socket.emit("joinRoom", data);
  },
  computed: {
    ...mapState("meeting", [
      "checkActive",
      "id",
      "showDetail",
      "files",
      "isUpdateParticipant",
    ]),
    // eslint-disable-next-line vue/return-in-computed-property
    renderUnreadMess() {
      const numUnreadMess = this.unreadMessage.find(
        (item) => item.idUser == this.receiverId
      );
      if (numUnreadMess) return numUnreadMess?.seened;
    },
  },
  watch: {
    "$route.params.id": {
      handler: function (id) {
        let data = {
          room: +id || +this.id,
          kind: SOCKET_KIND.chat,
        };
        this.$socket.emit("joinRoom", data);
      },
      deep: true,
      immediate: true,
    },
    isUpdateParticipant: {
      handler(newValue) {
        if (newValue == true) {
          this.getDetail(this.id);
        }
      },
      deep: true,
    },
  },
  methods: {
    ...mapMutations("meeting", [
      "setDataInforMeeting",
      "setID",
      "setUploadFiles",
      "pushUploadFiles",
      "setFiles",
      "setEmptyFiles",
      "setShowDetailMeeting",
      "setLinkID",
      "spliceFiles",
      "setCheckActive",
      "setLinkWeb",
      "setDataDetail",
      "pushDataDetail",
      "setCheckAPI",
    ]),
    ...mapMutations("invitation", [
      "setShowModalInvitation",
      "setActive",
      "setTitleInvite",
    ]),
    ...mapMutations("account", ["setAccountDetail"]),

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
     * get time value last of chat
     */
    handleGoToCreateMeeting() {
      this.$router.push({ name: "CreatingMeeting" });
    },

    /**
     * get time last updated
     */
    getAttached(data) {
      const arrHasTime = data.filter(
        (item) => item?.conversation_files.length != 0
      );
      if (arrHasTime)
        arrHasTime.sort(
          (a, b) => new Date(b?.created_at) - new Date(a?.created_at)
        );
      this.timeAttached = arrHasTime
        ? moment(arrHasTime[0]?.created_at).format("YYYY/M/DD HH:mm")
        : moment(data[0]?.created_at).format("YYYY/M/DD HH:mm");
    },

    closeImageChat() {
      this.showListImageChat = false;
    },

    showImageChat() {
      this.showListImageChat = true;
    },

    /**
     * check pdf
     */
    checkPDF(file) {
      if (
        file?.file_path?.type === "application/pdf" ||
        file?.file_path?.type === "pdf"
      )
        return true;
      return false;
    },

    /**
     * get room chat detail
     */
    getRoomDetailChat(id) {
      getDetailChat(id)
        .then((result) => {
          result.data.data.forEach((element) => {
            if (element.conversation_files) {
              element.conversation_files.forEach((file) => {
                getFile(file.file_path)
                  .then((result) => {
                    const temp = this.totalImages.some(
                      (item) => item.id == file.id
                    );
                    if (file.mime_type.includes("image")) {
                      let temp_image = {
                        id: file.id,
                        mime_type: file.mime_type,
                        file_path: URL.createObjectURL(result.data),
                      };
                      file.file_path = URL.createObjectURL(result.data);
                      if (!temp) {
                        this.totalImages.push(temp_image);
                      }
                    } else if (file.mime_type.includes("pdf")) {
                      const filePDF = new File([result.data], file.file_name, {
                        type: "application/pdf",
                      });
                      file.file_path = filePDF;

                      let temp_image = {
                        id: file.id,
                        mime_type: file.mime_type,
                        file_path: filePDF,
                      };
                      if (!temp) {
                        this.totalImages.push(temp_image);
                      }
                    }
                  })
                  .then(() => {
                    const arrImage = this.totalImages;
                    this.displayedImages = arrImage.slice(0, 5);
                    this.remainingImages = arrImage.slice(5).length;
                  });
              });
            }
          });
          this.conversationDetail = result.data.data;
          if (this.conversationDetail)
            this.getAttached(this.conversationDetail);
          this.emitter.emit("isShowLoading", false);
        })
        .catch((error) => {
          console.log(error);
          this.emitter.emit("isShowLoading", false);
        });
    },

    /**
     * Handle show list chat
     */
    handleShowListChat() {
      this.totalImages = [];
      this.displayedImages = [];
      this.conversationDetail = [];
      this.remainingImages = 0;
      this.showListChat = true;
      this.setCheckActive(null);
      if (this.showListChat) {
        this.getAllChat({
          id: this.senderIDListChat,
          meeting_id: this.meetingIDListChat,
        });
      }
    },

    /**
     * Handle get all chat
     */
    getAllChat(data) {
      this.senderId = data.id;
      const param = {
        sender_id: data.id,
      };
      this.totalImages = [];
      this.displayedImages = [];
      this.conversationDetail = [];
      this.remainingImages = 0;
      this.showListChat == true;
      this.isChatAll = true;
      this.emitter.emit("isShowLoading", true);
      getAllListChat(data.meeting_id, param)
        .then((result) => {
          const newResult = result.data.map((element) => ({
            ...element,
            name:
              element?.sender_info?.last_name +
              " " +
              element?.sender_info?.first_name,
            company: element?.company_name,
            email: element?.sender_info?.email,
          }));
          newResult.forEach((element) => {
            if (element.conversation_files) {
              element.conversation_files.forEach((file) => {
                getFile(file.file_path)
                  .then((result) => {
                    if (file.mime_type.includes("image")) {
                      let temp_image = {
                        id: file.id,
                        mime_type: file.mime_type,
                        file_path: URL.createObjectURL(result.data),
                      };
                      file.file_path = URL.createObjectURL(result.data);
                      this.totalImages.push(temp_image);
                    } else if (file.mime_type.includes("pdf")) {
                      const filePDF = new File([result.data], file.file_name, {
                        type: "application/pdf",
                      });
                      file.file_path = filePDF;

                      let temp_image = {
                        id: file.id,
                        mime_type: file.mime_type,
                        file_path: filePDF,
                      };
                      this.totalImages.push(temp_image);
                    }
                  })
                  .then(() => {
                    const arrImage = this.totalImages;
                    this.displayedImages = arrImage.slice(0, 5);
                    this.remainingImages = arrImage.slice(5).length;
                  });
              });
            }
          });
          this.conversationDetail.push(...newResult);
          this.getAttached(this.conversationDetail);
          this.$nextTick(() => {
            this.scrollToTop();
          });
          this.emitter.emit("isShowLoading", false);
        })
        .catch((error) => {
          console.log(error);
          this.emitter.emit("isShowLoading", false);
        });
    },

    /**
     * Handle go to chat
     */
    handleGoToChat(data) {
      this.totalImages = [];
      this.displayedImages = [];
      this.remainingImages = 0;
      this.showListChat = false;
      this.senderId = data.senderID;
      this.receiverId = data.data.id;
      this.userIdClick = data.senderID;
      this.receiverCompany = data.data.company;
      this.receiverName = data.data.name;
      this.currentUserAvatarChat = data?.data?.avatar;
      this.conversationDetail = [];
      const param = {
        sender_id: this.senderId,
        meeting_id: data.data.meeting_id,
        receiver_id: this.receiverId,
      };
      this.dataStatus.forEach((item) => {
        if (item.id == this.receiverId) {
          item.number = 0;
        }
      });
      if (this.$refs.chatContent) this.$refs.chatContent.value = "";
      this.emitter.emit("isShowLoading", true);
      getRoomID(param)
        .then((result) => {
          this.isChatAll = false;
          if (typeof result.data == "number") {
            this.roomID = result.data;
            this.getRoomDetailChat(this.roomID);
            this.hasSeenMessage(this.roomID, this.senderId);
            param["room_id"] = result.data;
          } else {
            this.emitter.emit("isShowLoading", false);
          }
        })
        .catch((error) => {
          this.emitter.emit("isShowLoading", false);
        });
    },

    /**
     * click chat to set value default of unreadMessage
     */
    async hasSeenMessage(roomID, senderID) {
      try {
        const param = {
          sender_id: senderID,
        };
        await seenedMessages(roomID, param);
        const userById = this.unreadMessage.find(
          (item) => item.idUser == this.receiverId
        );
        if (userById !== undefined) {
          userById.seened = 0;
        }

        this.dataStatus.forEach((item) => {
          if (item.id == this.receiverId) {
            item.number = 0;
          }
        });
      } catch (error) {
        this.emitter.emit("isShowLoading", false);
      }
    },
    /**
     * delete meeting by id
     */
    handleDeleteMeeting() {
      const tempID = this.$route.params.id || this.id;
      this.emitter.emit("isShowLoading", true);
      deleteMeeting(tempID)
        .then((result) => {
          if (result.data == true) {
            notification.success({
              message: NOTIFICATION_MESSAGE.DELETE_SUCCESS,
            });
            this.getListMeetings();
            this.$router.push({ name: "Home" });
          } else
            notification.success({
              message: NOTIFICATION_MESSAGE.DELETE_FAIL,
            });
          this.emitter.emit("isShowLoading", false);
        })
        .catch((error) => {
          console.log(error);
          this.emitter.emit("isShowLoading", false);
        });
    },

    /**
     * Handle clear data in local
     */
    removeDataFromLocal() {
      localStorage.removeItem("COMPANY_NAME");
      localStorage.removeItem("FULLNAME");
      localStorage.removeItem("TIME");
      localStorage.removeItem("DATE");
    },

    async getMeetingByLinkID() {
      const link_id = { link_id: this.linkId };
      this.dataStatus = [];
      this.removeDataFromLocal();
      this.dataInforMeeting.link = null;
      this.dataInforMeeting.selected = "";
      this.setLinkWeb(null);
      this.emitter.emit("isShowLoading", true);
      try {
        const res = await getDetailMeetingByLinkID(link_id);
        //socket: send room id to server
        this.meetingId = res.data?.id;
        this.meetingDetail = res?.data;
        await this.processParticipantDetail(res, this.meetingId);
        // Continue with the rest of the code using the data obtained from getMeetingDetails

        var data = {
          room: +res.data?.id,
          kind: SOCKET_KIND.chat,
        };
        this.$socket.emit("joinRoom", data);
        //socket: end send room id to server

        this.setID(this.meetingId);
        this.processMeetingDetails(res.data);
        const scheduleDate = res.data?.schedule_date;
        const meetingData = {
          id: res.data?.id,
          date: moment(scheduleDate).format("YYYY年M月D日"),
          hour: this.getFullTime(res.data?.start_time, res.data?.end_time),
          title: res.data?.title,
          name: `${res.data?.user?.last_name} ${res.data?.user?.first_name}`,
          participants: res.data?.participants_count,
          creatorAvatar: this.creatorAvatar ?? null,
        };
        this.data = [];
        const eventsByDate = {};
        if (!scheduleDate) {
          this.data.push({
            id: "difference",
            title: "その他",
            meetings: [meetingData],
          });
        } else {
          const today = moment();
          const isToday = moment(scheduleDate).isSame(today, "day");
          const isTomorrow = moment(scheduleDate).isSame(
            today.clone().add(1, "day"),
            "day"
          );
          // Check whether the meetings is today or tomorrow or is just have day and don't have day
          if (isToday) {
            this.data.push({
              id: "today",
              title: "今日",
              meetings: [meetingData],
            });
          } else if (isTomorrow) {
            this.data.push({
              id: "tomorrow",
              title: "明日",
              meetings: [meetingData],
            });
          } else {
            if (!eventsByDate[scheduleDate]) {
              eventsByDate[scheduleDate] = {
                id: `id-${scheduleDate}`,
                title: moment(scheduleDate).format("YYYY年M月D日"),
                meetings: [],
              };
              this.data.push(eventsByDate[scheduleDate]);
            }
            eventsByDate[scheduleDate].meetings.push(meetingData);
          }
        }
        this.dataInforMeeting.dataImages = [];
        await this.processMeetingFiles(res.data.meeting_files);
      } catch (error) {
        console.log("Error fetching meeting details:", error);
      } finally {
        this.emitter.emit("isShowLoading", false);
      }
    },
    goToEdit() {
      this.setCheckActive(null);
      this.$router.push({ name: "EditMeeting", params: { id: this.id } });
    },
    /**
     * get meeting detail
     */
    async getDetail(id) {
      this.meetingId = id;
      this.emitter.emit("isShowLoading", true);
      this.removeDataFromLocal();
      this.dataInforMeeting.link = null;
      this.dataInforMeeting.selected = "";
      this.setLinkWeb(null);

      try {
        const res = await getDetailMeeting(id);
        this.dataStatus = [];
        this.meetingDetail = res?.data;

        await this.processParticipantDetail(res);

        this.processMeetingDetails(res.data);
        // Process meeting files
        await this.processMeetingFiles(res.data.meeting_files);
        this.emitter.emit("isShowLoading", false);
      } catch (error) {
        this.emitter.emit("isShowLoading", false);
        console.log("Error fetching meeting details:", error);
      }
    },

    /**
     * Process and handle meeting files
     */
    async processMeetingFiles(meetingFiles) {
      for (const element of meetingFiles) {
        const result = await getFile(element.file_path);

        if (element.mime_type.includes("image")) {
          let temp_image = {
            id: element.id,
            mime_type: element.mime_type,
            file_name: element.file_name,
            file_path: URL.createObjectURL(result.data),
            created_at: element.created_at,
          };
          this.dataInforMeeting.dataImages.push(temp_image);
        } else if (element.mime_type.includes("pdf")) {
          // get data and change a file to display pdf
          const filePDF = new File([result.data], element.file_name, {
            type: "application/pdf",
          });
          let temp_image = {
            id: element.id,
            mime_type: element.mime_type,
            file_path: filePDF,
            created_at: element.created_at,
          };
          this.dataInforMeeting.dataImages.push(temp_image);
        }
      }
    },
    async getParticipantAvatar(file_path, participant) {
      try {
        const result = await getFile(file_path);
        if (result && result.data) {
          participant.avatar = URL.createObjectURL(result.data);
        }
      } catch (error) {
        console.log("Error fetching participant avatar:", error);
      }
    },
    /**
     * Get current user info
     */
    async getUserInfo() {
      try {
        const { data } = await getUserDetail();
        this.setAccountDetail(data);
      } catch (error) {
        console.error(error);
      }
    },
    /**
     * get list meeting
     */
    async getListMeetings() {
      this.emitter.emit("isShowLoading", true);
      localStorage.removeItem("USER_MEETING_ID");
      this.data = [
        {
          id: 1,
          title: "今日",
          meetings: [],
        },
        {
          id: 2,
          title: "明日",
          meetings: [],
        },
      ];
      try {
        const res = await getListMeeting({ status: MEETING_STATUS.normal });
        await this.getUserInfo();
        if (res.data.data.length == 0) {
          this.emitter.emit("isShowLoading", false);
        }
        const eventsByDate = {};
        const emptyDay = {
          id: "id-empty",
          title: "その他",
          meetings: [],
        };

        const avatarPromises = res.data.data.map(async (item) => {
          if (item.user.avatar) {
            return this.getParticipantAvatar(item.user.avatar, item.user);
          }
        });

        await Promise.all(avatarPromises);

        res.data.data.map((item) => {
          const scheduleDate = item?.schedule_date;
          const meetingData = {
            id: item?.id,
            date: moment(scheduleDate).format("YYYY年M月D日"),
            hour: this.getFullTime(item?.start_time, item?.end_time),
            title: item?.title,
            name:
              item?.user?.nickname ||
              `${item?.user?.last_name} ${item?.user?.first_name}`,
            participants: item?.participants_count,
            creatorAvatar: item?.user?.avatar,
          };
          if (!scheduleDate) {
            emptyDay.meetings.push(meetingData);
          } else {
            const today = moment();
            const isToday = moment(scheduleDate).isSame(today, "day");
            const isTomorrow = moment(scheduleDate).isSame(
              today.clone().add(1, "day"),
              "day"
            );

            // Check whether the meetings is today or tomorrow or is just have day and don't have day
            if (isToday) {
              this.data[0]?.meetings.push(meetingData);
            } else if (isTomorrow) {
              this.data[1]?.meetings.push(meetingData);
            } else {
              if (!eventsByDate[scheduleDate]) {
                eventsByDate[scheduleDate] = {
                  id: `id-${scheduleDate}`,
                  title: moment(scheduleDate).format("YYYY年M月D日"),
                  meetings: [],
                };
                this.data.push(eventsByDate[scheduleDate]);
              }
              eventsByDate[scheduleDate].meetings.push(meetingData);
            }
          }
          this.data.sort((a, b) => {
            return moment(b.title, "YYYY年M月D日").diff(
              moment(a.title, "YYYY年M月D日")
            );
          });
        });

        this.data.push(emptyDay); // Push a meeting that don't have day to last of array

        // Select first meeting when render
        if (!this.$route.params.id) {
          const selectMeetingDetailByID = (index) => {
            this.getDetail(this.data[index].meetings[0].id);
            this.setID(this.data[index].meetings[0].id);
          };

          const selectedMeetingIndex = this.data.findIndex(
            (data) => data.meetings.length > 0
          );

          if (selectedMeetingIndex !== -1) {
            selectMeetingDetailByID(selectedMeetingIndex);
          }
        }
      } catch (err) {
        this.emitter.emit("isShowLoading", false);
        console.log("there was an error", err);
      }
    },

    /**
     * Process and set meeting details
     */
    processMeetingDetails(data) {
      this.setDataDetail(data.participants);
      this.senderIDListChat = data?.current_participant_id;
      const userCurrent = data.participants.find(
        (item) => item.id == this.senderIDListChat
      );
      if (userCurrent != undefined && userCurrent.status == 0) {
        this.handleUpdateStatusParticipant(this.senderIDListChat);
      }

      this.meetingIDListChat = data.id;
      this.getAllChat({
        id: this.senderIDListChat,
        meeting_id: this.meetingIDListChat,
      });
      this.setLinkID(data?.link_id);
      //
      this.dataInforMeeting.title = data?.title;
      this.dataInforMeeting.date = data?.schedule_date;
      this.dataInforMeeting.time = this.getFullTime(
        data?.start_time,
        data?.end_time
      );
      if (data?.location_type != null && data?.location_value != null) {
        if (data?.location_type) {
          this.dataInforMeeting.selected = LOCATION_VALUE[data?.location_type];
          this.dataInforMeeting.link = data?.location_value;
        }
        this.setLinkWeb(data?.location_value);
      } else {
        this.setLinkWeb(null);
      }

      if (data?.content !== "null")
        this.dataInforMeeting.content = data?.content;
      this.dataInforMeeting.dataImages = [];
      this.dataTodo.date = data.summary_updated_at
        ? moment(data.summary_updated_at).format("YYYY/M/D")
        : "";
      this.dataTodo.time = data.summary_updated_at
        ? moment(data.summary_updated_at).format("HH:mm")
        : "";
      this.dataTodo.author = data.summary_updater
        ? `${data.summary_updater.last_name}${data.summary_updater.first_name}`
        : "";
      this.dataTodo.summary = data.summary || "";

      this.companyName = data.user.company_name;
      this.fullName = data.user
        ? data.user.nickname || `${data.user.last_name}${data.user.first_name}`
        : "";
      this.time = data.created_at
        ? moment(data.created_at).format("HH:mm")
        : "";
      this.date = data.created_at
        ? moment(data.created_at).format("YYYY/M/D")
        : "";
    },

    /**
     * Get meeting details
     */
    async processParticipantDetail(res) {
      let lengthEmails = 0;

      if (res?.data.user?.avatar) {
        await this.getParticipantAvatar(res.data.user.avatar, res.data.user);
        this.creatorAvatar = res.data.user.avatar;
      }
      await Promise.all(
        res.data.participants.map(async (item) => {
          if (item.user?.avatar) {
            await this.getParticipantAvatar(item.user?.avatar, item);
          }
        })
      );
      await Promise.all(
        res.data.participants.map(async (item) => {
          if (
            res?.data?.current_participant_id &&
            res?.data?.current_participant_id === item.id
          ) {
            this.currentParticipantRole = item.role;
            this.currentUserAvatar = item.avatar;
          }

          if (res.data.user?.email != item.email) {
            lengthEmails++;
            this.dataStatus.push({
              id: item.id,
              meeting_id: item?.meeting_id,
              checkEmail: item.email,
              company: item.email || item?.company_name,
              name: getNickname(item?.user),
              status: this.getStatusSeen(item, res.data.current_participant_id),
              number: item.count_unread_message || 0,
              avatar: item?.avatar,
              role: item?.role,
              currentParticipantID: res?.data.current_participant_id,
              userId: item?.user_id,
              data: res?.data,
              created_at: item?.created_at,
            });
            this.unreadMessage.push({
              idUser: item.id,
              seened: item.count_unread_message || 0,
            });
            if (lengthEmails == res.data.participants.length) {
              this.dataStatus.unshift({
                id: res.data.user.id,
                createdID: res.data?.creator_id,
                company: res.data.user?.company_name || res.data.user?.email,
                name: getNickname(res.data?.user),
                status: STATUS_LIST_CHATS.operator,
                checkEmail: res.data.user?.email,
                avatar: res.data.user?.avatar,
                data: res.data,
                created_at: item?.created_at,
              });
              this.unreadMessage.unshift({
                idUser: item.id,
                seened: item.count_unread_message || 0,
              });
            }
          } else if (res.data.user?.email == item.email) {
            this.checkCreator = true;
            this.dataStatus.unshift({
              id: item.id,
              createdID: res.data?.creator_id,
              meeting_id: item?.meeting_id,
              company: res.data.user?.company_name || res.data.user?.email,
              name: getNickname(item?.user),
              status: STATUS_LIST_CHATS.operator,
              checkEmail: res.data.user?.email,
              data: res.data,
              number: item.count_unread_message || 0,
              avatar: item?.avatar,
              role: item?.role,
              userId: item?.user_id,
              isDeletedCreator: true,
              created_at: item?.created_at,
            });
            this.unreadMessage.unshift({
              idUser: item.id,
              seened: item.count_unread_message || 0,
            });
          }
        })
      );
    },
    getStatusSeen(data, idUserCurrent) {
      if (data.status != 1) {
        if (data.id == idUserCurrent) return STATUS_LIST_CHATS.seen;
        return STATUS_LIST_CHATS.notSeen;
      }
      return STATUS_LIST_CHATS.seen;
    },
    /**
     * get full time
     */
    getFullTime(start_time, end_time) {
      if (start_time != null && end_time != null)
        return start_time?.slice(0, 5) + ` ~ ` + end_time?.slice(0, 5);
      else if (start_time != null && end_time == null)
        return start_time?.slice(0, 5);
      else if (start_time == null && end_time == null) return `時間なし`;
    },
    /**
     * deleted item in list chat
     */
    handleDeleted(data) {
      this.dataStatus.splice(data, 1);
    },
    /**
     * set item in list todo
     */
    setValueTodo(data) {
      if (data.summary != "") this.dataTodo.summary = data.summary;
      data = {
        summary: data.summary,
      };
      this.handleUpdateMeetingSummary(data);
    },

    handleUpdateMeetingSummary(data) {
      this.emitter.emit("isShowLoading", true);
      updateMeeting(this.id, data)
        .then((res) => {
          notification.success({
            message: NOTIFICATION_MESSAGE.UPDATE_SUCCESS,
          });
          this.getDetail(this.id);
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          notification.error({
            message: NOTIFICATION_MESSAGE.UPDATE_FAIL,
          });
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },
    /**
     * update status of meeting
     */
    handleUpdateStatusParticipant(id) {
      let formData = new FormData();
      formData.append("status", 1);
      this.emitter.emit("isShowLoading", true);
      updateStatusParticipant(id, formData)
        .then((res) => {
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },

    /**
     * open modal
     */
    handleAdd(data) {
      // this.setShowModalInvitation(data);
    },
    /**
     * open modal
     */
    showCancelMeeting(value) {
      this.showModalCancelMeeting = value;
    },
    /**
     * cancel confirm modal
     */
    handleDestroyCancelMeeting() {
      this.showModalCancelMeeting = false;
    },
    /**
     * accept confirm modal
     */
    handleAcceptCancelMeeting() {
      this.showModalCancelMeeting = false;
    },
    /**
     * close modal
     */
    closeCancelMeeting() {
      this.showModalCancelMeeting = false;
    },
    saveCancelMeeting() {
      this.handleDeleteMeeting();
      this.showModalCancelMeeting = false;
    },

    getInforDetailMeeting(id) {
      this.setCheckActive(null);
      this.setShowDetailMeeting(true);
      this.setID(id);
      this.setCheckAPI(false);
      this.dataInforMeeting.dataImages = [];
      this.setTitleInvite(INVITATION_CONTENT.INVITATION);
      this.setActive({ index: 1, subIndex: 1 });
      this.getDetail(id);
      this.$router.push({ name: "MeetingDetail", params: { id: id } });
    },
    /**
     * upload image and pdf
     */
    uploadFile(event) {
      let input = event.target;
      if (input.files) {
        for (let i = 0; i < input.files.length; i++) {
          let file = input.files[i];
          this.setFiles(file);
          if (file.type.includes("image")) {
            let reader = new FileReader();
            reader.onload = (e) => {
              this.upload_files.push({
                type: "image",
                file: e.target.result,
                blob: file,
              });
            };
            reader.readAsDataURL(file);
          } else if (file.type === "application/pdf") {
            this.upload_files.push({ type: "pdf", file, blob: file });
          }
        }
      }
    },
    /**
     * Remove files from upload_pdfs
     * @param {*} index
     */
    removeUploadFile(index) {
      this.spliceFiles(index);
      this.upload_files.splice(index, 1);
      if (this.$refs.imgagePDF) this.$refs.imgagePDF.value = "";
    },

    /** * Line up in chat input */
    dropLine() {
      this.$refs.chatContent.value += "\n";
    },
    handleSendChat(e) {
      !e.isComposing && this.sendChat();
    },
    /**
     * Resize chat input
     */
    resizeInput() {
      let elementInput = this.$refs.chatContent;
      elementInput.style.height = "32px";
      elementInput.style.height = elementInput.scrollHeight + "px";
      // this.resizeChatBody();
    },
    resizeChatBody() {
      let elementInput = this.$refs.chatContent;
      if (this.upload_files.length > 0) {
        $(
          ".participant-chat__body"
        )[0].style.height = `calc(100vh - 60px - 65px - 152px - ${elementInput.clientHeight}px)`;
      } else {
        $(
          ".participant-chat__body"
        )[0].style.height = `calc(100vh - 60px - 65px - 52px - ${elementInput.clientHeight}px)`;
      }
    },
    /**
     * send chat message
     */
    async sendChat() {
      let files = [];
      let checkChat = false;
      if (this.upload_files.length > 0) {
        this.upload_files.forEach((element) => {
          files.push({
            mime_type: element.type,
            file_name: element.name,
            file_path: element.file,
            file_blob: element.blob,
            created_at: moment(),
          });
        });
      }
      if (
        this.$refs.chatContent.value === "" &&
        this.upload_files.length === 0
      ) {
        return;
      }
      // api
      if (!checkChat) {
        await this.apiChainChat(
          this.senderId,
          this.receiverId,
          this.meetingId,
          this.$refs.chatContent.value,
          files
        );
        checkChat = true;
      }
      if (checkChat) {
        this.$refs.chatContent.value = "";
        this.upload_files = [];
      }
      this.$nextTick(() => {
        this.scrollToTop();
      });
    },
    /**
     * @description load image
     */
    async loadImage(file) {
      if (
        file?.file_path?.includes &&
        file?.file_path?.includes("conversation_files")
      ) {
        getFile(file.file_path)
          .then((result) => {
            if (file.mime_type.includes("image")) {
              let temp_image = {
                id: file.id,
                mime_type: file.mime_type,
                file_path: URL.createObjectURL(result.data),
              };
              file.file_path = URL.createObjectURL(result.data);
              this.totalImages.push(temp_image);
            } else if (file.mime_type.includes("pdf")) {
              const filePDF = new File([result.data], file.file_name, {
                type: "application/pdf",
              });
              file.file_path = filePDF;
              let temp_image = {
                id: file.id,
                mime_type: file.mime_type,
                file_path: filePDF,
              };
              this.totalImages.push(temp_image);
            }
          })
          .then(() => {
            const arrImage = this.totalImages;
            this.displayedImages = arrImage.slice(0, 5);
            this.remainingImages = arrImage.slice(5).length;
          });
      }
    },
    /**
     * calling the api finished, it will call that api again
     */
    async apiChainChat(sender_id, receiver_id, meeting_id, message, file) {
      if (this.apiChain) {
        this.apiChain = this.apiChain.finally(() =>
          this.createMess(sender_id, receiver_id, meeting_id, message, file)
        );
        return;
      }
      this.apiChain = this.createMess(
        sender_id,
        receiver_id,
        meeting_id,
        message,
        file
      );
    },
    /**
     * Get Conversation List
     */
    async createMess(sender_id, receiver_id, meeting_id, message, file) {
      let dataTemp = [];
      const userById = this.unreadMessage.find(
        (item) => item.idUser == this.receiverId
      );
      if (userById != undefined) {
        if (userById.seened > 0) {
          this.hasSeenMessage(this.roomID, this.senderId);
        }
      }
      let formData = new FormData();
      if (message != "" && message != null) {
        formData.append("message", message);
      }
      if (file.length > 0) {
        file.forEach((element, index) => {
          formData.append("file[" + index + "]", element.file_blob);
        });
      }
      formData.append("sender_id", sender_id);
      formData.append("meeting_id", meeting_id);
      formData.append("receiver_id", receiver_id);

      const temp = {
        message: formData.get("message"),
        sender_id: formData.get("sender_id"),
        conversation_files: file,
        created_at: moment(),
      };
      this.$refs.chatContent.value = "";
      this.conversationDetail.unshift(temp);
      dataTemp.unshift(temp);
      this.$nextTick(() => {
        this.scrollToTop();
      });
      try {
        const res = await createChat(formData);
        if (res.data.conversation_files || res.data.message) {
          res.data.conversation_files.forEach(async (file) => {
            const result = await getFile(file.file_path);

            if (file.mime_type.includes("image")) {
              file.file_path = URL.createObjectURL(result.data);
              this.totalImages.push({
                mime_type: file.mime_type,
                file_path: file.file_path,
              });
              this.remainingImages = this.totalImages.slice(5).length;
            } else if (file.mime_type.includes("pdf")) {
              const filePDF = new File([result.data], file.file_name, {
                type: "application/pdf",
              });
              file.file_path = filePDF;
              this.totalImages.push({
                mime_type: file.mime_type,
                file_path: file.file_path,
              });
              this.remainingImages = this.totalImages.slice(5).length;
            }
          });

          var data = {
            id: res.data.id,
            room: this.roomID,
            sender_id: this.senderId,
            receiver_id: receiver_id,
            message: res.data.message,
            conversation_files: res.data.conversation_files,
            created_at: res.data.created_at,
            kind: SOCKET_KIND.chat,
            type: socketChatType.createConversation,
          };

          this.displayedImages = this.totalImages.slice(0, 5);

          this.$nextTick(() => {
            this.scrollToTop();
          });

          this.$socket.emit("sendSignal", data);
        }

        let arrChat = this.conversationDetail;

        if (arrChat) {
          arrChat.forEach((item) => {
            if (item.conversation_files) {
              item.conversation_files.forEach((file) => {
                this.loadImage(file);
                this.setEmptyFiles([]);
              });
            }
          });
        }
      } catch (error) {
        // Handle error if needed
      } finally {
        this.$nextTick(() => {
          this.scrollToTop();
        });
      }
    },
    renderDate(date) {
      const formattedDate = moment(date, "YYYY-MM-DD").format("YYYY-MM-DD");
      const currentDate = moment().format("YYYY-MM-DD");
      if (moment(formattedDate).isSame(currentDate, "day")) return "今日";
      else return moment(date, "YYYY-MM-DD").format("YYYY-MM-DD");
    },
    /**
     * Scroll chat to top
     */
    scrollToTop() {
      let element = document.querySelector(".participant-chat__body");
      element.scrollTop = 0;
    },
    /**
     * click toggle
     */
    handleClick() {
      this.isToggle = !this.isToggle;
    },
    /**
     * Format date
     */
    formatDate(date) {
      return formatYMD(date);
    },
    /**
     * Format datetime
     */
    formatDateTime(dateTime) {
      return formatYMDhm(dateTime);
    },
    handleFetchNewParticipant() {
      this.getDetail(this.id);
    },
    getReceiverAvatar(id) {
      const foundItem = this.dataStatus.find((item) => item.id === id);
      if (foundItem) {
        return foundItem.avatar;
      }

      return null; // Return null if the item is not found
    },
  },
  data() {
    return {
      checkCreator: false,
      senderIDListChat: null,
      meetingIDListChat: null,
      showListChat: true,
      timeAttached: null,
      receiverCompany: null,
      receiverName: null,
      listReceiverInfor: [],
      roomID: null,
      showListImageChat: false,
      displayedImages: [],
      remainingImages: null,
      totalImages: [],
      linkId: null,
      senderId: null,
      receiverId: null,
      userIdClick: null,
      isToggle: false,
      upload_files: [],
      conversationDetail: [],
      showModalCancelMeeting: false,
      meetingId: null,
      dataTest: [],
      dateTitle: "",
      companyName: "",
      fullName: "",
      time: "",
      date: "",
      data: [],
      dataInforMeeting: {},
      dataTodo: {
        date: "",
        time: "",
        author: "",
        summary: "",
      },
      dataStatus: [],
      unreadMessage: [],
      isChatAll: false,
      meetingDetail: {},
      currentParticipantRole: null,
      creatorAvatar: "",
      currentUserAvatar: "",
      currentUserAvatarChat: "",
      apiChain: null,
    };
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";
</style>
