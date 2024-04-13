<template lang="">
  <!-- header todo -->
  <div class="d-flex align-items-center justify-content-between mt-15 mb-15">
    <div class="d-flex">
      <img :src="PENCIL" alt="" srcset="" class="h-25-px" />
      <div class="fz-16 home__right-title ml-20">確定事項</div>
    </div>
    <div class="home__right-line bg-color-grey2 flex-grow-1 mr-20 ml-20" />
    <div :class="['d-flex justify-content-between', data ? '' : 'todo-time']">
      <span v-if="data?.date && data?.time" class="text-grey1">
        更新: {{ data?.date }} &nbsp; {{ data.time }} &nbsp;
      </span>
      <span v-if="data?.author" class="text-grey1 mr-25">
        作成者: {{ data.author }}
      </span>
      <span
        v-if="!hideButtonProp"
        class="text-global cur-p"
        @click="handleOpenModal"
      >
        編集
      </span>
    </div>
  </div>
  <div class="d-flex flex-column ml-60">
    <div v-if="data?.summary" class="d-flex flex-column">
      <div
        :class="externalClass"
        class="text-grey1 fz-15"
        v-html="data?.summary"
      />
    </div>
    <div v-else class="text-grey1 text-center fz-14 mt-15 ml-10">
      確定事項はありません。
    </div>
  </div>
  <ConfirmModal
    :showModal="openModal"
    @closeModal="handleCloseModal"
    @save="handleCloseModal"
    :showFooter="false"
    :widthCustom="700"
  >
    <template #content>
      <div class="font-weight-600 text-black1 fz-16 mb-15">確定事項</div>
      <Editor api-key="no-api-key" v-model="summary" :init="editorConfig" />
    </template>
    <template #select>
      <div
        class="d-flex justify-content-between align-items-center mt-3 gap-180"
      >
        <div class="d-flex gap-260 mt-20">
          <div class="text-underline cur-p fz-16" @click="handleCancel">
            <span class="text-grey1 fz-14">キャンセル</span>
          </div>
          <button
            @click="handleAccept"
            class="min-w-120-px btn btn-primary h-40-px radius-12-px"
          >
            <span
              class="d-flex align-items-center justify-content-center gap-5"
            >
              <div>OK</div>
            </span>
          </button>
        </div>
      </div>
    </template>
  </ConfirmModal>
</template>
<script>
import ConfirmModal from "../../../components/ConfirmModal.vue";
import Editor from "@tinymce/tinymce-vue";
import { PENCIL } from "../../../constants/imageConst";
import { mapState } from "vuex";
export default {
  name: "Todo",
  components: { ConfirmModal, Editor },
  props: {
    data: { type: Object, default: () => ({}) },
    externalClass: { type: String, default: "" },
    hideButtonProp: { type: Boolean, default: false },
  },
  created() {
    this.PENCIL = PENCIL;
  },
  emits: ["getValue"],
  data() {
    return {
      openModal: false,
      summary: "",
    };
  },
  computed: {
    ...mapState("meeting", ["showDetail"]),
    editorConfig() {
      return {
        statusbar: false,
        notifications: false,
        height: 300,
        content_css: "/CreatingMeeting.scss",
        menubar: false,
      };
    },
  },
  methods: {
    handleOpenModal() {
      this.summary = this.data.summary;
      this.openModal = true;
    },
    handleCloseModal() {
      this.openModal = false;
    },
    handleAccept() {
      this.$emit("getValue", { summary: this.summary });
      this.openModal = false;
    },
    handleCancel() {
      this.summary = "";
      this.openModal = false;
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";
</style>
