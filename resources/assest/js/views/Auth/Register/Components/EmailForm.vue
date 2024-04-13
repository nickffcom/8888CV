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
        :disabled="isDisabledInputEmail"
        :class="[isDisabledInputEmail ? 'bg-color-disabled' : '']"
      />
      <label class="afi-label d-flex">
        <div>メールアドレス</div>
        <span class="fz-10 mr-5 mt-1 ml-2 icon-start">★</span>
      </label>
      <span class="error" ref="errorEmail">
        {{ ERROR_MESSAGE.emailError }}
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
    <div class="d-flex mt-30 w-100 justify-content-center align-items-center">
      <div class="login-line d-flex flex-grow-1 mr-20" />
      <span class="text-grey1 text-break fz-14">または</span>
      <div class="login-line d-flex flex-grow-1 ml-20" />
    </div>
    <div
      @click="goToLogin"
      class="mt-80 mb-80 fz-14 w-100 text-center text-global cur-p"
    >
      すでに登録されている方はこちら
    </div>
  </form>
</template>
<script>
import { validEmail } from "../../../../utils/helper";
import { ERROR_MESSAGE } from "../../../../constants";
import { createAccount } from "../../../../apis/account/user";
import { mapMutations, mapState } from "vuex";
import { notification } from "ant-design-vue";
export default {
  data() {
    return {
      email: "",
      isSubmit: false,
      profileID: null,
      isDisabledInputEmail: false,
    };
  },
  props: {
    emailProps: {
      type: String,
      default: "",
    },
    isDisabledInput: {
      type: Boolean,
      default: false,
    },
  },
  created() {
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    if (this.$route.params.profile_id && this.$route.name == "RegisterInvite") {
      this.profileID = this.$route.params.profile_id;
    }
    this.email = this.emailProps;
    this.isDisabledInputEmail = this.isDisabledInput;
  },
  watch: {
    email() {
      this.validateEmail();
      this.$refs.errorEmail.classList.remove("error-email");
      this.$refs.inputEmail.classList.remove("error-input-email");
    },
    emailProps() {
      this.email = this.emailProps;
    },
    isDisabledInput() {
      this.isDisabledInputEmail = this.isDisabledInput;
    },
  },
  methods: {
    ...mapMutations("account", ["setIndexChoose"]),
    goToLogin() {
      this.$router.push({ name: "Login" });
    },
    /**
     * Next to next step
     */
    onNextStep() {
      this.emitter.emit("isShowLoading", true);
      this.isSubmit = true;
      createAccount({
        email: this.email,
        invite_user_profile_id: this.profileID,
      })
        .then((res) => {
          this.setIndexChoose(1);
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          if (err.response && err.response.status === 422) {
            notification.error({ message: ERROR_MESSAGE.emailExist });
          }
          this.emitter.emit("isShowLoading", false);
          console.log("there was an error", err);
        });
      if (this.validateEmail()) {
        this.$emit("onNext");
      }
    },
    /**
     * Validate required of email
     */
    validateEmail() {
      const isValidEmail = validEmail(this.email);
      if (!isValidEmail && this.isSubmit) {
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
