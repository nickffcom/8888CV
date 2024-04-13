<template lang="">
  <div class="invitation d-flex flex-column text-black1 w-100 mt-5">
    <div class="invitation__top w-100 d-flex flex-column">
      <span class="text-black1 invitation__top-title fz-16 mb-10">
        電子メールで招待
      </span>
      <span class="fz-14 mb-10">ChatMeeting を電子メールで伝える。</span>
      <textarea
        v-if="textarea == true"
        v-model="sendEmailTextArea"
        spellcheck="false"
        class="border-1-color-grey radius-10-px pl-20 pt-10 form-control invitation-textarea mb-5 fz-14"
        placeholder="メールアドレス: (改行区切りで複数入力可)"
      />
      <div v-else-if="textarea == false">
        <div
          v-for="(item, index) in inputCount"
          :key="index"
          class="d-flex flex-column"
        >
          <div class="d-flex">
            <input
              v-model="sendEmail[index]"
              type="text"
              class="pl-20 pr-20 radius-8-px mb-5 h-40-px form-control fz-14"
              spellcheck="false"
              placeholder="メールアドレスを入力"
            />
            <i
              v-if="checkAdd == true"
              class="fas fa-times text-grey1 fz-14 pl-15 d-flex align-items-center mb-5 cur-p"
              @click="subtractInput(index)"
            />
          </div>
          <span
            class="error fz-14 mb-5"
            v-if="emptyEmail == false && getStatus(listError[index], index)"
          >
            {{ emailErrorMessage.emailSyntaxError }}
          </span>
          <span class="error fz-14 mb-5" v-else-if="emptyEmail == true">
            {{ emailErrorMessage.emptyEmailError }}
          </span>
        </div>
      </div>
      <div class="d-flex justify-content-between mb-15">
        <span
          :class="['text-global cur-p fz-14', hide == true && 'visibleText']"
          @click="addInput"
        >
          +招待するメールアドレスを追加
        </span>
        <span
          @click="showTextarea"
          v-if="textarea == false"
          class="text-global cur-p fz-14"
        >
          一括追加
        </span>
        <span
          class="text-global cur-p fz-14"
          @click="hideTextarea"
          v-if="textarea == true"
        >
          個別追加
        </span>
      </div>
      <span class="text-black1 invitation__top-title fz-16 mb-10">
        メッセージ (任意)
      </span>
      <textarea
        class="pl-20 pr-20 pt-10 radius-8-px fz-14 form-control invitation-textarea"
        placeholder="メッセージを入力"
        v-model="content"
        spellcheck="false"
      />
      <div class="d-flex justify-content-end">
        <button class="mt-35 h-30-px" @click="handleSendEmail">
          招待メールを送信
        </button>
      </div>
    </div>
    <div class="invitation__bottom mt-30 w-100 d-flex flex-column">
      <span class="text-black1 invitation__top-title fz-16 mb-10">
        リンクを共有して招待する
      </span>
      <span class="fz-14">
        相手にプロフィールのリンクを伝えて、 ChatMeeting でつながりましょう。
      </span>
      <div
        class="d-flex"
        :class="
          checkAPI == false ? 'justify-content-end' : 'justify-content-between'
        "
      >
        <div
          class="mt-35 link-invitation justify-content-center align-items-center bg-color-grey2 mt-10 mr-20 w-100 radius-10-px"
          :class="checkAPI == false ? 'd-none' : 'd-flex'"
        >
          <span class="text-link ml-10 fz-14 lh-35">
            {{ link }}
          </span>
          <div
            class="cur-p h-35-px text-center w-70-px link-image"
            @click="handleCopy"
          >
            <img
              v-if="!isCopied"
              :src="LINK_IMAGE"
              alt=""
              srcset=""
              class="h-35-px"
            />
            <img v-else :src="CHECKED_IMAGE" alt="" srcset="" class="h-25-px" />
          </div>
        </div>
        <button @click="handleShareLink" class="mt-35 h-30-px">
          リンクを発行する
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { ERROR_MESSAGE, NOTIFICATION_MESSAGE } from "../../constants";
import { validEmail } from "../../utils/helper";
import { inviteUser } from "../../apis/meeting/meeting";
import {
  getMeetingLink,
  inviteEmail,
  inviteLink,
} from "../../apis/invite/invite";
import { notification } from "ant-design-vue";
import { mapMutations, mapState } from "vuex";
import { LINK_IMAGE, CHECKED_IMAGE } from "../../constants/imageConst";
export default {
  name: "Invitation",
  components: {},
  created() {
    this.LINK_IMAGE = LINK_IMAGE;
    this.CHECKED_IMAGE = CHECKED_IMAGE;
  },
  data() {
    return {
      content: "",
      sendEmail: [],
      sendEmailTextArea: "",
      checkAdd: false,
      inputCount: 1,
      inputCountTemp: 1,
      textarea: false,
      hide: false,
      checkSendEmail: false,
      emptyEmail: false,
      emailErrorMessage: {
        emailSyntaxError: ERROR_MESSAGE.emailSyntaxError,
        emptyEmailError: ERROR_MESSAGE.emailEmptyError,
      },
      haveError: false,
      listError: [],
      isCopied: false,
    };
  },
  computed: {
    ...mapState("meeting", ["id", "checkAPI", "link"]),
  },
  watch: {
    sendEmail: {
      handler: function (newValue) {
        this.sendEmailTextArea = newValue.join("\n");
      },
      deep: true,
    },
    sendEmailTextArea: {
      handler: function (newValue) {
        this.sendEmail = newValue.split("\n");
      },
      deep: true,
    },
  },
  methods: {
    ...mapMutations("meeting", ["setID", "setCheckAPI", "setLink"]),
    showTextarea() {
      this.hide = true;
      this.inputCount = 0;
      this.textarea = true;
      this.checkAdd = false;
    },
    hideTextarea() {
      this.hide = false;
      if (this.sendEmail.length > 0) {
        if (this.sendEmail.length >= 2) this.inputCount = this.sendEmail.length;
        else this.inputCount = 1;
      } else this.inputCount = this.inputCountTemp;
      this.textarea = false;
      if (this.inputCount > 1) {
        this.checkAdd = true;
      }
    },
    subtractInput(index) {
      this.sendEmail.splice(index, 1);
      this.listError.splice(index, 1);
      this.listError = this.listError.map((item, index) => ({
        ...item,
        index: index,
      }));
      this.inputCount--;
      if (this.inputCount == 1) this.checkAdd = false;
    },
    addInput() {
      this.checkAdd = true;
      this.inputCount++;
      this.inputCountTemp = this.inputCount;
      this.emptyEmail = false;
    },
    handleSendEmail() {
      this.listError = [];
      this.sendEmail = this.sendEmail.filter(
        (item) => item !== undefined && item !== ""
      );
      this.listError = this.listError.map((item, index) => ({
        ...item,
        index: index,
      }));
      this.sendEmail.forEach((item, index) => {
        const isCheck = this.validateEmail(item);
        this.listError.push({ status: isCheck, index: index });
        if (isCheck == false) {
          this.haveError = true;
        } else this.haveError = false;
      });

      this.inputCount = this.sendEmail.length;
      if (this.sendEmail.length != 0) {
        if (this.sendEmail.length === 1 && !this.sendEmail[0]) {
          this.emptyEmail = true;
        } else if (this.sendEmail.length === 1 && this.sendEmail[0]) {
          this.emptyEmail = false;
        }
      } else if (this.sendEmail.length == 0) {
        this.emptyEmail = true;
        this.inputCount = 1;
      }

      if (this.sendEmail.length > 1) this.checkAdd = true;
      else this.checkAdd = false;
      this.inputCountTemp = this.inputCount;
      if (this.haveError == false) {
        if (
          this.inputCount != this.sendEmail.length &&
          this.sendEmail.length >= 1
        )
          this.inputCount = this.sendEmail.length;
        this.checkSendEmail = true;
        let userInfo = JSON.parse(localStorage.getItem("USER_INFO"));
        let formData = new FormData();
        formData.append("message", this.content);
        for (let i = 0; i < this.sendEmail.length; i++) {
          formData.append(`emails[${i}]`, this.sendEmail[i]);
        }
        formData.append(`invite_user_profile_id`, userInfo.profile_id);
        if (this.sendEmail.length > 0) {
          this.emitter.emit("isShowLoading", true);
          inviteUser(formData)
            .then((res) => {
              notification.success({
                message: NOTIFICATION_MESSAGE.INVITE_SUCCESS,
              });
              this.emitter.emit("isShowLoading", false);
              this.sendEmail = [];
              this.content = "";
            })
            .catch((err) => {
              notification.error({
                message: NOTIFICATION_MESSAGE.INVITE_FAIL,
              });
              console.log("there was an error", err);
              this.emitter.emit("isShowLoading", false);
            });
        }
      }
    },
    handleShareLink() {
      this.emitter.emit("isShowLoading", true);
      inviteLink()
        .then((res) => {
          this.setLink(res.data);
          this.setCheckAPI(true);
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },
    async handleCopy() {
      // Navigator clipboard api needs a secure context (https)
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(this.link);
        this.isCopied = false;
        setTimeout(() => {
          this.isCopied = true;
          setTimeout(() => {
            this.isCopied = false;
          }, 800);
        }, 800);
      } else {
        // Use the 'out of viewport hidden text area' trick
        const textArea = document.createElement("textarea");
        textArea.value = this.link;
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
    getStatus(item, index) {
      if (item && index == item.index) {
        return !item.status;
      }
    },
    validateEmail(item) {
      let isValidEmail = validEmail(item);
      if (isValidEmail == null) {
        return false;
      } else {
        return true;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Invitation.scss";
</style>
