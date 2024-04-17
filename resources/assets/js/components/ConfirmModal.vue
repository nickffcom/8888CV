<template>
  <a-modal
    :visible="showModal"
    title=""
    centered
    :footer="null"
    :closable="showClose"
    :maskClosable="disabledClickOutside"
    @cancel="PopupCancel"
    :class="`base-confirm-modal ${modalCustomClass}`"
    dialogClass="p-10"
    :width="widthCustom"
  >
    <slot name="text-button" />
    <div class="d-flex justify-content-center align-items-center fz-32">
      <slot name="icon" />
    </div>
    <div
      class="d-flex align-items-center justify-content-center fz-20 flex-column fz-sm-16"
    >
      <slot name="content" />
    </div>
    <div
      class="d-flex align-items-center justify-content-center gap-40 gap-sm-0"
    >
      <slot name="select" />
    </div>
    <div
      class="d-flex align-items-center justify-content-center gap-40 gap-sm-0"
    >
      <slot name="time-keeping" />
    </div>

    <div
      v-if="showFooter"
      class="d-flex justify-content-center align-items-center mt-3 gap-80 gap-md-60 gap-xs-40 fz-18 fz-sm-14"
    >
      <div class="text-underline cur-p" @click="onCancel">
        <slot name="text-cancel" />
      </div>
      <button class="min-w-120-px btn btn-primary h-40-px" @click="onSave">
        <span class="d-flex align-items-center justify-content-center gap-5">
          <slot name="text-save" />
        </span>
      </button>
    </div>
  </a-modal>
</template>

<script>
export default {
  name: "ConfirmModal",
  props: {
    showModal: {
      type: Boolean,
      default: false,
    },
    showClose: {
      type: Boolean,
      default: true,
    },
    disabledClickOutside: {
      type: Boolean,
      default: true,
    },
    showFooter: {
      type: Boolean,
      default: true,
    },
    widthCustom: {
      type: [String, Number],
      default: "auto",
    },
    modalCustomClass: {
      type: String,
    },
  },
  methods: {
    PopupCancel() {
      this.$emit("closeModal");
    },
    onSave() {
      this.$emit("save");
    },
    onCancel() {
      this.$emit("handleCancel");
      this.$emit("closeModal");
      this.$emit("cancel");
    },
  },
};
</script>
