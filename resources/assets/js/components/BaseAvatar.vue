<template lang="">
  <div
    class="base-avatar fz-14 w-35-px h-35-px d-flex justify-content-center align-items-center radius-50-percent"
    :class="externalClass"
  >
    <img v-if="imgUrl" :src="imgUrl" alt="base-avatar" class="img-fluid" />
    <span v-else>{{ firstCharName }}</span>
  </div>
</template>
<script>
export default {
  props: {
    imgUrl: {
      type: String,
      required: true,
    },
    externalClass: {
      type: String,
      default: "",
    },
    name: {
      type: String,
      default: "",
    },
  },
  computed: {
    firstCharName() {
      return this.name.slice(0, 1).toUpperCase();
    },
  },
};
</script>
<style lang="scss" scoped>
.base-avatar {
  overflow: hidden;
  box-shadow: 0 4px 16px rgba($color: #000000, $alpha: 0.1);

  img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    border-radius: 50%;
  }
}
</style>
