<template lang="">
  <div class="mt-20 fz-15 w-100 font-weight-600 text-black1">
    <div>&lt; プロフィール詳細 &gt;</div>
    <div>
      <table class="mt-15 w-100 border-primary-color">
        <tr>
          <td class="w-25 text-global bg-color">会社名</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.company_name_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.company_name }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{
                  privacyPolicy(
                    userProfile.user_policy_data?.company_name_policy
                  )
                }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <input
                type="text"
                class="form-control h-40-px mr-70"
                v-model="userProfile.user_data.company_name"
              />
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.company_name_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">所属</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.department_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.department }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{
                  privacyPolicy(userProfile.user_policy_data?.department_policy)
                }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <input
                type="text"
                class="form-control h-40-px mr-70"
                v-model="userProfile.user_data.department"
              />
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.department_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">役職</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.position_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.position }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{
                  privacyPolicy(userProfile.user_policy_data?.position_policy)
                }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <input
                type="text"
                class="form-control h-40-px mr-70"
                v-model="userProfile.user_data.position"
              />
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.position_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">所在地</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.address_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.address }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{
                  privacyPolicy(userProfile.user_policy_data?.address_policy)
                }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <input
                type="text"
                class="form-control h-40-px mr-70"
                v-model="userProfile.user_data.address"
              />
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.address_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">URL</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.url_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.url }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{ privacyPolicy(userProfile.user_policy_data?.url_policy) }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <input
                type="text"
                class="form-control h-40-px mr-70"
                v-model="userProfile.user_data.url"
              />
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.url_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">メールアドレス</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.email_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.email }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{ privacyPolicy(userProfile.user_policy_data?.email_policy) }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <div class="w-55 mr-70">
                <input
                  type="text"
                  class="form-control w-100 h-40-px"
                  v-model="userProfile.user_data.email"
                  ref="inputEmail"
                />
                <span class="error mt-5" ref="errorEmail">
                  {{ ERROR_MESSAGE.emailError }}
                </span>
              </div>
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.email_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">電話番号(勤務先)</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.company_phone_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.company_phone }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{
                  privacyPolicy(
                    userProfile.user_policy_data?.company_phone_policy
                  )
                }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <div class="w-55 mr-70">
                <input
                  type="text"
                  class="form-control h-40-px w-100"
                  v-model="userProfile.user_data.company_phone"
                  @keydown="validNumber"
                  maxlength="11"
                  ref="inputCompanyPhone"
                />
                <span class="error mt-5" ref="errorCompanyPhone">
                  {{ ERROR_MESSAGE.phoneError }}
                </span>
              </div>
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.company_phone_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">電話番号(内線)</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.internal_line_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.internal_line }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{
                  privacyPolicy(
                    userProfile.user_policy_data?.internal_line_policy
                  )
                }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <input
                type="text"
                class="form-control h-40-px w-55 mr-70"
                v-model="userProfile.user_data.internal_line"
                @keydown="validNumber"
                maxlength="2"
              />
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.internal_line_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <td class="w-25 text-global bg-color">電話番号(携带)</td>
          <td v-if="!isSettingProps" class="font-weight-300">
            <div
              v-if="
                isContact ||
                userProfile?.user_policy_data?.mobile_policy ===
                  PRIVACY_POLICY.public
              "
              class="d-flex justify-content-between align-items-center pl-20"
            >
              <div class="w-55">
                {{ userProfile.user_data?.mobile }}
              </div>
              <div class="w-40 ml-30 font-weight-300 text-grey1">
                {{ privacyPolicy(userProfile.user_policy_data?.mobile_policy) }}
              </div>
            </div>
          </td>
          <td v-else class="pt-10 pb-10">
            <div class="d-flex w-100 align-items-center">
              <div class="w-55 mr-70">
                <input
                  type="text"
                  class="form-control h-40-px w-100"
                  v-model="userProfile.user_data.mobile"
                  @keydown="validNumber"
                  maxlength="11"
                  ref="inputMobile"
                />
                <span class="error mt-5" ref="errorMobile">
                  {{ ERROR_MESSAGE.phoneError }}
                </span>
              </div>
              <select
                class="select-list cur-p text-global w-150-px"
                v-model="userProfile.user_policy_data.mobile_policy"
              >
                <option :value="PRIVACY_POLICY.contact">
                  <span class="px-3">コンタクトのみ</span>
                </option>
                <option
                  :value="PRIVACY_POLICY.public"
                  class="option-list cur-p text-global"
                >
                  <span class="px-3">公開する</span>
                </option>
              </select>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>
