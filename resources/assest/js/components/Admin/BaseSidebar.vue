<template lang="">
  <!-- Main Sidebar Container -->
  <aside
    :class="isResize ? ' main-sidebar-resize' : 'main-sidebar'"
    class="main sidebar-collapse h-100-vh position-fixed overflow-x-hidden sidebar-no-expand"
  >
    <!-- Brand Logo -->
    <div
      class="d-flex mt-30 mb-4 flex-column justify-content-center align-items-center text-gray min-w-max-content"
    >
      <Logo
        :externalClass="
          isResize
            ? 'logo fz-18 radius-50-percent'
            : 'w-80-px h-80-px radius-50-percent fz-18'
        "
        title="ロゴ"
      />
    </div>

    <!-- Sidebar -->
    <div ref="sidebar" class="sidebar p-0" id="js-sidebar">
      <!-- Sidebar Menu -->
      <nav class="mb-5">
        <!-- <BaseMenu v-if="isRoleAdmin" :panel="adminPanel" /> -->
        <BaseMenu :panel="userPanel" :isResize="isResize" />
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <div
    :class="isResize && 'btn-sidebar-collapse-resize'"
    class="nav-item btn-sidebar-collapse position-fixed mb-0 cur-p overflow-hidden hide-on-sp"
    data-widget="pushmenu"
    @click="onResizeSidebar"
  >
    <span class="nav-link h-40-px d-flex align-items-center mb-0 ml-12">
      <img
        :class="['toggle-sidebar-icon', isResize && 'logo-xl-btn-collapse']"
        :src="require('../../../images/icons/icon-11.svg').default"
        alt="navIcon"
      />
      <span
        v-bind:class="isResize && 'd-none'"
        class="text-global text-btn-collapse"
      >
        メニューをしまう
      </span>
    </span>
  </div>
  <a
    class="nav-link top-20-px d-flex position-absolute justify-content-center align-items-center fz-22 extend-icon"
    data-widget="pushmenu"
    href="#"
    role="button"
  >
    <i class="fas fa-bars text-global" />
  </a>
</template>
<script>
import BaseMenu from "./BaseMenu.vue";
// import { LOGO_V } from "../../../js/constants/imageConst";
// import { HOME_ROUTE, ADMIN_DASHBOARD_ROUTE } from "../constants";
import Logo from "../Logo.vue";
import {
  HOME_ICON,
  PENCIL,
  MENU_ICON_2,
  MENU_ICON_3,
  MENU_ICON_4,
} from "../../../js/constants/imageConst";
export default {
  props: {
    isResize: {
      type: Boolean,
      default: false,
    },
  },
  components: {
    BaseMenu,
    Logo,
  },
  emits: ["resize"],
  created() {
    // const indexOfHeadquaterInAdminPanel = 6;
    // const indexOfHeadquaterInSubAdminPanel = 1;
    // this.adminPanel[indexOfHeadquaterInAdminPanel].children[
    //   indexOfHeadquaterInSubAdminPanel
    // ].to = "/shop/" + this.$store.state?.auth.staff.shop_id;
    // this.LOGO_V = LOGO_V;
    // this.HOME_ROUTE = HOME_ROUTE;
    // this.logoRoute = this.isRoleAdmin ? ADMIN_DASHBOARD_ROUTE : HOME_ROUTE;
  },
  data() {
    return {
      //   isRoleAdmin: JSON.parse(localStorage.getItem("isRoleAdmin")),
      //   logoRoute: HOME_ROUTE,
      adminPanel: [],
      userPanel: [
        {
          message: "ダッシュボード",
          to: "/",
          iconPath: HOME_ICON,
          activeLinks: [],
        },
        {
          message: "ミーティング",
          to: "",
          iconPath: "",
          activeLinks: [],
          children: [
            {
              message: "タイムライン",
              to: "/schedule",
              iconPath: MENU_ICON_2,
              activeLinks: [],
            },
            {
              message: "ミーティング作成",
              to: "/meeting",
              iconPath: PENCIL,
              activeLinks: [],
            },
          ],
        },
        {
          message: "リスト",
          to: "",
          iconPath: "",
          activeLinks: [],
          children: [
            {
              message: "メンバー",
              to: "/staffs",
              iconPath: MENU_ICON_3,
              activeLinks: [],
            },
          ],
        },
        {
          message: "マイビジネス",
          to: "",
          iconPath: "",
          activeLinks: [],
          children: [
            {
              message: "マスタ管理",
              to: "/time-setting",
              iconPath: MENU_ICON_4,
              activeLinks: [],
            },
          ],
        },
      ],
    };
  },
  mounted() {
    const scrollTop = sessionStorage.getItem("scrollTop");
    if (this.$refs.sidebar) {
      this.$refs.sidebar.scrollTop = scrollTop;
    }
  },
  methods: {
    onResizeSidebar() {
      this.$emit("resize");
    },
  },
};
</script>
<style lang="scss" scoped>
.sidebar-collapse .name-app {
  margin-left: -10px;
  visibility: hidden;
  opacity: 0;
}
.nav-item {
  z-index: 999;
}
.name-app-close {
  margin-left: 0px;
  opacity: 1;
  visibility: visible;
  transition: margin-left 0.3s linear, opacity 0.3s ease, visibility 0.3s ease;
}
</style>
