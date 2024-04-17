export const LoadingMixins = {
  created() {
    this.emitter.on("isShowLoading", (isLoading) => {
      if (isLoading) {
        document.body.style.overflow = "hidden";
        document.body.style.pointerEvents = "none";
      } else {
        document.body.style.overflow = "unset";
        setTimeout(() => {
          document.body.style.pointerEvents = "auto";
        }, 1);
      }
      this.isLoading = isLoading;
    });
  },
};
