<template lang="">
  <div class="d-flex vh-100 login flex-column">
    <LoginLogo
      externalClass="h-100-px w-300-px mx-auto mt-30 mb-30 fz-30"
      title="ロゴ"
    />
    <div
      class="login-form pb-84 d-flex justify-content-start align-items-center flex-column bg-color-grey2"
    >
      <div
        class="d-flex flex-column justify-content-center align-items-center mt-60 mb-20"
      >
        <div class="fz-24 font-weight-600">チャットミーティング</div>
        <span class="fz-16">PC専用管理画面</span>
      </div>
      <div
        class="d-flex justify-content-center align-items-start flex-row w-100 login-container-link"
      >
        <!-- Login with member -->
        <div
          :class="[
            'd-flex flex-column align-items-center flex-column bg-color-white p-30 radius-30-px ',
            tokenMeeting && 'mr-50 w-100',
            !tokenMeeting && 'max-w-500-px',
          ]"
        >
          <div
            class="d-flex mt-15 justify-content-center align-items-center flex-column"
          >
            <span class="fz-24 font-weight-600 title mb-50">
              会員(メンバー)
            </span>
            <span class="title-sub">
              ログインにはメールアドレス・パスワードの登録が必要です。 まだ登
              録されていない方は、チャットミーティングの「メールアドレス登録」
              から登録してください。
            </span>
          </div>
          <LoginForm
            :displayForgetPassword="true"
            :displayTickRemmber="true"
            :displayAccount="true"
            :displayPassword="true"
            textForgetPassword="パスワードを忘れてしまった方は"
          />
        </div>
        <!-- Login without member -->
        <div
          class="d-flex flex-column w-100 align-items-center flex-column bg-color-white ml-50 p-30 radius-30-px"
          v-if="tokenMeeting"
        >
          <div
            class="d-flex mt-15 justify-content-center align-items-center flex-column"
          >
            <span class="fz-24 font-weight-600 mb-50">ゲスト</span>
            <span class="title-sub">
              ゲストとしてサービス利用される方は下記のログインボタンをクリ
              ックしてください。 ゲストでログインすると過去の履歴が閲覧できな
              くなる可能性ございます。
            </span>
          </div>
          <button type="button" @click="submit" class="lcf-submit flex">
            ログイン
            <i class="fa-solid fa-circle-play" />
          </button>
          <div class="mt-40">
            <span class="fz-16 text-grey1">会員登録する方は</span>
            <span class="text-global fz-16 cur-p" @click="goToRegister">
              こちら
            </span>
          </div>
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

export default {
  name: "LoginWeb",
  components: {
    LoginLogo,
    LoginForm,
    LoadingScreen,
  },
  mixins: [LoadingMixins],
  created() {
    if (localStorage.getItem("isLogin") === "true") {
      this.$router.push({
        name: "HomeByLinkID",
        params: { link_id: this.$route.params.link_id },
      });
    }
  },
  data() {
    return {
      isLoading: false,
      tokenMeeting: this.$route.params.token,
    };
  },
  methods: {
    goToRegister() {
      this.$router.push({
        name: "RegisterMeetingLink",
        params: { token_guest: this.tokenMeeting },
      });
    },
    submit() {
      this.$router.push({
        name: "MeetingGuest",
        params: { link_id: this.$route.params.link_id },
      });
    },
  },
};
</script>
<style lang="scss">
@import "./Login.scss";
</style>
