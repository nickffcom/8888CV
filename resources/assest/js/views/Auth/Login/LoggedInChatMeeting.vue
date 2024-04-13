<template lang="">
  <div class="d-flex vh-100 login flex-column">
    <LoginLogo
      externalClass="h-100-px w-300-px mx-auto mt-30 mb-30 fz-30"
      title="ロゴ"
    />
    <div
      class="login-form h-100 d-flex justify-content-start align-items-center flex-column bg-color-grey2"
    >
      <div
        class="d-flex justify-content-center align-items-start flex-row w-100 login-container mt-60"
      >
        <!-- Login with member -->
        <div
          class="d-flex flex-column w-700-px align-items-center flex-column bg-color-white p-30 radius-30-px"
        >
          <div
            class="d-flex mt-15 justify-content-center align-items-center flex-column"
          >
            <span class="fz-24 font-weight-600 mb-20">
              {{ this.name }}さんとChatmeetingでつながりましょう
            </span>
          </div>
          <div class="text-black1 fz-16 mb-20">
            「コンタクトに追加」から{{
              this.name
            }}さんとメッセージのやりとりを始めましょう。
          </div>
          <button
            class="btn btn-primary w-170-px mb-20"
            @click="handleAddContact"
          >
            コンタクトに追加
          </button>
          <div class="fz-16 mr-auto ml-100 font-weight-600 mb-10">
            公開プロフィール
          </div>
          <div
            class="d-flex w-70 justify-content-start align-items-start border p-20"
          >
            <img :src="USER_IMAGE" alt="" srcset="" class="w-100-px h-100-px" />
            <div class="d-flex flex-column ml-20">
              <div class="fz-18 font-weight-600">{{ name }}</div>
              <div class="fz-14">ID: {{ profileID }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <LoadingScreen v-if="isLoading" />
</template>
<script>
import LoginLogo from "../../../components/Logo.vue";
import { LoadingMixins } from "../../../mixins/Loading";
import LoadingScreen from "../../../components/LoadingScreen.vue";
import { USER_IMAGE } from "../../../constants/imageConst";
import { addContact, getUserByProfileID } from "../../../apis/invite/invite";
export default {
  name: "LoggedInChatMeeting",
  components: {
    LoginLogo,
    LoadingScreen,
  },
  created() {
    this.USER_IMAGE = USER_IMAGE;
    this.profileID = this.$route.params.profile_id;
    this.getUser();
  },
  mixins: [LoadingMixins],
  data() {
    return {
      isLoading: false,
      profileID: null,
      name: null,
    };
  },
  methods: {
    /**
     * get infor user invite
     */
    getUser() {
      const params = {
        profile_id: this.profileID,
      };
      this.emitter.emit("isShowLoading", true);
      getUserByProfileID(params)
        .then((res) => {
          this.name = res.data.last_name + " " + res.data.first_name;
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },
    /**
     * add contact
     */
    handleAddContact() {
      const formData = new FormData();
      formData.append("profile_id", this.profileID);
      this.emitter.emit("isShowLoading", true);
      addContact(formData)
        .then((res) => {
          this.$router.push({ name: "Home" });
          this.emitter.emit("isShowLoading", false);
        })
        .catch((err) => {
          console.log("there was an error", err);
          this.emitter.emit("isShowLoading", false);
        });
    },
  },
};
</script>
<style lang="scss">
@import "./Login.scss";
</style>
