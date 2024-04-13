<template lang="">
  <div
    class="home__right-header d-flex justify-content-between align-items-center"
  >
    <div v-if="!hideButtonProp" :class="linkWeb == null ? 'visible-copy' : ''">
      <div
        v-if="isCopied"
        class="text-global fz-14 header-left cur-p"
        @click="handleCopy"
      >
        打ち合わせリンクコピーする
      </div>
      <div v-if="!isCopied" class="text-global fz-14 header-left pl-10 pr-10">
        打ち合わせリンクコピーしました
      </div>
    </div>
    <div
      class="header-right d-flex justify-content-between align-items-center h-100"
    >
      <div class="d-flex gap-10 align-items-center justify-content-center">
        <span class="text-grey1">作成者</span>
        <Avatar :imgUrl="avatar" externalClass="w-35-px h-35-px" />
        <div class="d-flex flex-column w-fit-content flex-wrap">
          <span class="header-right__company fz-12 text-grey1">
            {{ company }}
          </span>
          <span class="header-right__name text-grey1">
            {{ name }}
          </span>
        </div>
      </div>
      <span class="header-right__title text-grey1">作成日時</span>
      <div class="d-flex flex-row">
        <span class="text-grey1 header-right__date fz-14">{{ date }}</span>
        <span class="text-grey1 header-right__time fz-14 ml-7">{{ time }}</span>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "../../../components/Avatar.vue";
import { mapMutations, mapState } from "vuex";
export default {
  name: "HomeHeader",
  components: {
    Avatar,
  },
  props: {
    linkCopy: { type: String, default: "" },
    avatar: { type: String, default: "" },
    company: { type: String, default: "" },
    name: { type: String, default: "" },
    date: { type: String, default: "" },
    time: { type: String, default: "" },
    hideButtonProp: { type: Boolean, default: false },
    meetingDetailProps: { type: Object },
  },

  created() {
    this.linkId = this.$route.params.link_id;
    if (this.linkId) {
      this.setShowDetailMeeting(true);
    }
  },
  data() {
    return {
      checkActive: 1,
      isCopied: true,
      meetingLink: window.location.origin,
      linkId: "",
      linkMeeting: "",
    };
  },
  watch: {
    meetingDetailProps(newVal) {
      this.meetingDetail = newVal;
      this.linkMeeting =
        window.location.host +
        "/meeting/" +
        newVal.link_id +
        "/" +
        newVal?.user?.profile_id;
    },
  },
  computed: {
    ...mapState("meeting", ["showDetail", "linkID", "linkWeb"]),
  },
  methods: {
    ...mapMutations("meeting", ["setShowDetailMeeting", "setLinkID"]),
    isActive(index) {
      return index == this.checkActive ? "active" : "";
    },
    async handleCopy() {
      // Navigator clipboard api needs a secure context (https)
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(this.linkMeeting);
        this.isCopied = true;
        setTimeout(() => {
          this.isCopied = false;
          setTimeout(() => {
            this.isCopied = true;
          }, 800);
        }, 800);
      } else {
        // Use the 'out of viewport hidden text area' trick
        const textArea = document.createElement("textarea");
        textArea.value = this.linkMeeting;

        // Move textarea out of the viewport so it's not visible
        textArea.style.position = "absolute";
        textArea.style.left = "-999999px";

        document.body.prepend(textArea);
        textArea.select();
        try {
          document.execCommand("copy");
          this.isCopied = true;
          setTimeout(() => {
            this.isCopied = false;
          }, 2000);
        } catch (error) {
          console.error(error);
        } finally {
          textArea.remove();
        }
      }
    },
    handleClick(item, index) {
      this.checkActive = index;
      this.$emit("clicked", item);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";
</style>
