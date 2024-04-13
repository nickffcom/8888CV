<template lang="">
  <BaseSidebar @resize="onResizeSidebar" :isResize="isResize" />
  <div :class="!isResize ? 'content' : 'content-resize'">
    <BaseHeader />
    <div class="layout">
      <div class="layout-content">
        <router-view />
      </div>
    </div>
  </div>
  <div id="sidebar-overlay" @click="onCollapse" />
  <LoadingScreen v-if="isLoading" />
</template>
<script>
import BaseSidebar from "../../../components/Admin/BaseSidebar.vue";
import BaseHeader from "../../../components/Admin/BaseHeader.vue";
import { LoadingMixins } from "../../../mixins/Loading";
import LoadingScreen from "../../../components/LoadingScreen.vue";
import { notification } from "ant-design-vue";

export default {
  name: "DashboardAdmin",
  components: {
    BaseSidebar,
    BaseHeader,
    LoadingScreen,
  },
  setup() {
    notification.config({
      top: "75px",
    });
  },
  mixins: [LoadingMixins],
  data() {
    return {
      isResize: false,
      windowWidth: window.innerWidth,
      isLoading: false,
    };
  },

  mounted() {
    this.$nextTick(() => {
      window.addEventListener("resize", this.updateWindowWidth);
    });
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.updateWindowWidth);
  },

  watch: {
    windowWidth(newWidth) {
      if (newWidth <= 990 && this.isResize === true) {
        this.isResize = false;
      }
    },
  },
  methods: {
    onResizeSidebar() {
      this.isResize = !this.isResize;
    },
    updateWindowWidth() {
      this.windowWidth = window.innerWidth;
    },
    onCollapse() {
      if (this.windowWidth < 996) {
        $('[data-widget="pushmenu"]').PushMenu("collapse");
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.content {
  padding-left: 250px;
  transition: all 0.3s ease-in-out;

  @include res_max-width(992) {
    padding-left: 0;
  }
}
.content-resize {
  width: 100%;
  padding-left: 80px;
  transition: all 0.3s ease-in-out;
  @include res_max-width(992) {
    padding-left: 0;
  }
}

.layout {
  // box-shadow: inset 11px 0 9px -7px rgb(0 0 0 / 10%),
  //   inset 0px 11px 9px -7px rgb(0 0 0 / 10%);
  min-height: 100vh;
  padding-right: 1.5rem;
  border-left: 1px solid #eee;
  border-top: 1px solid #eee;
  background-color: $white;
  .layout-content {
    border-radius: 15px;
    width: 100%;
    min-height: 100%;
  }
}
</style>
