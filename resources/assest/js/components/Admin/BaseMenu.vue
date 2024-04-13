<template>
  <ul
    class="nav nav-pills nav-sidebar nav-child-indent flex-column"
    data-widget="treeview"
    role="menu"
  >
    <template v-for="(menu, index) in panel">
      <li
        v-bind:class="isResize && 'd-none'"
        v-if="menu.children"
        class="nav-header p-0"
        :key="menu.message"
        @click="onCollapse"
      >
        <hr
          v-if="index === panel.length - 1"
          class="separator h-4-px mb-3 mt-3"
          :key="menu.message"
        />
        <router-link
          :to="menu.to"
          class="nav-link h-40-px mb-2 d-flex align-items-center pl-16-px"
        >
          <p>{{ menu.message }}</p>
        </router-link>
      </li>

      <li v-else class="nav-item" :key="menu.message">
        <router-link
          :to="menu.to"
          @click="onCollapse"
          :class="[
            'nav-link h-40-px d-flex align-items-center gap-10 mb-2',
            isActiveMenu(menu.to, menu.activeLinks),
          ]"
        >
          <img
            v-if="menu.iconPath"
            class="w-25-px h-25-px"
            :src="menu.iconPath"
            alt="navIcon"
          />
          <p v-bind:class="isResize && 'd-none'">{{ menu.message }}</p>
        </router-link>
      </li>

      <li v-if="menu.children" :key="`children_${menu.message}`">
        <ul class="nav">
          <li
            class="nav-item w-100"
            v-for="subMenu in menu.children"
            :key="`submenu_${subMenu.message}`"
            @click="onCollapse"
          >
            <router-link
              :to="subMenu.to"
              :class="[
                'nav-link h-40-px d-flex align-items-center gap-10 mb-1 w-100',
                !subMenu.children &&
                  isActiveMenu(subMenu.to, subMenu.activeLinks),
              ]"
            >
              <img
                v-if="subMenu.iconPath"
                class="w-25-px h-25-px"
                :src="subMenu.iconPath"
                alt="navIcon"
              />
              <div v-else class="w-25-px h-25-px" />
              <p v-bind:class="isResize && 'd-none'">{{ subMenu.message }}</p>
            </router-link>
            <ul
              v-if="subMenu.children"
              :class="[{ 'd-none': isResize }, 'nav-menu-3']"
            >
              <li
                class="w-100"
                v-for="subMenu3 in subMenu.children"
                :key="`submenu_${subMenu3.message}`"
              >
                <router-link
                  :to="subMenu3.to"
                  :class="[
                    'nav-link h-40-px d-flex align-items-center gap-10 mb-1 w-100',
                    isActiveMenu(subMenu3.to, subMenu3.activeLinks),
                  ]"
                >
                  <img
                    v-if="subMenu3.iconPath"
                    class="w-25-px h-25-px"
                    :src="subMenu3.iconPath"
                    alt="navIcon"
                  />
                  <div v-else class="w-25-px h-25-px" />
                  <p v-bind:class="isResize && 'd-none'">
                    {{ subMenu3.message }}
                  </p>
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
      </li>
    </template>
  </ul>
</template>

<script>
export default {
  props: {
    panel: {
      type: Array,
      default: () => [],
    },
    isResize: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      windowWidth: window.innerWidth,
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
  methods: {
    isActiveMenu(checkPath, activeRoute) {
      const currentPath = this.$route.path;
      const currentRouteName = this.$route.name;
      const isActive =
        currentPath === checkPath ||
        (activeRoute &&
          activeRoute.some(
            (route) => route === currentPath || route === currentRouteName
          ));
      return isActive ? "active" : "";
    },
    /**
     * hide sidebar when click change screen and screen less 996
     */
    onCollapse() {
      if (this.windowWidth < 996) {
        $('[data-widget="pushmenu"]').PushMenu("collapse");
      }
    },

    /**
     * update window-width when resize window
     */
    updateWindowWidth() {
      this.windowWidth = window.innerWidth;
    },
  },
};
</script>

<style>
.separator {
  display: list-item;
  background-color: gray;
  opacity: 0.2;
}

.nav-header {
  font-size: 12px !important;
}
.nav-link {
  margin-bottom: 0.5rem !important;
}
.nav-item {
  padding-left: 0.5rem;
  padding-right: 0.5rem;
}
.nav-item p,
.nav-header p {
  color: #615a5a;
}
.nav-menu-3 {
  display: flex;
  flex-wrap: wrap;
  padding-left: 0;
  margin-bottom: 0;
  list-style: none;
}
</style>
