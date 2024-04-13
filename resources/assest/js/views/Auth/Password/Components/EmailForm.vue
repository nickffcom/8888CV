<template lang="">
  <form class="authentication-form-input lcf-username">
    <span class="fz-14 font-weight-600 w-100 pl-10">メールアドレス</span>
    <div class="afi-input-container">
      <input
        ref="inputEmail"
        placeholder="メールアドレス・"
        name="email"
        type="text"
        maxlength="256"
        class="afi-input"
        v-model="email"
      />
      <label class="afi-label d-flex">
        <div>メールアドレス</div>
        <span class="fz-10 mr-5 mt-1 ml-2 icon-start">★</span>
      </label>
      <span class="error mt-10" ref="errorEmail">
        {{ ERROR_MESSAGE.emailError }}
      </span>
      <span v-if="!isEmailExist" class="error mt-10" ref="errorEmail">
        {{ ERROR_MESSAGE.emailNotExist }}
      </span>
    </div>
    <div class="mt-20 mb-20 fz-14 w-100 pl-10">例) mail@example.com</div>
    <button
      type="button"
      @click="onNextStep"
      class="lcf-submit w-35 flex fz-14"
    >
      続ける
    </button>
    <div
      @click="goToLogin"
      class="mt-30 mb-20 fz-14 w-100 text-center text-global cur-p"
    >
      ログインへ
    </div>
  </form>
</template>
<script>
import { validEmail } from "../../../../utils/helper";
import { ERROR_MESSAGE } from "../../../../constants";
import { forgetPassword } from "../../../../apis/account/user";
import { mapMutations, mapState } from "vuex";

export default {
  data() {
    return {
      email: "",
      isEmailExist: false,
    };
  },
  created() {
    this.ERROR_MESSAGE = ERROR_MESSAGE;
  },
  watch: {
    email() {
      this.validateEmail();
      this.$refs.errorEmail.classList.remove("error-email");
      this.$refs.inputEmail.classList.remove("error-input-email");
    },
  },
  methods: {
    ...mapMutations("account", ["setIndexChoosingStep"]),

    /**
     * Go to login screen
     */
    goToLogin() {
      this.$router.push({ name: "Login" });
    },

    /**
     * Handle forget password
     */
    async handleForgetPassword() {
      this.emitter.emit("isShowLoading", true);
      try {
        await forgetPassword({ email: this.email });
        this.setIndexChoosingStep(1);
        this.isEmailExist = true;
        this.$refs.errorEmail.classList.remove("error-email");
        this.$refs.inputEmail.classList.remove("error-input-email");
        this.emitter.emit("isShowLoading", false);
      } catch (error) {
        console.log(error);
        this.$refs.errorEmail.classList.add("error-email");
        this.$refs.inputEmail.classList.add("error-input-email");
        this.isEmailExist = false;
        this.emitter.emit("isShowLoading", false);
      }
    },
    /**
     * Next to next step
     */
    onNextStep() {
      this.validateEmail();
      if (this.validateEmail()) {
        this.handleForgetPassword();
      }
    },
    /**
     * Validate required of email
     */
    validateEmail() {
      const isValidEmail = validEmail(this.email);
      if (!isValidEmail) {
        this.$refs.errorEmail.classList.add("error-email");
        this.$refs.inputEmail.classList.add("error-input-email");
        return false;
      } else {
        this.$refs.errorEmail.classList.remove("error-email");
        this.$refs.inputEmail.classList.remove("error-input-email");
        return true;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../Login/Login.scss";
</style>
