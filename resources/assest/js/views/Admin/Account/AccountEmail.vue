<template lang="">
  <TableAccount :data="dataEmail" :rowData="rowDataEmail">
    <template #currentEmail>
      <div class="d-flex flex-column flex-grow-1 mr-40">
        <input
          type="email"
          class="w-100 h-40-px form-control pl-12"
          spellcheck="false"
          v-model="rowDataEmail.currentEmail"
          disabled
        />
      </div>
    </template>
    <template #newEmail>
      <div class="d-flex flex-column flex-grow-1 mr-40">
        <input
          type="email"
          class="w-100 h-40-px form-control pl-12"
          spellcheck="false"
          v-model="rowDataEmail.newEmail"
          ref="newEmail"
        />
        <span class="notify-error" v-if="newEmailError">
          {{ ERROR_MESSAGE.emailError }}
        </span>
        <span class="notify-error" v-if="isDuplicatesEmail">
          {{ ERROR_MESSAGE.emailDuplicates }}
        </span>
      </div>
    </template>
    <template #confirmNewEmail>
      <div class="d-flex flex-column flex-grow-1 mr-40">
        <input
          type="email"
          class="w-100 h-40-px form-control pl-12"
          spellcheck="false"
          v-model="rowDataEmail.confirmNewEmail"
          ref="confirmNewEmail"
        />
        <span class="notify-error" v-if="confirmNewEmailError">
          {{ ERROR_MESSAGE.confirmEmailError }}
        </span>
      </div>
    </template>
  </TableAccount>
  <div class="text-black1 mt-10">
    新しいメールアドレスに送信される確認メール内の認証用
    URLにアクセスすることで、 正式に変更手続きが完了します。
  </div>
  <div class="text-black1">「変更」をクリック後、 メールをご確認ください。</div>
  <div class="text-black1 mt-20">
    ログインメールアドレスを変更すると自動的にログアウトされます。
  </div>
  <button class="status-primary-outline mt-25" @click="handleSubmit">
    変更
  </button>
</template>
<script>
import TableAccount from "../../../components/TableAccount.vue";
import { RENDER_TYPE } from "../../../constants/table";
import { ERROR_MESSAGE } from "../../../constants";
import { validEmail, validConfirmEmail } from "../../../utils/helper";

export default {
  name: "AccountEmail",
  components: {
    TableAccount,
  },
  props: {
    currentEmailProps: { type: String },
    newEmailProps: { type: String },
    confirmNewEmailProps: { type: String },
  },
  created() {
    this.RENDER_TYPE = RENDER_TYPE;
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    this.rowDataEmail = {
      ...this.rowDataEmail,
      currentEmail: this.currentEmailProps,
      newEmail: this.newEmailProps,
      confirmNewEmail: this.confirmNewEmailProps,
    };
  },
  emits: ["update"],
  watch: {
    "rowDataEmail.newEmail"() {
      this.$refs.newEmail?.classList.add("error-input-email");
      this.$refs.newEmail?.classList.remove("error-input-email");
      this.newEmailError = false;
    },
    "rowDataEmail.confirmNewEmail"() {
      this.$refs.confirmNewEmail?.classList.add("error-input-email");
      this.$refs.confirmNewEmail?.classList.remove("error-input-email");
      this.confirmNewEmail = false;
    },
  },
  data() {
    return {
      newEmailError: false,
      confirmNewEmailError: false,
      isDuplicatesEmail: false,
      dataEmail: [
        {
          classTitle: "text-center text-global",
          title: "現在のメールアドレス",
          renderType: RENDER_TYPE.slot,
          slotName: "currentEmail",
        },
        {
          classTitle: "text-center text-global",
          title: "新しいメールアドレス",
          renderType: RENDER_TYPE.slot,
          slotName: "newEmail",
        },
        {
          classTitle: "text-center text-global",
          title: "新しいメールアドレス(確認)",
          renderType: RENDER_TYPE.slot,
          slotName: "confirmNewEmail",
        },
      ],
      rowDataEmail: {
        currentEmail: "",
        newEmail: "",
        confirmNewEmail: "",
      },
    };
  },
  methods: {
    /**
     * Check new email
     */
    handleCheckNewEmail() {
      const isValidNewEmail = validEmail(this.rowDataEmail.newEmail);
      if (isValidNewEmail) {
        this.$refs.newEmail.classList.remove("error-input-email");
        this.newEmailError = false;
        return true;
      } else {
        this.$refs.newEmail.classList.add("error-input-email");
        this.newEmailError = true;
        return false;
      }
    },
    /**
     * Check new confirm email
     */
    handleCheckConfirmNewEmail() {
      const isValidConfirmNewEmail = validConfirmEmail(
        this.rowDataEmail.newEmail,
        this.rowDataEmail.confirmNewEmail
      );
      if (isValidConfirmNewEmail) {
        this.$refs.confirmNewEmail.classList.remove("error-input-email");
        this.confirmNewEmailError = false;
        return true;
      } else {
        this.$refs.confirmNewEmail.classList.add("error-input-email");
        this.confirmNewEmailError = true;
        return false;
      }
    },
    /**
     * Check current email for duplicates new email
     */
    handleCheckCurrentEmail() {
      if (this.rowDataEmail.newEmail === this.rowDataEmail.currentEmail) {
        this.isDuplicatesEmail = true;
        this.newEmailError = false;
        this.$refs.newEmail.classList.add("error-input-email");
        return true;
      } else {
        this.isDuplicatesEmail = false;
        return false;
      }
    },
    /**
     * Emit update user email
     */
    handleSubmit() {
      this.handleCheckCurrentEmail();
      this.handleCheckNewEmail();
      this.handleCheckConfirmNewEmail();
      if (
        this.handleCheckNewEmail() &&
        this.handleCheckConfirmNewEmail() &&
        !this.handleCheckCurrentEmail()
      ) {
        this.$emit("update", {
          email: this.rowDataEmail.newEmail,
          confirm_email: this.rowDataEmail.confirmNewEmail,
        });
        this.rowDataEmail.currentEmail = this.rowDataEmail.newEmail;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../Auth/Login/Login.scss";

.notify-error {
  color: red;
}
</style>
