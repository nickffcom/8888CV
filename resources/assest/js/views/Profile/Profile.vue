<template lang="">
  <div
    v-if="!isHaveHeader"
    class="fz-16 font-weight-600 w-100 max-w-800-px mb-10 text-black1"
  >
    プロフィール
  </div>
  <div
    v-if="isHaveHeader"
    :class="[
      'd-flex w-100  justify-content-center mt-20 border-line',
      isHaveHeader && 'max-w-800-px',
    ]"
  >
    <Logo
      externalClass="w-80-px h-80-px radius-50-percent fz-18 mb-15"
      title="ロゴ"
    />
  </div>
  <div
    :class="[
      'w-100 d-flex justify-content-center pb-30',
      isHaveHeader && 'max-w-800-px',
    ]"
  >
    <div :class="['d-flex flex-column', !isHaveHeader && 'w-100']">
      <div v-if="isHaveHeader" class="d-flex flex-column">
        <div
          class="fz-23 font-weight-600 w-100 pl-20 pr-20 mb-10 text-center mt-30 text-black1"
        >
          プロフィール
        </div>
        <span class="fz-16 font-weight-400 text-grey1 text-center p-20">
          このページのリンクをコピーしてメールやチャット、
          SNSなどで伝えることで、 ChatMeeting
          でやり取りしたい相手を簡単に招待・コンタクト追加することができます。
        </span>
        <div class="d-flex justify-content-center p-20">
          <div
            class="cur-p d-flex ml-5 bg-color-white2 p-5 radius-8-px pl-10"
            @click="onCopyMeetingLink"
            :style="{ opacity: isCopied ? 0.5 : 1 }"
          >
            <div class="mr-5 text-global fz-14">
              {{ link + userProfile?.user_data?.profile_id }}
            </div>
            <img :src="CHAIN_ICON" alt="" class="w-25-px h-25-px mr-10" />
          </div>
        </div>
        <span class="fz-16 font-weight-400 text-grey1 text-center mb-40">
          コピーできます。
        </span>
      </div>
      <div
        :class="[
          'w-100 d-flex justify-content-center',
          isHaveHeader && 'max-w-800-px',
        ]"
      >
        <div class="position-relative w-100 h-250-px">
          <img
            v-if="
              userProfile?.user_data?.isContact ||
              userProfile?.user_policy_data?.profile_image_policy ===
                PRIVACY_POLICY.public
            "
            :src="userProfile?.profile_image ?? imgLink"
            alt=""
            class="w-100 h-100 banner"
          />
          <img v-else :src="imgLink" alt="" class="w-100 h-100 banner" />
          <div>
            <Avatar
              v-if="
                userProfile?.user_data?.isContact ||
                userProfile?.user_policy_data?.avatar_policy ===
                  PRIVACY_POLICY.public
              "
              :imgUrl="userProfile.avatar ?? USER_AVATAR"
              class="avatar"
            />
            <Avatar v-else :imgUrl="USER_AVATAR" class="avatar" />
          </div>
          <img
            v-if="isSetting"
            :src="CAMERA_ICON"
            alt=""
            class="camera-icon"
            @click="openUploadBanner"
          />
          <div
            v-if="isSetting"
            class="btn-change position-absolute btn fz-14 bg-color-grey1 text-white"
            @click="openUploadImage"
          >
            写真を変更する
          </div>
          <!-- Upload Avatar Image -->
          <div v-if="isUploadImage" class="upload-avatar-box">
            <div class="triangle" />
            <div
              class="avatar-fake bg-color-white ml-20 d-flex align-items-center justify-content-center"
            >
              <Avatar
                :imgUrl="USER_AVATAR"
                :class="[
                  tempAvatarUrl.length === 0 ? 'w-100 h-100' : 'w-0 h-0',
                ]"
              />
            </div>
            <div
              class="w-75 d-flex flex-column justify-content-around align-items-center"
            >
              <div
                class="d-flex w-100 pl-10 pb-15 pr-10 justify-content-center"
              >
                <UpLoadImage
                  externalClass="btn fz-14 text-global"
                  :buttonStyle="buttonStyle"
                  :previewStyleImage="previewStyle"
                  :imagesUrl="tempAvatarUrl"
                  :imageStyle="true"
                  title="ファイル選択"
                  @success="addAvatarFileImages"
                  @remove="removedFile"
                />
                <select
                  class="select-list fz-14 cur-p text-global w-150-px ml-20"
                  v-model="tempAvatarPolicy"
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
              <div class="fz-14">
                (最大 5MBまで。 JPEG, GIF, PNG が使えます)
              </div>
              <div
                class="d-flex mt-10 w-100 pl-40 pr-40 justify-content-center"
              >
                <div
                  class="btn m-0 fz-14 radius-5-px h-35-px w-100-px btn-decoration"
                  @click="closeUploadAvatar"
                >
                  キャンセル
                </div>
                <div
                  class="btn btn-primary fz-14 radius-5-px h-35-px w-100-px ml-15"
                  @click="saveUploadAvatar"
                >
                  保存する
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Upload Banner Image -->
        <div
          v-if="isUploadBanner"
          class="position-absolute upload-banner-box d-flex justify-content-center flex-column align-items-center"
          style="top: 10%"
        >
          <div
            :class="[
              'w-100 h-250-px max-w-800-px',
              tempBannerUrl.length === 0 ? 'bg-color-grey1' : '',
            ]"
          />

          <div
            :class="[
              'd-flex flex-column justify-content-around align-items-center w-100 mt-15',
              isHaveHeader && 'max-w-800-px',
            ]"
          >
            <div class="d-flex justify-content-between w-100">
              <UpLoadImage
                externalClass="btn fz-14 w-150-px text-global"
                :buttonStyle="buttonStyle"
                :previewStyleImage="previewBannerStyle"
                :imagesUrl="tempBannerUrl"
                title="ファイル選択"
                @success="addBannerFileImages"
                @remove="removedFileBanner"
              />
              <select
                class="select-list fz-14 cur-p text-global w-150-px"
                v-model="tempProfileImagePolicy"
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
            <div class="w-100 fz-14 mt-10">
              (最大 5MBまで。 JPEG, GIF, PNG が使えます)
            </div>
            <div class="d-flex mt-30 w-100 pl-40 pr-40 justify-content-center">
              <div
                class="btn m-0 fz-14 radius-5-px h-35-px w-100-px btn-decoration"
                @click="closeUploadBanner"
              >
                キャンセル
              </div>
              <div
                class="btn btn-primary fz-14 radius-5-px h-35-px w-100-px ml-55"
                @click="saveUploadBanner"
              >
                保存する
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        :class="[
          'w-100 mt-20 d-flex flex-row-reverse',
          isHaveHeader && 'max-w-800-px',
        ]"
      >
        <div
          class="btn-outline btn w-150-px h-30-px fz-14 m-0 cur-p"
          @click="openChangeProfile"
          v-if="!isSetting"
        >
          プロフィール編集
        </div>
      </div>
      <div :class="[' w-100', isHaveHeader && 'max-w-800-px']">
        <div v-if="!isSetting">
          <div class="fz-16 font-weight-600 w-100 text-black1">
            {{ userProfile?.user_data?.nickname }}
          </div>
          <div class="mt-20 fz-15 w-100 text-grey1">
            chatmeeting ID : {{ userProfile?.user_data?.profile_id }}
          </div>
          <div
            v-if="
              userProfile?.user_data?.isContact ||
              userProfile?.user_policy_data?.self_introduction_policy ===
                PRIVACY_POLICY.public
            "
            class="mt-20 fz-15 w-100 text-grey1 d-flex introduction"
          >
            {{ userProfile?.user_data?.self_introduction }}
          </div>
          <div class="mt-20 fz-15 w-100 d-flex align-items-center text-grey1">
            プロフィールリンク :
            <div
              class="cur-p d-flex ml-5 bg-color-white2 p-5 radius-8-px pl-10"
              @click="onCopyMeetingLink"
              :style="{ opacity: isCopied ? 0.5 : 1 }"
            >
              <div class="mr-5 text-global">
                {{ link + userProfile?.user_data?.profile_id }}
              </div>
              <img :src="CHAIN_ICON" alt="" class="w-25-px h-25-px mr-10" />
            </div>
          </div>
        </div>
        <div v-else>
          <table class="mt-40 w-100 border-primary-color">
            <tr>
              <td class="w-25 text-global bg-color">表示名</td>
              <td class="pt-10 pb-10">
                <div class="d-flex">
                  <input
                    type="text"
                    class="form-control h-50-px w-40"
                    v-model="userProfile.user_data.nickname"
                  />
                  <div class="mt-13 d-flex">
                    <label class="ml-20">
                      <input
                        type="checkbox"
                        class="primary"
                        v-model="userProfile.user_policy_data.nickname_policy"
                      />
                      <span />
                    </label>
                    <div class="font-weight-500 fz-14">
                      コンタクトの検索対象にする
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="w-25 text-global bg-color">chatmeeting ID</td>
              <td class="pt-10 pb-10">
                <div class="d-flex w-100 align-items-center">
                  <input
                    type="text"
                    class="form-control h-50-px mr-70"
                    v-model="userProfile.user_data.profile_id"
                  />
                  <select
                    class="select-list fz-14 cur-p text-global w-150-px"
                    v-model="userProfile.user_policy_data.profile_id_policy"
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
              <td class="w-25 text-global bg-color">自己紹介</td>
              <td class="pt-10 pb-10">
                <div class="d-flex w-100 align-items-center">
                  <textarea
                    class="form-control mr-70"
                    v-model="userProfile.user_data.self_introduction"
                  />
                  <select
                    class="select-list fz-14 cur-p text-global w-150-px"
                    v-model="
                      userProfile.user_policy_data.self_introduction_policy
                    "
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
        <ProfileDetail
          :isSettingProps="isSetting"
          :userProfileProps="userProfile"
          ref="profileDetail"
          :isContact="true"
        />
        <div
          class="mt-50 d-flex flex-row justify-content-center align-items-center"
          v-if="isSetting"
        >
          <div
            class="btn-outline btn m-0 fz-14 radius-5-px mr-15 h-35-px w-100-px"
            @click="cancelChangeProfile"
          >
            キャンセル
          </div>
          <div
            class="btn btn-primary fz-14 radius-5-px h-35-px w-100-px"
            @click="saveChangeProfile"
          >
            保存する
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "../../components/Avatar.vue";
import {
  USER_AVATAR,
  CHAIN_ICON,
  CAMERA_ICON,
} from "../../constants/imageConst";
import ProfileDetail from "../../components/ProfileModal/ProfileDetail.vue";
import UpLoadImage from "../../components/ProfileModal/UpLoadImage.vue";
import { b64toBlob } from "../../mixins/b64ToBlob";
import {
  PRIVACY_POLICY,
  NOTIFICATION_MESSAGE,
  LOCAL_STORAGE_USER_INFO,
} from "../../constants";
import Logo from "../../components/Logo.vue";
import { mapState, mapMutations } from "vuex";
import { updateUserDetailByID, getUserDetail } from "../../apis/account/user";
import { notification } from "ant-design-vue";

