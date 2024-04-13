<template lang="">
  <div class="d-flex home create-meeting">
    <div class="home__right w-100 p-10">
      <!-- Header -->
      <HomeHeader
        linkCopy="打ち合わせリンクコピーする"
        :avatar="USER_IMAGE"
        :company="companyName"
        :name="fullName"
        :date="date"
        :time="time"
        :hideButtonProp="true"
      />
      <InforMeeting
        :data="dataInforMeeting"
        @show="showCancelMeeting"
        @edit="goToEdit"
        :hideButtonProp="true"
      />
      <!-- todo -->
      <Todo :data="dataTodo" @getValue="setValueTodo" :hideButtonProp="true" />
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
          <div
            class="text-underline cur-p fz-16"
            @click="handleDestroyCancelMeeting"
          >
            <span class="text-grey1 fz-14">キャンセル</span>
          </div>
          <button
            @click="handleAcceptCancelMeeting"
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
</template>
<script>
import ListMeeting from "../../../components/Admin/ListMeeting.vue";
import Avatar from "../../../components/Avatar.vue";
import {
  NOTIFICATION_MESSAGE,
  STATUS_LIST_CHATS,
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
} from "../../../constants/imageConst";
import HomeHeader from "./HomeHeader.vue";
import { conversationDetail } from "./mockData";
import moment from "moment";
import PdfPreview from "../../../components/PdfPreview.vue";
import { mapMutations, mapState } from "vuex";
import {
  getListMeeting,
  getDetailMeeting,
  getDetailMeetingByLinkID,
  updateMeeting,
  updateStatusParticipant,
} from "../../../apis/meeting/meeting";
import { getFile } from "../../../apis/getFile";
import { notification } from "ant-design-vue";
import { MEETING_STATUS } from "../../../constants/index";
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
    this.conversationDetail = conversationDetail;
    this.formatYMD = formatYMD;
    this.formatYMDhm = formatYMDhm;
    this.formatTime = formatTime;
    this.DOCUMENT = DOCUMENT;
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
    this.linkId = this.$route.params.link_id;
    this.token = this.$route.params.token;
    if (this.$route.params.token) {
      if (localStorage.getItem("isLogin") === "true") {
        this.$router.push({
          name: "HomeByLinkID",
          params: { link_id: this.linkId },
        });
      } else {
        this.$router.push({
          name: "MeetingGuest",
          params: { link_id: this.linkId, token: this.token },
        });
      }
      this.getMeetingByLinkID();
    } else {
      this.$router.push("/404");
      this.getListMeetings();
    }
  },
  mounted() {
    // this.sockets.subscribe("signal", (data) => {
    //   if (data.type == socketChatType.createConversation) {
    //     if (data.conversation.sender_id != this.userId) {
    //       this.scrollToLast();
    //     }
    //     if (data.roomSize > 1 && data.receiver_id == this.userId) {
    //       console.log("create conversation 2 user");
    //     }
    //   } else if (data.type == socketChatType.updateConversation) {
    //     console.log("send new message");
    //   } else if (data.type == socketChatType.removeFileConversation) {
    //     console.log("remove file in conversation");
    //   } else if (data.type == socketChatType.removeMessageConversation) {
    //     console.log("remove message in conversation");
    //   }
    // });
    // const data = {
    //   kind: SOCKET_KIND.chat,
    // };
    // this.$socket.emit("joinRoom", data);
  },
  computed: {
    ...mapState("meeting", ["checkActive", "id", "showDetail"]),
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
  },
  methods: {
    ...mapMutations("meeting", [
      "setDataInforMeeting",
      "setID",
      "setUploadFiles",
      "pushUploadFiles",
      "setFiles",
      "setShowDetailMeeting",
    ]),
    ...mapMutations("invitation", ["setShowModalInvitation"]),
    setDataIntoLocal(res) {
      localStorage.setItem("COMPANY_NAME", res.data?.user.company_name);
      let time = moment(res.data?.created_at).format("HH:mm");
      let date = moment(res.data?.created_at).format("YYYY/MM/DD");
      localStorage.setItem("TIME", time);
      localStorage.setItem("DATE", date);
      localStorage.setItem(
        "FULLNAME",
        res.data?.user.last_name + " " + res.data?.user.first_name
      );
    },
    getDataIntoLocal() {
      this.companyName = localStorage.getItem("COMPANY_NAME");
      this.time = localStorage.getItem("TIME");
      this.date = localStorage.getItem("DATE");
      this.fullName = localStorage.getItem("FULLNAME");
    },
    removeDataFromLocal() {
      localStorage.removeItem("COMPANY_NAME");
      localStorage.removeItem("FULLNAME");
      localStorage.removeItem("TIME");
      localStorage.removeItem("DATE");
    },
    getMeetingByLinkID() {
      console.log("Meeting Guest");
      const params = { link_id: this.linkId, token: this.token };
      this.removeDataFromLocal();
      getDetailMeetingByLinkID(params).then((res) => {
        //socket: send room id to server
        this.meetingId = res.data?.id;
        if (res.data.current_participant_id) {
          this.handleUpdateStatusParticipant(res.data.current_participant_id);
        }
        this.setID(this.meetingId);
        var data = {
          room: +res.data?.id,
          kind: SOCKET_KIND.chat,
        };
        this.$socket.emit("joinRoom", data);
        //socket: end send room id to server
        if (moment(res.data?.schedule_date).isSame(moment(), "day")) {
          this.data[0]?.meetings.push({
            id: res.data?.id,
            date: moment(res.data?.schedule_date).format("YYYY年M月D日"),
            hour: this.getFullTime(res.data?.start_time, res.data?.end_time),
            title: res.data?.title,
            name: `${res.data?.user?.last_name} ${res.data?.user?.first_name}`,
            participants: res.data?.participants_count,
          });
        } else {
          this.data[1]?.meetings.push({
            id: res.data?.id,
            date: moment(res.data?.schedule_date).format("YYYY年M月D日"),
            hour: this.getFullTime(res.data?.start_time, res.data?.end_time),
            title: res.data?.title,
            name: `${res.data?.user?.last_name} ${res.data?.user?.first_name}`,
            participants: res.data?.participants_count,
          });
        }

        this.dataTodo.date = res.data.summary_updated_at
          ? moment(res.data.summary_updated_at).format("YYYY/M/D")
          : "";
        this.dataTodo.time = res.data.summary_updated_at
          ? moment(res.data.summary_updated_at).format("HH:mm")
          : "";
        this.dataTodo.author = res.data.summary_updater
          ? `${res.data.summary_updater.last_name}${res.data.summary_updater.first_name}`
          : "";
        this.dataTodo.summary = res.data.summary || "";

        this.companyName = res.data.user.company_name;
        this.fullName = res.data.user
          ? `${res.data.user.last_name}${res.data.user.first_name}`
          : "";
        this.time = res.data.created_at
          ? moment(res.data.created_at).format("HH:mm")
          : "";
        this.date = res.data.created_at
          ? moment(res.data.created_at).format("YYYY/M/D")
          : "";
        //meeting details
        this.dataInforMeeting.title = res.data?.title;
        this.dataInforMeeting.date = res.data?.schedule_date;
        this.dataInforMeeting.time = this.getFullTime(
          res.data?.start_time,
          res.data?.end_time
        );
        if (
          res.data?.location_type != null &&
          res.data?.location_value != null
        ) {
          if (res.data?.location_type == 1)
            this.dataInforMeeting.selected = "web会議";
          this.dataInforMeeting.link = res.data?.location_value;
        }
        if (res.data?.content !== "null")
          this.dataInforMeeting.content = res.data?.content;
        this.dataInforMeeting.dataImages = [];
        res.data?.meeting_files.forEach((element) => {
          getFile(element.file_path)
            .then((result) => {
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
                };
                this.dataInforMeeting.dataImages.push(temp_image);
                this.emitter.emit("isShowLoading", false);
              }
            })
            .catch((error) => {
              console.log(error);
            });
        });
        this.emitter.emit("isShowLoading", false);
      });
    },
    goToEdit() {
      this.$router.push({ name: "EditMeeting", params: { id: this.id } });
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
      this.setShowModalInvitation(data);
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

    getInforDetailMeeting(id) {
      this.setShowDetailMeeting(true);
      this.setID(id);
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
          if (file.type.includes("image")) {
            let reader = new FileReader();
            reader.onload = (e) => {
              this.upload_files.push({ type: "image", file: e.target.result });
            };
            reader.readAsDataURL(file);
          } else if (file.type === "application/pdf") {
            this.upload_files.push({ type: "pdf", file });
          }
        }
      }
    },
    /**
     * Remove files from upload_pdfs
     * @param {*} index
     */
    removeUploadFile(index) {
      this.upload_files.splice(index, 1);
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
      if (this.upload_files.length > 0) {
        this.upload_files.forEach((element) => {
          files.push({
            mime_type: element.file.type,
            file_name: element.file.name,
            file_path: element.file,
            created_at: new Date(new Date().toUTCString().substring(0, 25)),
          });
        });
      }
      let obj = {
        sender_id: this.senderId,
        message: this.$refs.chatContent.value,
        created_at: new Date(new Date().toUTCString().substring(0, 25)),
        conversation_files: files,
      };
      if (
        this.$refs.chatContent.value === "" &&
        this.upload_files.length === 0
      ) {
        return;
      }
      this.conversationDetail.push(obj);
      // var data = {
      //   conversation: obj,
      //   receiver_id: this.userId == 1 ? 2 : 1,
      //   kind: SOCKET_KIND.chat,
      //   type: socketChatType.createConversation,
      // };
      // this.$socket.emit("sendSignal", data);
      this.$refs.chatContent.value = "";
      this.upload_files = [];
      this.scrollToLast();
    },
    renderDate(date) {
      if (moment(date, "YYYY-MM-DD").isSame(moment(), "day")) return "今日";
      else return moment(date, "YYYY-MM-DD").format("YYYY-MM-DD");
    },
    /**
     * Scroll chat to last
     */
    scrollToLast() {
      let element = document.querySelector(".participant-chat__body");
      $(".participant-chat__body").animate(
        { scrollTop: element.scrollHeight },
        800
      );
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
  },
  data() {
    return {
      linkId: null,
      senderId: 1,
      isToggle: false,
      upload_files: [],
      conversationDetail: conversationDetail,
      showModalCancelMeeting: false,
      meetingId: null,
      dataTest: [],
      dateTitle: "",
      companyName: "",
      fullName: "",
      time: "",
      date: "",
      data: [
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
        // {
        //   id: 3,
        //   title: "",
        //   meetings: [],
        // },
        {
          id: 3,
          title: "その他",
          meetings: [],
        },
      ],
      dataInforMeeting: {},
      dataTodo: {
        date: "",
        time: "",
        author: "",
        summary: "",
      },
      dataStatus: [
        // {
        //   status: STATUS_LIST_CHATS.operator,
        //   company: "株式会社グロー",
        //   name: "大野 修平",
        //   number: 0,
        // },
        // {
        //   status: STATUS_LIST_CHATS.seen,
        //   company: "株式会社グロー",
        //   name: "山本 太郎",
        //   number: 1,
        // },
        // {
        //   status: STATUS_LIST_CHATS.notSeen,
        //   company: "ABC株式会社",
        //   name: "山田 一郎",
        //   number: 0,
        // },
      ],
    };
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";
</style>
