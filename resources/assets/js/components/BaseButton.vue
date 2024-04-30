<template>
  <button :class="['btn min-w-100-px', typeClass]" :disabled="disabled">
    <span class="d-flex align-items-center justify-content-center gap-5">
      <slot />
    </span>
  </button>
</template>
<script>
export default {
  props: {
    typeClass: {
      type: String,
      default: "btn-primary",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
