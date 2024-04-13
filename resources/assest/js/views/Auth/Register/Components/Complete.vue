<template lang="">
  <form class="authentication-form-input lcf-username">
    <div class="d-flex mt-30 w-100 justify-content-center align-items-center">
      <div class="login-line d-flex flex-grow-1 mr-50" />
      <div class="circle">
        <img :src="CHECK_COLOR_ICON" alt="" class="w-70-px h-70-px" />
      </div>
      <div class="login-line d-flex flex-grow-1 ml-50" />
    </div>
    <span class="fz-24 font-weight-600 mt-40 text-global">
      新規会員登録が完了しました。
    </span>
    <span class="fz-14 mt-40 font-weight-300 w-70 mt-40 text-center lh-20">
      Chatmeetingに登録いただきありがとうございます。 引き続き、Chatmeeting
      をお楽しみください。
    </span>
    <button
      @click="handleDashboard"
      type="button"
      class="lcf-submit mt-40 w-35 flex"
    >
      ダッシュボードへ
    </button>
  </form>
</template>
<script>
import { CHECK_COLOR_ICON } from "../../../../constants/imageConst";
import { HOME_ROUTE } from "../../../../constants";

export default {
  created() {
    this.CHECK_COLOR_ICON = CHECK_COLOR_ICON;
  },
  data() {
    return {};
  },
  methods: {
    handleDashboard() {
      window.location.href = HOME_ROUTE;
    },
  },
};
</script>
<style lang="scss" scoped>
.circle {
  border-radius: 50px;
  height: 90px;
  width: 90px;
  border: 4px solid $primary-color;
  display: flex;
  align-items: center;
  justify-content: center;
}
.login-line {
  width: auto;
}
</style>
