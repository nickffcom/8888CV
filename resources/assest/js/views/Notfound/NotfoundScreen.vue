<template lang="">
  <div
    class="d-flex flex-column justify-content-center error-layout not-found fullscreen text-center"
  >
    <div class="icon-wrapper">
      <h2 class="text-404 text-center fz-120 font-weight-bold">4 0 4</h2>
      <img :src="NOT_FOUND" alt="404" class="icon w-140-px d-block mx-auto" />
    </div>
    <div class="error-title font-weight-bold text-center fz-20">
      <div>ページが見つかりません</div>
    </div>
    <div class="error-description fz-16 text-center">
      <div>
        <p>
          ページが移動または削除されたか、URLが間違っている可能性があります。
        </p>
      </div>
    </div>
    <button
      @click="handleGoToHomePage"
      class="d-flex justify-content-center align-items-center w-100 max-w-300-px fz-15 pp-button action-button positive md flex primary draft-0"
    >
      <img
        :src="ICON_HOME_WHITE"
        alt="home_icon d-inline-block max-w-20-px max-h-20-px"
      />
      ホームへ移動する
    </button>
  </div>
</template>
<script>
import { NOT_FOUND, ICON_HOME_WHITE } from "../../constants/imageConst.js";
import { HOME_ROUTE, ADMIN_DASHBOARD_ROUTE } from "../../constants/index.js";

export default {
  name: "NotFound",
  data() {
    return {
      NOT_FOUND,
      ICON_HOME_WHITE,
      HOME_ROUTE: HOME_ROUTE,
      ADMIN_DASHBOARD_ROUTE: ADMIN_DASHBOARD_ROUTE,
      isLogin: JSON.parse(localStorage.getItem("isLogin")),
    };
  },
  methods: {
    handleGoToHomePage() {
      if (this.isLogin) {
        this.$router.push({ name: "Home" });
      } else {
        this.$router.push({ name: "Login" });
      }
    },
  },
};
</script>
