<template lang="">
  <div class="d-flex w-100 justify-content-center mt-20 border-line">
    <Logo
      externalClass="w-80-px h-80-px radius-50-percent fz-18 mb-15"
      title="ロゴ"
    />
  </div>
  <div class="w-100 d-flex justify-content-center pb-30 pl-20 pr-20">
    <div class="d-flex flex-column w-100">
      <div class="d-flex flex-column">
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
      <div class="w-100 d-flex justify-content-center">
        <div class="position-relative w-100 h-250-px">
          <img
            v-if="
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
                userProfile?.user_policy_data?.avatar_policy ===
                PRIVACY_POLICY.public
              "
              :imgUrl="userProfile.avatar ?? USER_AVATAR"
              class="avatar"
            />
            <Avatar v-else :imgUrl="USER_AVATAR" class="avatar" />
          </div>
        </div>
      </div>

      <div class="w-100 mt-30">
        <div>
          <div class="fz-16 font-weight-600 w-100 text-black1">
            {{ userProfile?.user_data?.nickname }}
          </div>
          <div
            v-if="
              userProfile?.user_policy_data?.profile_id_policy ===
              PRIVACY_POLICY.public
            "
            class="mt-20 fz-15 w-100 text-grey1"
          >
            chatmeeting ID : {{ userProfile?.user_data?.profile_id }}
          </div>
          <div
            v-if="
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
        <ProfileDetail
          :isSettingProps="false"
          :userProfileProps="userProfile"
        />
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "../../components/Avatar.vue";
import { USER_AVATAR, CHAIN_ICON } from "../../constants/imageConst";
import ProfileDetail from "../../components/ProfileModal/ProfileDetail.vue";
import UpLoadImage from "../../components/ProfileModal/UpLoadImage.vue";
import { b64toBlob } from "../../mixins/b64ToBlob";
import { PRIVACY_POLICY, NOTIFICATION_MESSAGE } from "../../constants";
import Logo from "../../components/Logo.vue";
import { getUserByProfileID } from "../../apis/account/user";
import { notification } from "ant-design-vue";
import { getFile } from "../../apis/getFile";

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
    this.PRIVACY_POLICY = PRIVACY_POLICY;
    this.NOTIFICATION_MESSAGE = NOTIFICATION_MESSAGE;
    if (this.$route.params.profile_id) {
      this.profileID = this.$route.params.profile_id;
    }
    this.getUserDetailFromProfileID();
  },

  data() {
    return {
      imgLink: "../../../images/banner.png",
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
    /**
     * User detail change
     */
    getUserChange(user) {
      if (user?.avatar) {
        this.tempAvatarUrl = [user?.avatar];
      }
      if (user?.profile_image) {
        this.tempBannerUrl = [user?.profile_image];
      }
      const userPolicy = user.user_policy || {};
      this.userProfile = {
        small_avatar: user?.small_avatar,
        avatar: user?.avatar,
        profile_image: user?.profile_image,
        id: user?.id,
        user_data: {
          nickname: user?.nickname ?? `${user.last_name} ${user.first_name}`,
          profile_id: user?.profile_id ?? "",
          self_introduction: user?.self_introduction ?? "",
          company_name: user?.company_name ?? "",
          department: user?.department ?? "",
          position: user?.position ?? "",
          address: user?.address ?? "",
          email: user?.email,
          url: user?.url ?? "",
          company_phone: user?.company_phone ?? "",
          internal_line: user?.internal_line ?? "",
          mobile: user?.mobile ?? "",
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
    },
    /**
     * Get user detail from store
     */
    async getUserDetailFromProfileID() {
      this.emitter.emit("isShowLoading", true);

      try {
        const { data } = await getUserByProfileID({
          profile_id: this.profileID,
        });

        const promises = [];
        const loadImage = async (imageField) => {
          if (data[imageField] != null) {
            try {
              const result = await getFile(data[imageField]);
              data[imageField] = URL.createObjectURL(result.data);
            } catch (error) {
              console.log(error);
            }
          }
        };

        await Promise.all([
          loadImage("avatar"),
          loadImage("small_avatar"),
          loadImage("profile_image"),
        ]);
        this.getUserChange(data);
        // this.emitter.emit("isShowLoading", false);
      } catch (error) {
        console.log(error);
        this.emitter.emit("isShowLoading", false);
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
  },
};
</script>
<style lang="scss" scoped>
@import "./Profile.scss";
</style>
