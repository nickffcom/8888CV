<template lang="">
  <form class="authentication-form-input lcf-username">
    <div class="afi-input-container" v-if="displayAccount">
      <div class="position-relative" />
      <div
        @click="enableEdit"
        v-if="enable"
        class="text-global position-absolute text-check"
      >
        編集
      </div>
      <input
        ref="inputEmail"
        placeholder="メールアドレス・"
        name="email"
        type="text"
        maxlength="256"
        class="afi-input"
        v-model="email"
        :disabled="enable"
        spellcheck="false"
      />

      <label class="afi-label">
        メールアドレス
        <span class="fz-10 mr-5 ml-2 position-absolute icon-start">★</span>
      </label>
      <span class="error" ref="errorEmail">
        {{ error.emailErrorMessage }}
      </span>
    </div>
    <div class="afi-input-container" v-if="displayPassword || accuracy">
      <input
        ref="inputPassword"
        placeholder="パスワード・"
        name="password"
        :type="showPassword ? 'text' : 'password'"
        maxlength="256"
        class="afi-input"
        v-model="password"
        spellcheck="false"
      />
      <label class="afi-label">
        パスワード
        <span class="fz-10 mr-5 ml-2">★</span>
      </label>
      <span class="error" ref="errorPassword">
        {{ error.passwordErrorMessage }}
      </span>
      <span class="error-login" v-if="loginError">
        {{ error.loginError }}
      </span>
      <span
        class="toggle-password position-absolute"
        @click="toggleShowPassword"
      >
        <i class="fa-solid fa-eye" v-if="showPassword" />
        <i class="fa-solid fa-eye-slash" v-else />
      </span>
    </div>
    <div v-if="accuracy" class="mt-20 mb-20 text-grey1 fz-16 w-100 text-center">
      パスワードを忘れてしまった方は
      <span class="text-global fz-16 cur-p" @click="goToForgetPassword">
        こちら
      </span>
    </div>
    <div
      v-if="displayTickRemmber"
      class="d-flex h-50-px justify-content-start align-items-center w-100 p-20 mt-10"
    >
      <div
        v-if="isChecked"
        class="h-23-px w-23-px mr-10 cur-p check"
        @click="onHandleChecked"
      >
        <img class="h-20-px w-20-px mr-10 ml-9" :src="CHECK_ICON" alt="" />
      </div>
      <div
        v-else
        class="h-23-px w-23-px mr-10 cur-p check"
        @click="onHandleChecked"
      />
      <div
        class="text-grey1 fz-16 text-left text-checkbox cur-p"
        @click="onHandleChecked"
      >
        次回から自動でログインする
      </div>
    </div>
    <!-- <div class="mb-30 text-center" v-if="checkParam">
      <div
        class="g-recaptcha"
        :data-sitekey="site_key"
        data-callback="onRecaptchaSuccess"
        data-expired-callback="onRecaptchaResponseExpiry"
        data-error-callback="onRecaptchaError"
      />
    </div> -->

    <button
      v-if="checkParam && !accuracy"
      type="button"
      @click="goToAccuracy"
      class="lcf-submit flex"
    >
      続ける
      <i :class="{ wait: waitLogin }" class="fa-solid fa-circle-play" />
    </button>
    <button
      v-else-if="accuracy && checkParam"
      type="button"
      @click="submit"
      class="lcf-submit flex"
    >
      続ける
      <i :class="{ wait: waitLogin }" class="fa-solid fa-circle-play" />
    </button>

    <button v-else type="button" @click="submit" class="lcf-submit flex">
      ログイン
      <i :class="{ wait: waitLogin }" class="fa-solid fa-circle-play" />
    </button>
    <div
      v-if="!showLoginWithSocial && checkParam"
      class="d-flex flex-column justify-content-center align-items-center w-100"
    >
      <div class="d-flex mt-30 w-100 justify-content-center align-items-center">
        <div class="login-line d-flex flex-grow-1 mr-20" />
        <span class="text-grey1 w-150-px">または</span>
        <div class="login-line d-flex flex-grow-1 ml-20" />
      </div>

      <div
        class="d-flex mt-30 w-250-px justify-content-start align-items-center h-40-px border-2-px radius-2-px cur-p"
      >
        <a href="auth/apple">
          <img :src="APPLE" alt="" class="h-15-px mr-20 ml-20 mb-2 lh-15" />
          <span class="text-black1 fz-16">Apple でサインイン</span>
        </a>
      </div>
      <div
        class="d-flex mt-10 w-250-px justify-content-start align-items-center h-40-px border-2-px radius-2-px cur-p"
      >
        <a href="auth/google">
          <img
            :src="GOOGLE_COLOR"
            alt=""
            class="h-15-px mr-20 ml-20 mb-2 lh-15"
          />
          <span class="text-black1 fz-16">Google でサインイン</span>
        </a>
      </div>
    </div>
    <div v-if="displayForgetPassword" class="mt-40">
      <span class="fz-16 text-grey1">{{ textForgetPassword }}</span>
      <span class="text-global fz-16 cur-p" @click="goToForgetPassword">
        こちら
      </span>
    </div>
    <div v-if="displayRegister" class="mt-40">
      <span class="fz-16 text-grey1">{{ textRegisterNewMember }}</span>
      <span class="text-global fz-16 cur-p" @click="goToRegisterNew">
        こちら
      </span>
    </div>
  </form>
