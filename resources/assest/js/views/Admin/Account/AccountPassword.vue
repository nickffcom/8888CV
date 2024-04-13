<template lang="">
  <TableAccount :data="dataPassword" :rowData="rowDataPassword">
    <template #currentPassword>
      <div class="d-flex flex-column flex-grow-1 mr-40 pt-10 pb-10">
        <input
          type="password"
          class="h-40-px form-control pl-12"
          spellcheck="false"
          v-model="rowDataPassword.currentPassword"
          ref="currentPassword"
        />
        <span class="notify-error" v-if="passwordError">
          {{ ERROR_MESSAGE.passwordError }}
        </span>
        <span class="notify-error" v-if="wrongPassword">
          {{ ERROR_MESSAGE.wrongPassword }}
        </span>
      </div>
    </template>
    <template #newPassword>
      <div class="d-flex flex-column flex-grow-1 mr-40 pt-10 pb-10">
        <input
          type="password"
          class="h-40-px form-control pl-12"
          spellcheck="false"
          v-model="rowDataPassword.newPassword"
          ref="newPassword"
        />
        <span class="text-black1">
          (半角の英字と数字を含む、 8文字以上の文字列)
        </span>
        <span class="notify-error" v-if="newPasswordError">
          {{ ERROR_MESSAGE.passwordError }}
        </span>
        <span class="notify-error" v-if="isDuplicatesPassword">
          {{ ERROR_MESSAGE.passwordDuplicates }}
        </span>
      </div>
    </template>
    <template #confirmNewPassword>
      <div class="d-flex flex-column flex-grow-1 mr-40 pt-10 pb-10">
        <input
          type="password"
          class="h-40-px form-control pl-12"
          spellcheck="false"
          v-model="rowDataPassword.confirmNewPassword"
          ref="confirmNewPassword"
        />
        <span class="notify-error" v-if="confirmNewPasswordError">
          {{ ERROR_MESSAGE.confirmPasswordError }}
        </span>
      </div>
    </template>
  </TableAccount>
  <div class="text-black1 mt-10">
    パスワードを変更すると、自動的にログアウトされます。
    新しいパスワードで再度ログインしてください。
  </div>
  <button class="status-primary-outline mt-25" @click="handleSubmit">
    変更
  </button>
</template>
<script>
import TableAccount from "../../../components/TableAccount.vue";
import { ERROR_MESSAGE } from "../../../constants";
import { RENDER_TYPE } from "../../../constants/table";
import { validPassword, validConfirmPassword } from "../../../utils/helper";

export default {
  name: "AccountPassword",
  components: {
    TableAccount,
  },
  props: {
    currentPasswordProps: { type: String },
    newPasswordProps: { type: String },
    confirmNewPasswordProps: { type: String },
  },
  created() {
    this.RENDER_TYPE = RENDER_TYPE;
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    this.rowDataPassword = {
      ...this.rowDataPassword,
      currentPassword: this.currentPasswordProps,
      newPassword: this.newPasswordProps,
      confirmNewPassword: this.confirmNewPasswordProps,
    };
  },
  emits: ["update"],
  watch: {
    "rowDataPassword.currentPassword"() {
      this.passwordError = false;
      this.wrongPassword = false;
      this.$refs.currentPassword?.classList.add("error-input-email");
      this.$refs.currentPassword?.classList.remove("error-input-email");
    },
    "rowDataPassword.newPassword"() {
      this.newPasswordError = false;
      this.isDuplicatesPassword = false;
      this.$refs.newPassword?.classList.add("error-input-email");
      this.$refs.newPassword?.classList.remove("error-input-email");
    },
    "rowDataPassword.confirmNewPassword"() {
      this.confirmNewPasswordError = false;
      this.$refs.confirmNewPassword?.classList.add("error-input-email");
      this.$refs.confirmNewPassword?.classList.remove("error-input-email");
    },
  },
  data() {
    return {
      passwordError: false,
      newPasswordError: false,
      confirmNewPasswordError: false,
      isDuplicatesPassword: false,
      wrongPassword: false,
      dataPassword: [
        {
          classTitle: "text-center text-global",
          title: "現在のパスワード",
          renderType: RENDER_TYPE.slot,
          slotName: "currentPassword",
        },
        {
          classTitle: "text-center text-global",
          title: "新しいパスワード",
          renderType: RENDER_TYPE.slot,
          slotName: "newPassword",
        },
        {
          classTitle: "text-center text-global",
          title: "新しいパスワード(確認)",
          renderType: RENDER_TYPE.slot,
          slotName: "confirmNewPassword",
        },
      ],
      rowDataPassword: {
        currentPassword: "",
        newPassword: "",
        confirmNewPassword: "",
      },
    };
  },
  methods: {
    /**
     * Check new password
     */
    handleCheckNewPassword() {
      const isValidNewPassword = validPassword(
        this.rowDataPassword.newPassword
      );
      if (isValidNewPassword) {
        this.$refs.newPassword.classList.remove("error-input-email");
        this.newPasswordError = false;
        return true;
      } else {
        this.$refs.newPassword.classList.add("error-input-email");
        this.newPasswordError = true;
        return false;
      }
    },
    /**
     * Check current password
     */
    handleCheckCurrentPassword() {
      const isValidNewPassword = validPassword(
        this.rowDataPassword.currentPassword
      );

      if (isValidNewPassword) {
        this.$refs.currentPassword.classList.remove("error-input-email");
        this.passwordError = false;
        return true;
      } else {
        this.$refs.currentPassword.classList.add("error-input-email");
        this.passwordError = true;
        return false;
      }
    },
    /**
     * Check new confirm password
     */
    handleCheckConfirmNewPassword() {
      const isValidConfirmNewPassword = validConfirmPassword(
        this.rowDataPassword.newPassword,
        this.rowDataPassword.confirmNewPassword
      );
      if (isValidConfirmNewPassword) {
        this.$refs.confirmNewPassword.classList.remove("error-input-email");
        this.confirmNewPasswordError = false;
        return true;
      } else {
        this.$refs.confirmNewPassword.classList.add("error-input-email");
        this.confirmNewPasswordError = true;
        return false;
      }
    },
    /**
     * Check current Password for duplicates new Password
     */
    handleCheckCurrentPasswordDuplicates() {
      if (
        this.rowDataPassword.newPassword ===
        this.rowDataPassword.currentPassword
      ) {
        this.isDuplicatesPassword = true;
        this.newPasswordError = false;
        this.$refs.newPassword.classList.add("error-input-email");
        return true;
      } else {
        this.isDuplicatesPassword = false;
        return false;
      }
    },
    /**
     * Emit update user password
     */
    handleSubmit() {
      this.handleCheckNewPassword();
      this.handleCheckConfirmNewPassword();
      this.handleCheckCurrentPassword();
      this.handleCheckCurrentPasswordDuplicates();
      if (
        this.handleCheckNewPassword() &&
        this.handleCheckConfirmNewPassword() &&
        this.handleCheckCurrentPassword() &&
        !this.handleCheckCurrentPasswordDuplicates()
      ) {
        this.$emit("update", {
          old_password: this.rowDataPassword.currentPassword,
          new_password: this.rowDataPassword.newPassword,
          confirm_password: this.rowDataPassword.confirmNewPassword,
        });
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
