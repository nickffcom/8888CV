<template>

</template>

<script>
import { ref } from "vue";
export default {
  name: "Login",
  setup() {
    const isLoading = ref(false);
    return { isLoading };
  },
};
</script>
<style lang="scss" scoped></style>