<script>
import { PRIVACY_POLICY, ERROR_MESSAGE } from "../../constants/index";
import { ValidateNumInput } from "../../mixins/ValidateNumInput";
import { validEmail, isValidPhoneNum } from "../../utils/helper";

export default {
  props: {
    isSettingProps: {
      type: Boolean,
    },
    userProfileProps: {
      type: Object,
    },
    isMainScreen: {
      type: Boolean,
      default: false,
    },
    isContact: {
      type: Boolean,
    },
  },
  mixins: [ValidateNumInput],
  created() {
    this.PRIVACY_POLICY = PRIVACY_POLICY;
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    this.userProfile = this.userProfileProps;
  },
  watch: {
    userProfileProps(newVal) {
      this.userProfile = newVal;
    },
    "userProfile.user_data.email"() {
      this.validateEmail();
      this.$refs.errorEmail?.classList?.remove("error-email");
      this.$refs.inputEmail?.classList?.remove("error-input-email");
    },
    "userProfile.user_data.company_phone"() {
      this.validCompanyPhone();
      this.$refs.errorCompanyPhone?.classList?.remove("error-email");
      this.$refs.inputCompanyPhone?.classList?.remove("error-input-email");
    },
    "userProfile.user_data.mobile"() {
      this.validateMobile();
      this.$refs.errorMobile?.classList?.remove("error-email");
      this.$refs.inputMobile?.classList?.remove("error-input-email");
    },
  },

  data() {
    return {
      userProfile: null,
    };
  },
  methods: {
    /**
     * Check policy
     */
    privacyPolicy(value) {
      return value === PRIVACY_POLICY.public ? "公開する" : "コンタクトのみ";
    },

    /**
     * Validate required of email
     */
    validateEmail() {
      if (this.userProfile.user_data) {
        const checkValidEmail = validEmail(this.userProfile.user_data?.email);
        if (!checkValidEmail) {
          this.$refs.errorEmail.classList.add("error-email");
          this.$refs.inputEmail.classList.add("error-input-email");
          return false;
        } else {
          this.$refs.errorEmail?.classList?.remove("error-email");
          this.$refs.inputEmail?.classList?.remove("error-input-email");
          return true;
        }
      }
    },

    /**
     * Check company phone is valid for japan
     */
    validCompanyPhone() {
      if (this.userProfile.user_data?.company_phone) {
        const isPhoneValid =
          isValidPhoneNum(this.userProfile.user_data?.company_phone) &&
          this.isJapanese(this.userProfile.user_data?.company_phone) &&
          this.userProfile.user_data?.company_phone[0] == "0";
        if (!isPhoneValid) {
          this.$refs.errorCompanyPhone?.classList?.add("error-email");
          this.$refs.inputCompanyPhone?.classList?.add("error-input-email");
          return false;
        } else {
          this.$refs.errorCompanyPhone?.classList?.remove("error-email");
          this.$refs.inputCompanyPhone?.classList?.remove("error-input-email");
          return true;
        }
      } else {
        return true;
      }
    },

    /**
     * Check mobile is valid for japan
     */
    validateMobile() {
      if (this.userProfile.user_data?.mobile) {
        const isPhoneValid =
          isValidPhoneNum(this.userProfile.user_data?.mobile) &&
          this.isJapanese(this.userProfile.user_data?.mobile) &&
          this.userProfile.user_data?.mobile[0] == "0";
        if (!isPhoneValid) {
          this.$refs.errorMobile?.classList?.add("error-email");
          this.$refs.inputMobile?.classList?.add("error-input-email");
          return false;
        } else {
          this.$refs.errorMobile?.classList?.remove("error-email");
          this.$refs.inputMobile?.classList?.remove("error-input-email");
          return true;
        }
      } else {
        return true;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../views/Auth/Login/Login.scss";
@import "../../views/Profile/Profile.scss";

.bg-color {
  background-color: $white2;
}
td {
  border: 1px $primary-color solid;
  height: 50px;
  padding-left: 20px;
  font-size: 14px;
  font-weight: 600;
}
.form-control {
  width: 55%;
}
</style>
