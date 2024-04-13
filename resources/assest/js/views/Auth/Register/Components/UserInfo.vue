<template lang="">
  <form class="authentication-form-input lcf-username">
    <span class="fz-16 font-weight-600 w-100 pl-10">ユーザー情報</span>
    <div class="w-100 d-flex mt-20">
      <div
        class="w-40 h-45-px fz-16 bg-color-white2 d-flex align-items-center justify-content-center mr-20 text-global"
      >
        名前 (登録名)
      </div>
      <div class="w-100 d-flex gap-10">
        <input
          class="form-control h-45-px"
          type="text"
          placeholder="姓"
          v-model="lastName"
          ref="lastName"
        />
        <input
          class="form-control h-45-px"
          type="text"
          placeholder="名"
          v-model="firstName"
          ref="firstName"
        />
      </div>
    </div>
    <div class="w-100 d-flex mt-20">
      <div
        class="w-40 fz-16 bg-color-white2 d-flex align-items-center justify-content-center mr-20 text-global"
      >
        電話番号
      </div>
      <div class="d-flex flex-column w-100">
        <input
          class="form-control h-45-px w-100"
          type="text"
          placeholder="例)123456789"
          v-model="phoneNumber"
          @keydown="validNumber"
          maxlength="11"
          ref="phoneNumber"
        />
        <div class="fz-16 font-weight-300 mt-20">
          ハイフン(-) を除いた10~11文字の半角数字
        </div>
      </div>
    </div>
    <div class="w-100 d-flex mt-20">
      <div
        class="w-40 h-45-px fz-16 bg-color-white2 d-flex align-items-center justify-content-center mr-20 text-global"
      >
        メールアドレス
      </div>
      <div class="h-45-px w-100 fz-16 d-flex align-items-center text-global">
        {{ email }}
      </div>
    </div>
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
        />
        <div class="fz-16 font-weight-300 mt-20">
          半角の英字と数字を含む8文字以上の文字列
        </div>
      </div>
    </div>
    <div class="fz-16 font-weight-300 mt-40 d-flex">
      <div class="text-global">利用規約とプライバシーポリシー</div>
      <div>をご確認の上、登録手続きを進めてください。</div>
    </div>
    <button type="button" @click="onRegister" class="lcf-submit w-35 flex">
      同意して始める
    </button>
  </form>
</template>
<script>
import { ValidateNumInput } from "../../../../mixins/ValidateNumInput";
import { isValidPhoneNum } from "../../../../utils/helper";
import { validPassword } from "../../../../utils/helper";
import { getEmailByID, createUser } from "../../../../apis/account/user";
import { mapMutations, mapState } from "vuex";
import { login } from "../../../../apis/auth/auth";
import { HOME_ROUTE, LOCAL_STORAGE_USER_INFO } from "../../../../constants";
import { addContact } from "../../../../apis/invite/invite";

export default {
  mixins: [ValidateNumInput],
  created() {
    this.emailID = this.$route.params.id;
    if (this.emailID) {
      this.setIndexChoose(2);
      this.getEmail(this.emailID);
    }
    if (this.$route.params.profile_id) {
      this.profileID = this.$route.params.profile_id;
      this.setIndexChoose(2);
      this.getEmail(this.$route.params.email_token);
    }
  },
  data() {
    return {
      firstName: "",
      lastName: "",
      phoneNumber: "",
      password: "",
      isSubmit: false,
      emailID: null,
      email: null,
      profileID: null,
    };
  },
  watch: {
    firstName() {
      this.$refs.firstName.classList.add("error-input-email");
      this.$refs.firstName.classList.remove("error-input-email");
    },
    lastName() {
      this.$refs.lastName.classList.remove("error-input-email");
      this.$refs.lastName.classList.remove("error-input-email");
    },
    phoneNumber() {
      this.$refs.phoneNumber.classList.remove("error-input-email");
      this.$refs.phoneNumber.classList.remove("error-input-email");
    },
    password() {
      this.$refs.password.classList.remove("error-input-email");
      this.$refs.password.classList.remove("error-input-email");
    },
  },
  methods: {
    ...mapMutations("account", ["setIndexChoose", "setInforRegister"]),
    /**
     * get email by ID
     */
    getEmail(emailID) {
      const data = {
        email_verify_token: emailID,
      };
      this.emitter.emit("isShowLoading", true);
      getEmailByID(data)
        .then((res) => {
          this.email = res.data;
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          this.emitter.emit("isShowLoading", false);
          if (err.response && err.response.status === 404) {
            this.$router.push("/404");
          }
          console.log("there was an error", err);
        });
    },
    /**
     * On next step
     */
    onRegister() {
      this.onValidateFirstName();
      this.onValidateLastName();
      this.validPhoneNum();
      this.validatePassword();
      if (
        this.onValidateFirstName() &&
        this.onValidateLastName() &&
        this.validPhoneNum() &&
        this.validatePassword()
      ) {
        this.emitter.emit("isShowLoading", true);
        this.setInforRegister({ email: this.email, password: this.password });
        createUser({
          first_name: this.firstName,
          last_name: this.lastName,
          email: this.email,
          password: this.password,
          mobile: this.phoneNumber,
        })
          .then((res) => {
            this.setIndexChoose(3);
            this.handleLogin();
          })
          .catch((err) => {
            this.emitter.emit("isShowLoading", false);
            console.log("there was an error", err);
          });
        this.$emit("onNext");
      }
    },
    handleLogin() {
      const authentication = new FormData();
      authentication.append("email", this.email);
      authentication.append("password", this.password);
      this.emitter.emit("isShowLoading", true);
      login(authentication)
        .then((res) => {
          if (this.profileID) this.handleAddContact();
          localStorage.setItem(
            LOCAL_STORAGE_USER_INFO,
            JSON.stringify(res.data.user)
          );
          localStorage.setItem(
            "fullName",
            res.data.user.last_name + " " + res.data.user.first_name
          );
          localStorage.setItem("isLogin", true);
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          this.msg = err.response.data.error;
          this.emitter.emit("isShowLoading", false);
        });
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
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },
    /**
     * Check empty first name
     */
    onValidateFirstName() {
      if (!this.firstName) {
        this.$refs.firstName.classList.add("error-input-email");
        return false;
      } else {
        this.$refs.firstName.classList.remove("error-input-email");
        return true;
      }
    },

    /**
     * Check empty last name
     */
    onValidateLastName() {
      if (!this.lastName) {
        this.$refs.lastName.classList.add("error-input-email");
        return false;
      } else {
        this.$refs.lastName.classList.remove("error-input-email");
        return true;
      }
    },

    /**
     * Check phone is valid for japan
     */
    validPhoneNum() {
      const isPhoneValid =
        isValidPhoneNum(this.phoneNumber) &&
        this.isJapanese(this.phoneNumber) &&
        this.phoneNumber[0] == "0";
      if (isPhoneValid) {
        this.$refs.phoneNumber.classList.remove("error-input-email");
        return true;
      } else {
        this.$refs.phoneNumber.classList.add("error-input-email");
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