</template>
<script>
import { validEmail, validPassword } from "../../../../utils/helper";
import {
  CHECK_ICON,
  GOOGLE_COLOR,
  APPLE,
} from "../../../../constants/imageConst";
import {
  HOME_ROUTE,
  ERROR_MESSAGE,
  LOCAL_STORAGE_USER_INFO,
} from "../../../../constants";
import { login } from "../../../../apis/auth/auth";
import { addContact } from "../../../../apis/invite/invite";

export default {
  name: "LoginForm",
  created() {
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    this.CHECK_ICON = CHECK_ICON;
    this.GOOGLE_COLOR = GOOGLE_COLOR;
    this.APPLE = APPLE;
    this.profileID = this.$route.params.profile_id;
  },
  mounted() {
    let recaptchaScript = document.createElement("script");
    recaptchaScript.setAttribute(
      "src",
      "https://www.google.com/recaptcha/api.js"
    );
    document.head.appendChild(recaptchaScript);
    window.onRecaptchaSuccess = this.onRecaptchaSuccess;
  },
  props: {
    displayTickRemmber: { type: Boolean, default: false },
    displayAccount: { type: Boolean, default: false },
    displayPassword: { type: Boolean, default: false },
    displayForgetPassword: { type: Boolean, default: false },
    displayRegister: { type: Boolean, default: false },
    displayRecaptCha: { type: Boolean, default: false },
    textForgetPassword: { type: String, default: "" },
    textRegisterNewMember: { type: String, default: "" },
  },
  data() {
    return {
      site_key: process.env.MIX_RECAPTCHA_KEY,
      isChecked: false,
      email: "",
      password: "",
      isSubmit: false,
      isAccuracy: false,
      loginError: false,
      waitLogin: false,
      accuracy: false,
      enable: false,
      checkClick: true,
      showPassword: false,
      showLoginWithSocial: false,
      checkedRecaptcha: false,
      error: {
        loginError: ERROR_MESSAGE.loginError,
        emailErrorMessage: ERROR_MESSAGE.emailError,
        passwordErrorMessage: ERROR_MESSAGE.passwordError,
      },
      linkId: this.$route.params.link_id,
      profileID: null,
    };
  },
  watch: {
    email() {
      this.validateEmail();
      this.isSubmit = false;
      this.isAccuracy = false;
    },
    password() {
      this.isSubmit = false;
      this.validatePassword();
    },
  },
  computed: {
    /**
     * check to display recaptcha
     */
    checkParam() {
      return Object.keys(this.$route.params).length === 0
        ? this.checkClick
        : true;
    },
  },
  methods: {
    goToForgetPassword() {
      this.$router.push({ name: "ForgetPassword" });
    },
    goToRegister() {
      this.$router.push({ name: "Register" });
    },
    goToRegisterNew() {
      this.$router.push({ name: "Register" });
    },
    /**
     * Is recaptcha checked?
     */
    onRecaptchaSuccess(response) {
      if (response.length > 0) {
        this.checkedRecaptcha = true;
      } else {
        this.checkedRecaptcha = false;
      }
    },
    /**
     * hide/appear password
     */
    toggleShowPassword() {
      this.showPassword = !this.showPassword;
    },
    /**
     * when user click recaptcha and website go to other place.
     */
    goToAccuracy() {
      this.isAccuracy = true;
      const validEmail = this.validateEmail();
      if (validEmail) {
        this.enable = !this.enable;
        this.accuracy = !this.accuracy;
        // if (!this.checkedRecaptcha) this.checkClick = true;
        // else this.checkClick = false;
        this.checkClick = false;
        this.showLoginWithSocial = !this.showLoginWithSocial;
        this.isAccuracy = false;
      }
    },
    /**
     * enable edit email
     */
    enableEdit() {
      this.enable = !this.enable;
    },
    onHandleChecked() {
      this.isChecked = !this.isChecked;
    },
    validateEmail() {
      const isValidEmail = validEmail(this.email);
      if (
        (!isValidEmail && this.isSubmit) ||
        (!isValidEmail && this.isAccuracy)
      ) {
        this.$refs.errorEmail.classList.add("error-email");
        this.$refs.inputEmail.classList.add("error-input-email");
        return false;
      } else {
        this.$refs.errorEmail.classList.remove("error-email");
        this.$refs.inputEmail.classList.remove("error-input-email");
        return true;
      }
    },
    validatePassword() {
      const isValidPassword = validPassword(this.password);
      if (!isValidPassword && this.isSubmit) {
        this.$refs.errorPassword.classList.add("error-password");
        this.$refs.inputPassword.classList.add("error-input-password");
        return false;
      } else {
        this.$refs.errorPassword.classList.remove("error-password");
        this.$refs.inputPassword.classList.remove("error-input-password");
        return true;
      }
    },

    /**
     * Submit login
     * @returns {Promise<void>}
     *
     */
    submit() {
      this.isSubmit = true;
      const validEmail = this.validateEmail();
      if (validEmail) {
        const authentication = new FormData();
        authentication.append("email", this.email);
        authentication.append("password", this.password);
        this.emitter.emit("isShowLoading", true);
        this.waitLogin = true;
        login(authentication)
          .then((res) => {
            if (this.profileID) this.handleAddContact();
            this.loginError = false;
            localStorage.setItem(
              LOCAL_STORAGE_USER_INFO,
              JSON.stringify(res.data.user)
            );
            localStorage.setItem(
              "fullName",
              `${res.data.user.last_name} ${res.data.user.first_name}`
            );
            localStorage.setItem("isLogin", true);
            if (this.linkId) {
              this.$router.push({
                name: "HomeByLinkID",
                params: { link_id: this.linkId },
              });
            } else {
              window.location.href = HOME_ROUTE;
            }
            this.emitter.emit("isShowLoading", false);
          })
          /**
           * @description Error
           */
          .catch((err) => {
            this.loginError = true;
            this.msg = err.response.data.error;
            this.emitter.emit("isShowLoading", false);
          });
      } else {
        this.loginError = false;
      }
    },
    /**
     * add contact
     */
    handleAddContact() {
      const formData = new FormData();
      formData.append("profile_id", this.profileID);
      this.emitter.emit("isShowLoading", true);
      addContact(formData)
        .then((res) => {
          this.$router.push({ name: "Home" });
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },
  },
};
</script>
<style lang="scss" scoped>
.login-line {
  background-color: #d7d7d7;
  width: 100%;
  height: 2px;
}
.text-check {
  top: 35px;
  width: 40px;
  height: 20px;
  right: 10px;
  cursor: pointer;
  z-index: 1;
}
.error {
  margin: 0 10px;
  line-height: 1.5;
  color: crimson;
  display: none;
}
.error-login {
  margin: 0 10px;
  line-height: 1.5;
  color: crimson;
}
.error-email,
.error-password {
  display: block;
}
.error-input-email,
.error-input-password,
.afi-input:focus.error-input-email:focus,
.afi-input:focus.error-input-password:focus {
  border-color: crimson !important;
}
.g-recaptcha {
  width: 300px;
  margin: 60px auto 0 auto;
  @include res_max-width(375) {
    margin-top: 45px;
    margin-left: -5%;
    transform: scale(0.8);
  }
}

.toggle-password {
  top: 36px;
  width: 40px;
  height: 20px;
  right: 0;
  cursor: pointer;
  z-index: 1;
}
.fa-eye,
.fa-eye-slash {
  color: #c6c2c2;
}

.icon-start {
  top: 2px;
}
</style>
