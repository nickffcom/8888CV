e
<template lang="">
  <div class="send-invitation d-flex flex-column text-black1 w-100 mt-5">
    <div class="send-invitation__top w-100 d-flex flex-column">
      <span class="text-black1 d-flex send-invitation__top-title fz-14 mb-10">
        <span v-if="fullName" class="text-global fz-14">
          {{ fullName }}
        </span>
        <span v-else class="text-global send-invitation__icon">○○</span>
        さんにコンタクト承認依頼を送信しますか?
      </span>
      <span class="text-black1 d-flex send-invitation__top-title fz-14 mb-10">
        (送信するとメールが同時に送信されます。)
      </span>
      <span
        class="text-black1 d-flex send-invitation__top-title-second fz-16 mt-20 mb-10"
      >
        メッセージ (任意)
      </span>
      <span class="d-flex send-invitation__top-title fz-14 mb-10">
        承認依頼にメッセージを添えることもできます。
      </span>
      <textarea
        cols="30"
        rows="5"
        class="pl-20 pr-20 pt-10 radius-8-px fz-14 form-control"
        placeholder="メッセージを入力"
        v-model="content"
        @input="updateValue"
        spellcheck="false"
      />
      <div class="d-flex justify-content-end">
        <div
          @click="handleCancelSendRequest"
          class="mt-35 mr-50 cur-p text-underline text-global fz-14 lh-30 h-30-px"
        >
          キャンセル
        </div>
        <button @click="handleSendRequest" class="mt-35 h-30-px">
          招待メールを送信
        </button>
      </div>
    </div>
    <div class="send-invitation__bottom mt-30 w-100 d-flex flex-column" />
  </div>
</template>
<script>
export default {
  name: "SendInvitation",
  components: {},
  props: {
    contentProp: { type: String, default: "" },
    fullName: { type: String, default: null },
  },
  watch: {
    contentProp(newVal) {
      this.content = newVal;
    },
  },
  emits: ["close", "accept", "update"],
  data() {
    return {
      content: this.contentProp,
    };
  },
  methods: {
    updateValue($event) {
      this.$emit("update", $event.target.value);
    },
    handleCancelSendRequest() {
      this.$emit("close", false);
    },
    handleSendRequest() {
      this.$emit("accept", true);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Invitation.scss";
</style>
