<template>
  <div>
    <div class="dropzone preview-container row" />
    <div ref="dropRef" class="dropzone custom-dropzone" />
  </div>
</template>

<script>
import { ref, onMounted, defineComponent } from "vue";
import { Dropzone } from "dropzone";
import { CLOUD_ICON, CANCEL_ICON } from "../../constants/imageConst";

export default defineComponent({
  name: "Dropzone",
  created() {
    this.CLOUD_ICON = CLOUD_ICON;
    this.CANCEL_ICON = CANCEL_ICON;
  },
  props: {
    imagesUrl: {
      type: Array,
      default: () => [],
    },
    isFilePath: {
      type: Boolean,
      default: false,
    },
    externalClass: {
      type: String,
    },
    title: {
      type: String,
    },
    previewStyleImage: {
      type: String,
    },
    buttonStyle: {
      type: String,
    },
    imageStyle: {
      type: Boolean,
    },
  },
  data() {
    return {
      isFake: false,
    };
  },
  setup(props, context) {
    const dropRef = ref(null);
    const deActiveColor = "#797979";
    const activeColor = "#55aeb4";
    const customPreview = `
                        <div
                            style="${props.previewStyleImage}"
                            class="dz-preview dz-processing dz-image-preview dz-complete"
                        >
                            <img data-dz-thumbnail style="
                            width: 100%; 
                            height: 100%;
                            border-radius: ${props.imageStyle ? "50px" : 0};
                            object-fit: cover
                            " />
                        </div>
                        `;
    const customDropActionArea = `
                <div class="${props.externalClass}" style="${props.buttonStyle}">
                    ${props.title}
                </div>
                `;
    const customDropRemoveIcon = ` <img src="${CANCEL_ICON}" />`;
    onMounted(() => {
      if (dropRef.value !== null) {
        new Dropzone(dropRef.value, {
          previewTemplate: customPreview,
          uploadMultiple: false,
          thumbnailWidth: null,
          thumbnailHeight: null,
          url: "/",
          maxFilesize: 256, //MB
          maxThumbnailFilesize: 256, //MB
          addRemoveLinks: true,
          dictRemoveFile: "",
          acceptedMimeTypes: "image/jpeg,image/png,image/svg+xml,image/jpg",

          init: function () {
            $(".dz-remove").html(``);
            if (dropRef.value.querySelector(".dz-default")) {
              dropRef.value.querySelector(".dz-default").innerHTML =
                customDropActionArea;
            }
            this.on("thumbnail", () => {
              $(".dz-image").find("img").attr({
                width: "100%",
                height: "100%",
                style: `object-fit:cover; opacity:1`,
                class: "rounded",
              });
            });
            this.on("addedfile", function () {
              if (this.files[1] != null) {
                this.removeFile(this.files[0]);
              }
            });
            // TODO: Open when call api
            props.imagesUrl?.map((image, index) => {
              const urlImg = image.dataURL ? image.dataURL || "" : image;
              const tempFile = {
                url: urlImg,
              };
              // Call the default addedfile event handler
              this.emit("addedfile", tempFile);
              // And optionally show the thumbnail of the file:
              this.emit(
                "thumbnail",
                tempFile,
                props.isFilePath ? image.url : urlImg
              );
              this.emit("success", tempFile);
              this.emit("complete", tempFile);
              this.files.push(tempFile);
            });
          },

          complete: function () {
            if ($(".dropzone.preview-container").children().length > 0) {
              $("#dropzone-icon").attr("fill", activeColor);
            }
            $(".dz-remove")
              .addClass("position-absolute d-none")
              .css({ top: "10px", right: "4px" })
              .html(customDropRemoveIcon);
            $(".dz-complete").hover(
              function () {
                $(this).children().last().removeClass("d-none");
              },
              function () {
                $(this).children().last().addClass("d-none");
              }
            );
          },
          removedfile: function (file, response) {
            file.previewElement.remove();
            context.emit("remove", file);
            if ($(".dropzone.preview-container").children().length === 0) {
              $("#dropzone-icon").attr("fill", deActiveColor);
            }
          },
          accept(file, done) {
            const reader = new FileReader();
            this.isFake = true;
            reader.addEventListener("loadend", function (event) {
              file.status = Dropzone.SUCCESS;
              $("#dropzone-icon").attr("fill", activeColor);
              context.emit("success", file);
            });
            reader.readAsText(file);
            $(".dz-remove")
              .addClass("position-absolute")
              .css({
                top: props.imageStyle ? "0" : "-15px",
                right: props.imageStyle ? "4px" : "45px",
              })
              .html(customDropRemoveIcon);
            $(".dz-complete").children().last().addClass("d-none");
            $(".dz-complete").hover(
              function () {
                $(this).children().last().removeClass("d-none");
              },
              function () {
                $(this).children().last().addClass("d-none");
              }
            );
          },

          previewsContainer:
            dropRef.value.parentElement.querySelector(".preview-container"),
        }).on("complete", () => {
          if (!$(".dropzone.preview-container").hasClass("mb-3")) {
            $(".dropzone.preview-container").addClass("mb-3");
          }
        });

        dropRef.value.querySelector(".dz-remove");
      }
    });
    return {
      dropRef,
    };
  },
});
</script>

<style lang="scss" scoped>
.preview-container {
  border: none;
}

.avatar-fake {
  width: 70px;
  height: 70px;
  background-color: #dfdfdf;
  border-radius: 50px;
  padding: 10px;
}
</style>
