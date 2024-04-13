<template lang="">
  <div class="d-flex vh-100 login flex-column">
    <LoginLogo
      externalClass="h-100-px w-300-px mx-auto mt-30 mb-30 fz-30"
      title="ロゴ"
    />
    <div
      class="login-form h-100 pl-40 pr-40 d-flex justify-content-start align-items-center flex-column bg-color-grey2"
    >
      <span class="fz-24 font-weight-600 mt-40">新規会員登録</span>
      <div class="d-flex flex-row position-relative mt-40 bg-color-white">
        <div v-for="(step, index) in stepConfirmEmail" :key="index">
          <div
            :class="[
              'step-box',
              index === indexChoose && 'chosen-step',
              (index === 1 || index === 2) && 'pl-40',
            ]"
          >
            <div
              v-if="index < stepConfirmEmail.length - 1"
              :class="[
                'triangle-right',
                index === indexChoose - 1 && 'triangle-chosen',
              ]"
            />
            <div
              v-if="index < stepConfirmEmail.length - 1"
              :class="[
                'triangle-right-small',
                index === indexChoose && 'd-none',
              ]"
            />
            <div class="font-weight-600">
              {{ step }}
            </div>
          </div>
        </div>
      </div>
      <div
        class="d-flex flex-column w-100 max-w-800-px align-items-center mt-20 bg-color-white p-30 radius-30-px"
      >
        <EmailForm
          :emailProps="email"
          :isDisabledInput="isDisabledInput"
          v-if="indexChoose === 0"
          @onNext="onNextStep"
        />
        <ConfirmInfo v-if="indexChoose === 1" @onNext="onNextStep" />
        <UserInfo v-if="indexChoose === 2" @onNext="onNextStep" />
        <Complete v-if="indexChoose === 3" @onNext="onNextStep" />
      </div>
    </div>
  </div>
  <LoadingScreen v-if="isLoading" />
</template>
<script>
import LoginLogo from "../../../components/Logo.vue";
import { LoadingMixins } from "../../../mixins/Loading";
import LoadingScreen from "../../../components/LoadingScreen.vue";
import { ERROR_MESSAGE } from "../../../constants";
import EmailForm from "../Register/Components/EmailForm.vue";
import ConfirmInfo from "./Components/ConfirmInfo.vue";
import UserInfo from "./Components/UserInfo.vue";
import Complete from "./Components/Complete.vue";
import { mapMutations, mapState } from "vuex";
import { getGuestByToken } from "../../../apis/account/user";

export default {
  name: "Register",
  components: {
    LoginLogo,
    LoadingScreen,
    EmailForm,
    ConfirmInfo,
    UserInfo,
    Complete,
  },
  mixins: [LoadingMixins],
  created() {
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    this.emailID = this.$route.params.id;
    if (this.emailID) {
      this.setIndexChoose(2);
    }
    if (this.$route.params.profile_id && this.$route.name == "RegisterInvite") {
      this.setIndexChoose(2);
    }
    if (
      this.$route.params.token_guest &&
      this.$route.name == "RegisterMeetingLink"
    ) {
      this.getEmailGuest(this.$route.params.token_guest);
    }
  },
  data() {
    return {
      isLoading: false,
      stepConfirmEmail: [
        "メールアドレス入力",
        "登録情報を確認",
        "必要事項を入力",
        "完了",
      ],
      email: "",
      emailID: null,
      isDisabledInput: false,
    };
  },
  computed: {
    ...mapState("account", ["indexChoose"]),
  },
  methods: {
    ...mapMutations("account", ["setIndexChoose"]),
    /**
     * Move to next step
     */
    onNextStep() {
      this.indexChoose =
        this.indexChoose === 3 ? (this.indexChoose = 0) : this.indexChoose + 1;
    },

    getEmailGuest(token) {
      this.emitter.emit("isShowLoading", true);
      const data = { token: token };
      getGuestByToken(data)
        .then((res) => {
          this.email = res.data.email;
          this.isDisabledInput = true;
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
@import "../Login/Login.scss";
@import "./Register.scss";
</style>
