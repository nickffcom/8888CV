<template lang="">
  <div>
    <!-- detail meeting -->
    <div class="d-flex align-items-center justify-content-between mt-20 mb-20">
      <div class="fz-14 home__right-title">ミーティング</div>
      <div class="home__right-line bg-color-grey2 flex-grow-1 mr-20 ml-20" />
      <slot name="groupbutton">
        <template v-if="!hideButtonProp">
          <div
            v-if="Object.keys(data).length != 0"
            class="list-button d-flex justify-content-between w-20"
          >
            <button class="button-left text-global ml-10" @click="handleEdit">
              編集
            </button>
            <button class="button-right text-error" @click="showCancelMeeting">
              削除
            </button>
          </div>
        </template>
      </slot>
    </div>
    <div v-if="Object.keys(data).length != 0">
      <div class="content d-flex mt-15">
        <span
          class="content-meeting-title-detail fz-16 font-weight-600 text-black1"
        >
          {{ data?.title }}
        </span>
      </div>
      <div :class="['content-meeting d-flex', !data.date && 'mb-15']">
        <div class="content-meeting-left d-flex">
          <div class="w-40-px d-flex">
            <img :src="CALENDAR_IMAGE" alt="" srcset="" class="h-25-px" />
          </div>
          <span
            :class="[
              'content-meeting-date-detail fz-16 text-black1',
              data?.date && 'ml-15',
              !data?.date && 'ml-15',
            ]"
          >
            {{ formatDateJa(data.date) || "スケジュールなし" }}
          </span>
        </div>
        <div class="content-meeting-right d-flex flex-grow-1 ml-20">
          <div class="w-40-px d-flex">
            <img :src="CLOCK_IMAGE" alt="" srcset="" class="h-25-px" />
          </div>
          <span class="content-meeting-time-detail fz-16 text-black1 ml-15">
            {{ data.time || "時間なし" }}
          </span>
        </div>
      </div>
      <div class="content d-flex mt-15">
        <div class="w-40-px d-flex">
          <img :src="LOCATED" alt="" srcset="" class="h-25-px" />
        </div>
        <div class="d-flex align-items-center w-100">
          <div
            class="d-flex align-items-center flex-grow-1"
            :class="{
              'flex-wrap': checkSpaceLine || isBreakWord,
            }"
            v-if="data?.link"
          >
            <div
              class="text-black1 fz-16 ml-18"
              :class="[
                { 'min-w-70-px': data?.selected === LOCATION.webURL.value },
                { 'min-w-70-px': data?.selected === LOCATION.others.value },
                {
                  'min-w-115-px':
                    data?.selected === LOCATION.meetingPlace.value,
                },
              ]"
            >
              {{ data?.selected }}
            </div>
            <span
              v-if="data?.selected === LOCATION.webURL.value"
              @click="openNewPage(data.link)"
              id="dataLink"
              class="content-meeting-link-detail fz-16 text-global ml-15 cur-p"
            >
              {{ `(` + data.link + `)` }}
            </span>
            <span
              v-else-if="data?.selected !== LOCATION.webURL.value"
              class="content-meeting-link-detail text-black1 fz-16 ml-15 space-line"
              id="dataLink"
            >
              {{ data.link }}
            </span>
          </div>
          <span v-else class="text-black1 fz-16 ml-15">場所なし</span>
        </div>
      </div>
      <div class="content d-flex flex-column mt-15">
        <div
          :class="[
            'd-flex w-170-px align-items-center',
            !data?.content && 'mb-15',
          ]"
        >
          <div class="w-40-px d-flex">
            <img :src="MENU_ICON_2" alt="" srcset="" class="h-25-px" />
          </div>
          <div
            v-if="data?.content"
            class="content-meeting-title fz-16 text-black1 ml-30"
          >
            目的内容
          </div>
          <div v-else class="content-meeting-title fz-16 text-black1 ml-30">
            目的内容なし
          </div>
        </div>
        <div
          :class="[
            ' ml-80',
            !data?.content && 'd-none',
            data?.content && 'd-flex flex-column',
          ]"
        >
          <div
            ref="refHeight"
            :class="[
              'content-meeting-purpose-detail fz-16 text-black1 mt-10 ',
              isResize ? '' : 'content-meeting-collapsed',
              isDisplay ? '' : 'mb-20',
            ]"
            v-html="data.content"
          />
          <div v-if="isDisplay" class="content-meeting-more">
            <div
              class="content-meeting-line h-2-px bg-color-grey2 flex-grow-1 mr-20"
            />
            <div
              @click="handleClick"
              class="cur-p content-meeting-more-detail text-global fz-16 d-flex justify-content-center mx-auto"
            >
              もっと見る
            </div>
            <div
              class="content-meeting-line h-2-px bg-color-grey2 flex-grow-1 ml-20"
            />
          </div>
        </div>
      </div>
      <div :class="['content d-flex flex-column']">
        <div class="d-flex w-170-px">
          <img :src="FOLDER_2" alt="" srcset="" class="h-25-px" />
          <div
            v-if="data?.dataImages"
            class="content-meeting-title fz-16 text-black1 ml-30"
          >
            配布資料
          </div>
          <div v-else class="content-meeting-title fz-16 text-black1 ml-30">
            配布資料なし
          </div>
        </div>
        <div v-if="data?.dataImages" class="d-flex ml-60">
          <div class="d-flex flex-wrap">
            <div
              v-for="(item, index) in data?.dataImages"
              :key="index"
              class="mr-30"
              @click="
                showModalZoomImage(item?.file_path, item?.mime_type, item?.id)
              "
            >
              <PdfPreview
                height="170"
                :src="item?.file_path"
                v-if="
                  item?.mime_type === 'application/pdf' ||
                  item?.mime_type === 'pdf'
                "
                class="h-150-px w-150-px cur-p"
              />
              <img
                v-else-if="
                  isMatchedRoute('EditMeeting') || isMatchedRoute('Home')
                "
                :src="item?.file_path"
                alt=""
                srcset=""
                class="h-150-px w-150-px cur-p"
              />
              <img
                v-else
                :src="item?.file_path"
                alt=""
                srcset=""
                class="h-150-px w-150-px cur-p"
                style="object-fit: cover"
              />
              <div class="mt-15">
                <span class="text-grey1 mr-5">添付</span>
                <span class="text-grey1">
                  {{ formatYMD(item?.created_at) }} &nbsp;
                  {{ formatTime(item?.created_at) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-grey1 text-center">
      ミーティングを選択してください。
    </div>
  </div>
  <div class="modal d-flex" v-if="showZoomImage">
    <div class="modal-overlay" />
    <div class="modal-body-img d-flex">
      <div class="modal-image w-100">
        <div
          v-if="type === 'application/pdf' || type === 'pdf'"
          class="d-flex w-100 h-100 flex-column"
        >
          <div
            class="d-flex zoom-container justify-content-between align-items-center"
          >
            <div class="text-white text-file-name min-w-20">
              {{ imgSrc.name }}
            </div>
            <div
              class="d-flex gap-20 align-items-center justify-content-center w-100"
            >
              <div
                class="text-white fz-16 font-weight-600 icon-close-modal"
                @click="closeZoomImage"
              >
                X
              </div>
              <div class="d-flex align-items-center gap-10">
                <div class="zoom-sub text-white fz-16" @click="handleZoomSub">
                  -
                </div>
                <div class="d-flex text-white align-items-center">
                  <input
                    type="text"
                    v-model="numZoomIn"
                    class="zoom-input text-white"
                    :disabled="true"
                  />
                  %
                </div>
                <div class="zoom-add text-white fz-16" @click="handleZoomAdd">
                  +
                </div>
              </div>
              <div
                class="d-flex gap-20 justify-content-center align-items-center"
              >
                <div
                  class="zoom-sub text-white fz-16"
                  :class="{ invisible: page <= 1 }"
                  @click="handleBack"
                >
                  ❮
                </div>
                <div class="text-white">{{ page }} / {{ pageCount }}</div>
                <div
                  class="zoom-sub text-white fz-16"
                  :class="{ invisible: page >= pageCount }"
                  @click="handleNext"
                >
                  ❯
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div
              class="d-flex flex-column overflow-y w-20 align-items-center"
              style="overflow-y: auto; height: 96vh"
              @scroll.native="handleScroll"
              ref="scrollContainer"
            >
              <div
                v-for="(item, index) in pageCount"
                :key="index"
                @click="handleChangePage(index + 1)"
                :class="{ active: activeIndex === index + 1 }"
                class="p-15 detail-pdf cur-p"
              >
                <PdfPreview
                  height="170"
                  :src="imgSrc"
                  :pageProp="index + 1"
                  @pageCount="getPageCount"
                />
                <div class="d-flex justify-content-center text-white mt-5">
                  {{ index + 1 }}
                </div>
              </div>
            </div>
            <div
              style="overflow: auto; max-height: 92vh; max-width: 92vw"
              class="mx-auto d-flex mt-10"
            >
              <PdfPreview
                :height="heightImgPdf"
                :src="imgSrc"
                :pageProp="page"
                @pageCount="getPageCount"
              />
            </div>
          </div>
        </div>
        <img
          v-if="type.includes('image') || type.includes('gif')"
          :src="imgSrc"
          style="object-fit: contain; height: 95vh"
        />
      </div>
    </div>
    <div class="group-button">
      <div class="d-flex gap-10 flex-wrap">
        <button
          v-if="type.includes('image') || type.includes('gif')"
          :src="imgSrc"
          class="h-40-px btn-outline btn m-0 fz-14 radius-5-px mr-15 w-100-px"
          @click="closeZoomImage"
        >
          <span class="text-grey1 fz-14">キャンセル</span>
        </button>
        <button
          class="h-40-px w-100-px text-white btn btn-primary"
          @click.prevent="shareZoomImage(indexPreview)"
        >
          共有
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import {
  USER_IMAGE,
  CALENDAR_IMAGE,
  LOCATED,
  FOLDER_2,
  PENCIL,
  CLOCK_IMAGE,
  MENU_ICON_2,
} from "../../../constants/imageConst";

import {
  formatDateJa,
  formatTime,
  formatYMD,
} from "../../../utils/formatDateTime";
import PdfPreview from "../../../components/PdfPreview.vue";
import { mapMutations, mapState } from "vuex";
import { SOCKET_KIND } from "../../../constants/socketConst";
import { notification } from "ant-design-vue";
import { ERROR_MESSAGE, LOCATION } from "../../../constants";
export default {
  name: "InforMeeting",
  components: {
    PdfPreview,
  },
  props: {
    data: { type: Object, default: () => ({}) },
    hideButtonProp: { type: Boolean, default: false },
  },
  created() {
    this.formatDateJa = formatDateJa;
    this.formatTime = formatTime;
    this.formatYMD = formatYMD;
    this.USER_IMAGE = USER_IMAGE;
    this.CALENDAR_IMAGE = CALENDAR_IMAGE;
    this.LOCATED = LOCATED;
    this.FOLDER_2 = FOLDER_2;
    this.PENCIL = PENCIL;
    this.CLOCK_IMAGE = CLOCK_IMAGE;
    this.MENU_ICON_2 = MENU_ICON_2;
    this.LOCATION = LOCATION;
  },
  data() {
    return {
      page: 1,
      pageCount: 1,
      isResize: false,
      isDisplay: true,
      showZoomImage: false,
      imgSrc: "",
      type: "",
      indexPreview: null,
      checkSocketSharing: false,
      checkTemp: false,
      listImage: this.data,
      isBreakWord: false,
      activeIndex: null,
      dataScrollY: 0,
      numZoomIn: 100,
      timeZoomIn: 3,
    };
  },
  watch: {
    "data.content": {
      handler: function (newValue) {
        this.checkContentMeetingHeight();
      },
      deep: true,
      immediate: true,
      flush: "post",
    },
  },
  mounted() {
    this.$nextTick(() => {
      this.caculateTextWidth();
    });
    this.checkContentMeetingHeight();
    // listen signal for sharing image
    this.sockets.subscribe("previewImage", (data) => {
      if (data.roomId == this.$route.params.id || data.roomId == this.id) {
        this.activeIndex = data.page;
        this.showZoomImage = true;
        this.checkSocketSharing = data.status;
        this.data.dataImages.forEach((item) => {
          if (item.id == data.image) {
            this.page = data.page;
            this.showModalZoomImage(item?.file_path, item?.mime_type, item.id);
          }
        });
      }
    });
    const data = {
      room: this.$route.params.id || this.id,
      kind: SOCKET_KIND.preview,
    };
    this.$socket.emit("joinRoom", data);

    // event image
    this.sockets.subscribe("eventImage", (data) => {
      this.page = data.page;
      this.activeIndex = data.page;
      const scrollContainer = this.$refs.scrollContainer;
      if (scrollContainer) {
        scrollContainer.scrollTop = data.scrollY;
      }
    });
    const dataImage = {
      room: this.$route.params.id || this.id,
      kind: SOCKET_KIND.nextBackImage,
    };
    this.$socket.emit("joinRoom", dataImage);
  },
  computed: {
    ...mapState("meeting", ["id"]),
    sortedDataImages() {
      return this.listImage.dataImages.slice().sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at);
      });
    },
    checkSpaceLine() {
      return this.data.link && this.data.link.includes("\n");
    },
    heightImgPdf() {
      let numHeight = 850 * (this.numZoomIn / 100);
      return numHeight.toString();
    },
  },
  emits: ["clicked", "show", "edit"],
  methods: {
    ...mapMutations("meeting", ["setPDF", "setCheckAdd"]),
    /**
     * set value when click sub or add zoom
     */
    setNumZoomIn(data) {
      if (data == 0) this.numZoomIn = 25;
      else if (data == 1) this.numZoomIn = 50;
      else if (data == 2) this.numZoomIn = 75;
      else if (data == 3) this.numZoomIn = 100;
      else if (data == 4) this.numZoomIn = 110;
      else if (data == 5) this.numZoomIn = 120;
      else if (data == 6) this.numZoomIn = 150;
      else if (data == 7) this.numZoomIn = 175;
      else if (data == 8) this.numZoomIn = 200;
      else if (data == 9) this.numZoomIn = 300;
    },
    handleZoomSub() {
      if (this.timeZoomIn > 0) {
        this.timeZoomIn--;
        this.setNumZoomIn(this.timeZoomIn);
      }
    },
    /**
     * add zoom
     */
    handleZoomAdd() {
      if (this.timeZoomIn <= 8) {
        this.timeZoomIn++;
        this.setNumZoomIn(this.timeZoomIn);
      }
    },
    handleScroll(event) {
      this.dataScrollY = event.target.scrollTop;
    },
    /**
     * change page of pdf
     */
    handleChangePage(index) {
      this.activeIndex = index;
      this.page = index;
      const image = {
        room: this.$route.params.id || this.id,
        kind: SOCKET_KIND.nextBackImage,
      };
      this.$socket.emit("joinRoom", image);
      var eventImage = {
        scrollY: this.dataScrollY,
        page: this.page,
        kind: SOCKET_KIND.nextBackImage,
        roomId: this.id,
        index: this.indexPreview,
      };
      // send signal
      if (this.checkSocketSharing) {
        this.$socket.emit("sendSignal", eventImage);
      }
    },
    /**
     * caculate width text of links
     */
    caculateTextWidth() {
      const span = document.getElementById("dataLink");
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");
      let parentWidthSpan = null;
      let textWidth = null;
      if (span && ctx) {
        parentWidthSpan = span.offsetWidth;
        ctx.width = span.offsetWidth;
        ctx.height = span.offsetHeight;
        ctx.font = `16px Roboto`;
        textWidth = ctx.measureText(span.innerText);
        if (textWidth.width <= parentWidthSpan) {
          this.isBreakWord = false;
        } else {
          this.isBreakWord = true;
        }
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        canvas.remove();
      }
    },

    getPageCount(data) {
      this.pageCount = data;
    },
    handleNext() {
      this.page++;
      this.activeIndex = this.page;
      const image = {
        room: this.$route.params.id || this.id,
        kind: SOCKET_KIND.nextBackImage,
      };
      this.$socket.emit("joinRoom", image);
      var eventImage = {
        page: this.page,
        kind: SOCKET_KIND.nextBackImage,
        roomId: this.id,
        index: this.indexPreview,
      };
      // send signal
      if (this.checkSocketSharing) {
        this.$socket.emit("sendSignal", eventImage);
      }
    },
    handleBack() {
      this.page--;
      this.activeIndex = this.page;
      const image = {
        room: this.$route.params.id || this.id,
        kind: SOCKET_KIND.nextBackImage,
      };
      this.$socket.emit("joinRoom", image);
      var eventImage = {
        page: this.page,
        kind: SOCKET_KIND.nextBackImage,
        roomId: this.id,
        index: this.indexPreview,
      };
      // send signal
      if (this.checkSocketSharing) {
        this.$socket.emit("sendSignal", eventImage);
      }
    },
    /**
     * open new page
     */
    openNewPage(data) {
      window.open(data, "_blank");
    },
    /**
     * check height
     */
    checkContentMeetingHeight() {
      const temp = this.$refs.refHeight;
      if (temp) {
        if (temp.scrollHeight <= 110) {
          this.isResize = true;
          this.isDisplay = false;
        } else {
          this.isDisplay = true;
          this.isResize = false;
        }
      }
    },
    /**
     *  Check if the route name matches the route being displayed.
     *  @returns {boolean} - Returns true if the route name matches the current route, false otherwise.
     */
    isMatchedRoute(routeName) {
      return this.$route.matched.some(({ name }) => {
        return name == routeName;
      });
    },
    handleClick() {
      this.isResize = !this.isResize;
      this.isDisplay = !this.isDisplay;
      this.$emit("clicked", this.isResize);
    },
    showCancelMeeting() {
      this.$emit("show", true);
    },
    /**
     * close modal zoom image
     */
    closeZoomImage() {
      this.setPDF(false);
      this.showZoomImage = false;
      this.checkSocketSharing = false;
      this.page = 1;
      this.activeIndex = 1;
      this.numZoomIn = 100;
      this.timeZoomIn = 3;
    },
    /**
     * close modal zoom image
     */
    shareZoomImage(data) {
      this.checkSocketSharing = true;
      const dataSocket = {
        room: this.$route.params.id || this.id,
        kind: SOCKET_KIND.preview,
      };
      this.$socket.emit("joinRoom", dataSocket);
      if (data) {
        var dataShareImage = {
          status: true,
          page: this.page,
          image: data,
          kind: SOCKET_KIND.preview,
          roomId: this.id,
        };
        // send signal
        this.$socket.emit("sendSignal", dataShareImage);
      }
      this.setPDF(false);
      // this.showZoomImage = false;
    },
    /**
     * open modal zoom image
     */
    showModalZoomImage(data, type, index) {
      console.log(data, type, index);
      this.setPDF(true);
      this.type = type;
      this.imgSrc = data;
      this.indexPreview = index;
      this.showZoomImage = true;
    },
    /**
     * go to screen edit
     */
    handleEdit() {
      this.setCheckAdd(true);
      this.$emit("edit", true);
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";
</style>
