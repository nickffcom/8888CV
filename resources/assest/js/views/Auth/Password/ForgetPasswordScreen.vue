<template lang="">
  <div class="d-flex vh-100 login flex-column">
    <LoginLogo
      externalClass="h-100-px w-300-px mx-auto mt-30 mb-30 fz-30"
      title="ロゴ"
    />
    <div
      class="login-form h-100 pl-40 pr-40 d-flex justify-content-start align-items-center flex-column bg-color-grey2"
    >
      <span class="fz-24 font-weight-600 mt-40">パスワードをお忘れの方</span>
      <div class="d-flex flex-row position-relative mt-40 bg-color-white">
        <div v-for="(step, index) in stepChangePassword" :key="index">
          <div
            :class="[
              'step-box',
              index === indexChoosingStep && 'chosen-step',
              (index === 1 || index === 2) && 'pl-40',
            ]"
          >
            <div
              v-if="index < stepChangePassword.length - 1"
              :class="[
                'triangle-right',
                index === indexChoosingStep - 1 && 'triangle-chosen',
              ]"
            />
            <div
              v-if="index < stepChangePassword.length - 1"
              :class="[
                'triangle-right-small',
                index === indexChoosingStep && 'd-none',
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
        <EmailForm v-if="indexChoosingStep === 0" />
        <ConfirmInfo v-if="indexChoosingStep === 1" />
        <UserPassword v-if="indexChoosingStep === 2" />
        <Complete v-if="indexChoosingStep === 3" />
      </div>
    </div>
  </div>
  <LoadingScreen v-if="isLoading" />
</template>
<script>
import LoginLogo from "../../../components/Logo.vue";
import LoadingScreen from "../../../components/LoadingScreen.vue";
import EmailForm from "./Components/EmailForm.vue";
import ConfirmInfo from "./Components/ConfirmInfo.vue";
import UserPassword from "./Components/UserPassword.vue";
import Complete from "./Components/Complete.vue";
import { LoadingMixins } from "../../../mixins/Loading";
import { mapMutations, mapState } from "vuex";

export default {
  name: "Register",
  components: {
    LoginLogo,
    LoadingScreen,
    EmailForm,
    ConfirmInfo,
    UserPassword,
    Complete,
  },
  mixins: [LoadingMixins],
  created() {
    this.token = this.$route.params.token;
    if (this.token) {
      this.setIndexChoosingStep(2);
    }
  },
  computed: {
    ...mapState("account", ["indexChoosingStep"]),
  },
  data() {
    return {
      isLoading: false,
      stepChangePassword: [
        "メールアドレス入力",
        "登録情報を確認",
        "必要事項を入力",
        "完了",
      ],
      token: null,
    };
  },

  methods: {
    ...mapMutations("account", ["setIndexChoosingStep"]),
  },
};
</script>
<style lang="scss">
@import "../Login/Login.scss";
@import "../Register/Register.scss";
</style>
