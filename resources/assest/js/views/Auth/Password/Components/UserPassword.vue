<template lang="">
  <form class="authentication-form-input lcf-username">
    <span class="fz-16 font-weight-600 w-100 pl-10">ユーザー情報</span>
    <div class="w-100 d-flex mt-20">
      <div
        class="w-40 fz-16 bg-color-white2 d-flex align-items-center justify-content-center mr-20 text-global"
      >
        パスワード
      </div>
      <div class="d-flex flex-column w-100">
        <input
          class="form-control h-45-px w-100"
          type="password"
          v-model="password"
          ref="password"
          name="password"
          autocomplete
        />
        <div class="fz-16 font-weight-300 mt-20">
          半角の英字と数字を含む8文字以上の文字列
        </div>
      </div>
    </div>

    <div class="w-100 d-flex mt-20">
      <div
        class="w-40 fz-16 bg-color-white2 d-flex align-items-center justify-content-center mr-20 text-global"
      >
        パスワードを認証する
      </div>
      <div class="d-flex flex-column w-100">
        <input
          class="form-control h-45-px w-100"
          type="password"
          v-model="confirmPassword"
          ref="confirmPassword"
          name="confirmPassword"
          autocomplete
        />
        <div class="fz-16 font-weight-300 mt-20">
          パスワードを認証するにはパスワードと一致である必要があります
        </div>
      </div>
    </div>

    <div class="fz-16 font-weight-300 mt-40 d-flex">
      <div class="text-global">利用規約とプライバシーポリシー</div>
      <div>をご確認の上、登録手続きを進めてください。</div>
    </div>
    <button
      type="button"
      @click="onChangePassword"
      class="lcf-submit w-35 flex"
    >
      同意して始める
    </button>
  </form>
</template>
<script>
import { ValidateNumInput } from "../../../../mixins/ValidateNumInput";
import { validConfirmPassword, validPassword } from "../../../../utils/helper";
import { mapMutations, mapState } from "vuex";
import {
  verifyTokenResetPassword,
  resetPassword,
} from "../../../../apis/account/user";

export default {
  mixins: [ValidateNumInput],
  created() {
    this.token = this.$route.params.token;
    if (this.token) {
      this.setIndexChoosingStep(2);
      this.handleVerifyTokenReset();
    }
  },
  data() {
    return {
      password: "",
      confirmPassword: "",
      token: null,
    };
  },
  watch: {
    confirmPassword() {
      this.$refs.confirmPassword?.classList.add("error-input-email");
      this.$refs.confirmPassword?.classList.remove("error-input-email");
    },
    password() {
      this.$refs.password?.classList.add("error-input-email");
      this.$refs.password?.classList.remove("error-input-email");
    },
  },
  methods: {
    ...mapMutations("account", ["setIndexChoosingStep"]),
    /**
     * Handle reset password
     */
    async handleResetPassword() {
      this.emitter.emit("isShowLoading", true);
      try {
        await resetPassword({
          token: this.token,
          new_password: this.password,
          confirm_password: this.confirmPassword,
        });
        this.emitter.emit("isShowLoading", false);
        this.setIndexChoosingStep(3);
      } catch (error) {
        console.log(error);
        this.$router.push("/404");
        this.emitter.emit("isShowLoading", false);
      }
    },
    /**
     * Handle verify token
     */
    async handleVerifyTokenReset() {
      this.emitter.emit("isShowLoading", true);
      try {
        const { data } = await verifyTokenResetPassword({ token: this.token });
        if (!data?.is_verified) {
          this.$router.push("/404");
        }
        this.emitter.emit("isShowLoading", false);
      } catch (error) {
        console.log(error);
        this.emitter.emit("isShowLoading", false);
        this.$router.push("/404");
      }
    },

    /**
     * On next step
     */
    onChangePassword() {
      this.handleCheckConfirmNewPassword();
      this.validatePassword();
      if (this.validatePassword() && this.handleCheckConfirmNewPassword()) {
        this.handleResetPassword();
      }
    },
    /**
     * Check new confirm password
     */
    handleCheckConfirmNewPassword() {
      const isValidConfirmPassword =
        validConfirmPassword(this.password, this.confirmPassword) &&
        validPassword(this.confirmPassword);
      if (isValidConfirmPassword) {
        this.$refs.confirmPassword.classList.remove("error-input-email");
        return true;
      } else {
        this.$refs.confirmPassword.classList.add("error-input-email");
        return false;
      }
    },
    /**
     * Validate Password
     */
    validatePassword() {
      const isPasswordValid = validPassword(this.password);
      if (!isPasswordValid) {
        this.$refs.password.classList.add("error-input-password");
        return false;
      } else {
        this.$refs.password.classList.remove("error-input-password");
        return true;
      }
    },
  },
};
</script>
<style lang="scss">
@import "../../Login/Login.scss";
</style>
