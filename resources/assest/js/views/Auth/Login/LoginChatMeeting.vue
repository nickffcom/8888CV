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
          class="d-flex flex-column w-700-px align-items-center flex-column bg-color-white p-30 radius-30-px"
        >
          <div
            class="d-flex mt-15 justify-content-center align-items-center flex-column"
          >
            <span class="fz-24 font-weight-600 mb-20">
              チャットミーティングへ招待されました
            </span>
          </div>
          <div class="text-black1 fz-16 mb-20">
            下の「新規登録(無料)」 から Chatmeeting の利用を開始しましょう。
          </div>
          <button class="btn btn-primary w-170-px" @click="goToRegister">
            新規登録 (無料)
          </button>
          <div
            class="d-flex mt-30 w-70 justify-content-center align-items-center"
          >
            <div class="d-flex flex-grow-1 mr-50 bg-color-grey3 h-1-px" />
            <div>すでに登録されている方</div>
            <div class="d-flex flex-grow-1 ml-50 bg-color-grey3 h-1-px" />
          </div>
          <div class="text-global mt-20 cur-p" @click="goToLogin">ログイン</div>
        </div>
      </div>
    </div>
  </div>
  <LoadingScreen v-if="isLoading" />
</template>
<script>
import LoginLogo from "../../../components/Logo.vue";
import { LoadingMixins } from "../../../mixins/Loading";
import LoadingScreen from "../../../components/LoadingScreen.vue";
import { getDetailMeetingByLinkID } from "../../../apis/meeting/meeting";

export default {
  name: "LoginChatMeeting",
  components: {
    LoginLogo,
    LoadingScreen,
  },
  mixins: [LoadingMixins],
  data() {
    return {
      isLoading: false,
      profileID: null,
      emailToken: null,
    };
  },
  created() {
    this.profileID = this.$route.params.profile_id;
    this.emailToken = this.$route.params.email_token;
    console.log(this.$route.params);
    // this.emitter.emit("isShowLoading", true);
    // getDetailMeetingByLinkID(params)
    //   .then((res) => {
    //     console.log(res.data);
    //   })
    //   .catch((err) => {
    //     console.log("there was an error", err);
    //     this.emitter.emit("isShowLoading", false);
    //   });
  },
  methods: {
    goToRegister() {
      this.$router.push({
        name: "RegisterInvite",
        params: { email_token: this.emailToken, profile_id: this.profileID },
      });
    },
    goToLogin() {
      this.$router.push({
        name: "Login",
        params: { profile_id: this.profileID },
      });
    },
  },
};
</script>
<style lang="scss">
@import "./Login.scss";
</style>
