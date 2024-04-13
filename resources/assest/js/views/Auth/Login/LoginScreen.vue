<template lang="">
  <div class="d-flex vh-100 login flex-column">
    <LoginLogo
      externalClass="h-100-px w-300-px mx-auto mt-30 mb-30 fz-30"
      title="ロゴ"
    />
    <div
      class="login-form h-100 d-flex justify-content-start align-items-center flex-column bg-color-grey2"
    >
      <div
        class="d-flex justify-content-center align-items-start flex-row w-100 login-container mt-60"
      >
        <!-- Login with member -->
        <div
          class="d-flex flex-column w-50 align-items-center flex-column bg-color-white p-30 radius-30-px"
        >
          <div
            class="d-flex mt-15 justify-content-center align-items-center flex-column"
          >
            <span class="fz-24 font-weight-600 mb-30">ログイン</span>
          </div>
          <LoginForm
            :displayRegister="true"
            :displayAccount="true"
            :displayRecaptCha="true"
            textForgetPassword="パスワードを忘れてしまった方は"
            textRegisterNewMember="会員登録する方は"
          />
        </div>
      </div>
    </div>
  </div>
  <LoadingScreen v-if="isLoading" />
</template>
<script>
import LoginLogo from "../../../components/Logo.vue";
import LoginForm from "./components/LoginForm.vue";
import { LoadingMixins } from "../../../mixins/Loading";
import LoadingScreen from "../../../components/LoadingScreen.vue";
import { getUserDetail } from "../../../apis/account/user";
import {
  HOME_ROUTE,
  LOCAL_STORAGE_REFRESHTOKEN,
  LOCAL_STORAGE_TOKEN,
  LOCAL_STORAGE_USER_INFO,
} from "../../../constants";

export default {
  name: "Login",
  components: {
    LoginLogo,
    LoginForm,
    LoadingScreen,
  },
  mixins: [LoadingMixins],
  mounted() {
    /**
     * Login with social network
     */
    if (Object.keys(this.$route.query).length !== 0) {
      console.log(this.$route.query);
      const access_token = this.$route.query.access_token;
      const refresh_token = this.$route.query.refresh_token;
      this.loginWithSocialNetwork(access_token, refresh_token);
    }
  },
  data() {
    return {
      isLoading: false,
    };
  },
  methods: {
    loginWithSocialNetwork(access_token, refresh_token) {
      try {
        this.handleFetchUserDetail();
        localStorage.setItem(LOCAL_STORAGE_TOKEN, access_token);
        localStorage.setItem(LOCAL_STORAGE_REFRESHTOKEN, refresh_token);
        localStorage.setItem("isLogin", true);
      } catch (error) {
        console.warn(error);
      }
    },
    handleFetchUserDetail() {
      getUserDetail()
        .then((res) => {
          localStorage.setItem(
            LOCAL_STORAGE_USER_INFO,
            JSON.stringify(res.data)
          );
          window.location.href = HOME_ROUTE;
        })
        .catch((err) => {
          console.log("There was an error", err);
        });
    },
  },
};
</script>
<style lang="scss">
@import "./Login.scss";
</style>
