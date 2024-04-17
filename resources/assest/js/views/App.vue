<template>
  <div>
    <router-view />
  </div>
</template>

<script>
export default {
  name: "App",
  watch: {
    "$route.name": {
      handler: function (newVal) {},
      deep: true,
      immediate: true,
    },
  },
};
</script>
