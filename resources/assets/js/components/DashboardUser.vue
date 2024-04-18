<template>
  <div class="layout">
    <Sidebar />
    <!-- <Header /> -->
    <div class="layout-content">
      <router-view />
    </div>
  </div>
  <div id="sidebar-overlay" />
  <LoadingScreen v-if="isLoading" />
</template>

<script>
import { ref } from "vue";
import Header from "../components/User/Header.vue";
import Sidebar from "../components/User/Sidebar.vue";
// import { LoadingMixins } from "../../mixins/Loading";
export default {
  name: "DashboardUser",
  components: {
    Header,
    Sidebar,
  },
  setup() {
    const isLoading = ref(false);
    return { isLoading };
  },
};
</script>
<style lang="scss" scoped></style>