export default {
  components: {
    Avatar,
    ProfileDetail,
    UpLoadImage,
    Logo,
  },
  props: {
    isHaveHeader: {
      type: Boolean,
      default: false,
    },
  },
  mixins: [b64toBlob],
  created() {
    this.USER_AVATAR = USER_AVATAR;
    this.CHAIN_ICON = CHAIN_ICON;
    this.CAMERA_ICON = CAMERA_ICON;
    this.PRIVACY_POLICY = PRIVACY_POLICY;
    this.NOTIFICATION_MESSAGE = NOTIFICATION_MESSAGE;
    this.LOCAL_STORAGE_USER_INFO = LOCAL_STORAGE_USER_INFO;
    this.getUserDetail();
  },
  computed: {
    ...mapState("account", {
      accountDetail: "accountDetail",
    }),
  },
  data() {
    return {
      imgLink: "../../../images/banner.png",
      isSetting: false,
      userProfile: {},
      tempAvatarUrl: [],
      tempBannerUrl: [],
      previewStyle: `
          width: 70px;
          height: 70px;
          background-color: #dfdfdf;
          border-radius: 50px;
          position:absolute;
          left: 21px;
          top: 40px;
          `,
      previewBannerStyle: `
          width: 100%;
          height: 250px;
          position:absolute;
          left: 0px;
          top: 26px;
          padding: 0 50px
          `,
      buttonStyle: `border:1px #55aeb4 dashed;`,
      isUploadImage: false,
      isUploadBanner: false,
      isCopied: false,
      link: window.origin + "/profile" + "/",
      profileID: null,
      isCurrentUser: true,
      tempAvatarPolicy: 1,
      tempProfileImagePolicy: 1,
    };
  },
  watch: {
    userProfile(newVal) {
      this.userProfile = newVal;
    },
    profileID(newVal) {
      this.profileId = newVal;
    },
  },
  methods: {
    ...mapMutations("account", ["setAccountDetail"]),

    /**
     * User detail change
     */
    getUserChange(currentUser) {
      if (currentUser?.avatar) {
        this.tempAvatarUrl = [currentUser?.avatar];
      }
      if (currentUser?.profile_image) {
        this.tempBannerUrl = [currentUser?.profile_image];
      }
      const currentUserStore = JSON.parse(localStorage.getItem("USER_INFO"));

      this.isCurrentUser = currentUserStore.id === currentUser.id;

      const userPolicy = currentUser.user_policy || {};
      this.userProfile = {
        small_avatar: currentUser?.small_avatar,
        avatar: currentUser?.avatar,
        profile_image: currentUser?.profile_image,
        id: currentUser?.id,
        user_data: {
          nickname:
            currentUser?.nickname ??
            `${currentUser.last_name} ${currentUser.first_name}`,
          profile_id: currentUser?.profile_id ?? "",
          self_introduction: currentUser?.self_introduction ?? "",
          company_name: currentUser?.company_name ?? "",
          department: currentUser?.department ?? "",
          position: currentUser?.position ?? "",
          address: currentUser?.address ?? "",
          email: currentUser?.email,
          url: currentUser?.url ?? "",
          company_phone: currentUser?.company_phone ?? "",
          internal_line: currentUser?.internal_line ?? "",
          mobile: currentUser?.mobile ?? "",
          isContact: this.isCurrentUser
            ? this.isCurrentUser
            : currentUser?.isContact ?? "",
        },
        user_policy_data: {
          nickname_policy:
            userPolicy?.nickname_policy == this.PRIVACY_POLICY.public
              ? true
              : false,
          self_introduction_policy:
            userPolicy?.self_introduction_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          profile_id_policy:
            userPolicy?.profile_id_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          company_name_policy:
            userPolicy?.company_name_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          department_policy:
            userPolicy?.department_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          position_policy:
            userPolicy?.position_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          address_policy:
            userPolicy?.address_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          url_policy:
            userPolicy?.url_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          email_policy:
            userPolicy?.email_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          company_phone_policy:
            userPolicy?.company_phone_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          internal_line_policy:
            userPolicy?.internal_line_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          avatar_policy:
            userPolicy?.avatar_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          profile_image_policy:
            userPolicy?.profile_image_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
          mobile_policy:
            userPolicy?.mobile_policy == this.PRIVACY_POLICY.public
              ? this.PRIVACY_POLICY.public
              : this.PRIVACY_POLICY.contact,
        },
      };
      this.tempProfileImagePolicy = userPolicy.profile_image_policy ?? 1;
      this.tempAvatarPolicy = userPolicy.avatar_policy ?? 1;
    },
    /**
     * Get user detail from store
     */
    async getUserDetail() {
      this.emitter.emit("isShowLoading", true);
      if (this.accountDetail) {
        this.getUserChange(this.accountDetail);
        this.emitter.emit("isShowLoading", false);
      } else {
        try {
          const { data } = await getUserDetail();
          this.getUserChange(data);
          // this.emitter.emit("isShowLoading", false);
        } catch (error) {
          console.log(error);
          this.emitter.emit("isShowLoading", false);
        }
      }
    },

    /**
     * Copy meeting link
     */
    async onCopyMeetingLink() {
      // Navigator clipboard api needs a secure context (https)
      const copyLink = this.link + this.userProfile.user_data.profile_id;
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(copyLink);
        this.isCopied = true;
        notification.info({
          message: NOTIFICATION_MESSAGE.COPY_SUCCESS,
        });
        setTimeout(() => {
          this.isCopied = false;
        }, 2000);
      } else {
        // Use the 'out of viewport hidden text area' trick
        const textArea = document.createElement("textarea");
        textArea.value = copyLink;

        // Move textarea out of the viewport so it's not visible
        textArea.style.position = "absolute";
        textArea.style.left = "-999999px";

        document.body.prepend(textArea);
        textArea.select();
        try {
          document.execCommand("copy");
          this.isCopied = true;
          setTimeout(() => {
            this.isCopied = false;
          }, 2000);
        } catch (error) {
          console.error(error);
        } finally {
          textArea.remove();
        }
      }
    },

    /**
     * Add new file banner
     */
    addBannerFileImages(data) {
      this.tempBannerUrl = [data];
    },

    /**
     * Save Banner
     */
    saveUploadBanner() {
      if (typeof this.tempBannerUrl[0] !== "string") {
        this.userProfile = {
          ...this.userProfile,
          profile_image: this.tempBannerUrl[0]?.dataURL,
        };
      }
      this.userProfile.user_policy_data.profile_image_policy =
        this.tempProfileImagePolicy;
      this.isUploadBanner = false;
    },

    /**
     * Open upload box banner
     */
    openUploadBanner() {
      this.isUploadBanner = !this.isUploadBanner;
    },

    /**
     * Close upload box banner
     */
    closeUploadBanner() {
      this.isUploadBanner = false;
      this.tempProfileImagePolicy =
        this.userProfile.user_policy_data.profile_image_policy;
    },

    /**
     * Open upload box avatar
     */
    openUploadImage() {
      this.isUploadImage = !this.isUploadImage;
    },

    /**
     * Close upload box avatar
     */
    closeUploadAvatar() {
      this.isUploadImage = false;
      this.tempAvatarPolicy = this.userProfile.user_policy_data.avatar_policy;
    },

    /**
     * Save Avatar
     */
    saveUploadAvatar() {
      if (typeof this.tempAvatarUrl[0] !== "string") {
        this.userProfile = {
          ...this.userProfile,
          avatar: this.tempAvatarUrl[0]?.dataURL ?? null,
        };
      }
      this.userProfile.user_policy_data.avatar_policy = this.tempAvatarPolicy;

      this.isUploadImage = false;
    },

    /**
     * Add new file avatar
     */
    addAvatarFileImages(data) {
      this.tempAvatarUrl = [data];
    },

    /**
     * Remove new file image
     */
    removedFile(file) {
      this.tempAvatarUrl = [];
    },

    /**
     * Remove new file image
     */
    removedFileBanner(file) {
      this.tempBannerUrl = [];
    },

    /**
     * Open change profile setting
     */
    openChangeProfile() {
      this.isSetting = true;
    },

    /**
     * Close change profile setting
     */
    closeChangeProfile() {
      this.isSetting = false;
    },

    /**
     * Cancel change profile setting
     */
    cancelChangeProfile() {
      this.getUserDetail();
      this.closeChangeProfile();
    },

    /**
     * Save change profile setting
     */
    async saveChangeProfile() {
      if (this.isUploadBanner || this.isUploadImage) {
        notification.error({
          message: NOTIFICATION_MESSAGE.SAVE_PROFILE_FALSE,
        });
      } else {
        const userDetailRef = this.$refs.profileDetail;
        let data = {
          user_data: {
            ...this.userProfile.user_data,
          },
          user_policy_data: {
            ...this.userProfile.user_policy_data,
            nickname_policy:
              this.userProfile.user_policy_data.nickname_policy == true
                ? this.PRIVACY_POLICY.public
                : this.PRIVACY_POLICY.contact,
          },
        };
        if (typeof this.tempAvatarUrl[0] !== "string") {
          data = {
            ...data,
            avatar: this.tempAvatarUrl[0]
              ? this.b64toBlob(this.tempAvatarUrl[0])
              : "deleted",
          };
        }
        if (typeof this.tempBannerUrl[0] !== "string") {
          data = {
            ...data,
            profile_image: this.tempBannerUrl[0]
              ? this.b64toBlob(this.tempBannerUrl[0])
              : "deleted",
          };
        }
        userDetailRef.validateEmail();
        userDetailRef.validCompanyPhone();
        userDetailRef.validateMobile();
        if (
          userDetailRef.validateEmail() &&
          userDetailRef.validCompanyPhone() &&
          userDetailRef.validateMobile()
        ) {
          this.closeChangeProfile();
          this.emitter.emit("isShowLoading", true);
          let formData = new FormData();
          for (const key in data.user_data) {
            formData.append(`user_data[${key}]`, data.user_data[key]);
          }

          for (const key in data.user_policy_data) {
            formData.append(
              `user_policy_data[${key}]`,
              data.user_policy_data[key]
            );
          }
          if (data.small_avatar) {
            formData.append("small_avatar", data.small_avatar);
          }
          if (data.avatar) {
            formData.append("avatar", data.avatar);
          }
          if (data.profile_image) {
            formData.append("profile_image", data.profile_image);
          }
          try {
            const { data } = await updateUserDetailByID(
              this.userProfile.id,
              formData
            );
            this.setAccountDetail(data);
            localStorage.setItem(LOCAL_STORAGE_USER_INFO, JSON.stringify(data));
            localStorage.setItem(
              "fullName",
              `${data.last_name} ${data.first_name}`
            );
            notification.success({
              message: NOTIFICATION_MESSAGE.UPDATE_SUCCESS,
            });
            this.emitter.emit("isShowLoading", false);
          } catch (error) {
            notification.error({
              message: NOTIFICATION_MESSAGE.UPDATE_FAIL,
            });
            this.emitter.emit("isShowLoading", false);
            console.log(error);
          }
        }
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Profile.scss";
</style>
