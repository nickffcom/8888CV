<template lang="">
  <div class="content-left d-flex flex-column h-100">
    <div v-for="i in data" :key="i.id" class="d-flex flex-column mr-25 pt-15">
      <span class="fz-16 content-left__title mb-15">{{ i.title }}</span>
      <div
        v-for="(item, key) in i.subData"
        :key="key"
        :class="[
          'content-left__item d-flex flex-column mb-5 position-relative',
          { active: isActive(i.id, item.id) },
        ]"
        @click="handleMeetingClick(item, i.id, item.id)"
      >
        <slot name="content">
          <span
            class="content-left__content fz-14 lh-35 h-35-px text-left pl-20 cur-p"
          >
            {{ item.subTittle }}
          </span>
        </slot>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "../../components/Avatar.vue";
import { mapMutations, mapState } from "vuex";

export default {
  name: "ContentLeft",
  components: {
    Avatar,
  },
  props: {
    data: { type: Array },
    extendClass: { type: String, default: "" },
  },
  data() {
    return {};
  },
  computed: {
    ...mapState("account", ["activeKey"]),
  },

  methods: {
    ...mapMutations("account", ["setActive", "isActive"]),
    isActive(index, subIndex) {
      const isActive =
        index === this.activeKey.index && subIndex === this.activeKey.subIndex;
      return isActive ? "active" : "";
    },

    handleMeetingClick(item, index, subIndex) {
      this.setActive({ index, subIndex });
      this.$emit("clicked", item);
    },
  },
};
</script>
<style lang="scss" scoped></style>
