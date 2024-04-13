<template lang="">
  <div>
    <div
      class="h-72-px d-flex justify-content-between align-items-center header"
    >
      <div class="d-flex align-items-center content-header-left">
        <img :src="EXCLAMATION_MARK" alt="" srcset="" class="h-25-px" />
        <div class="d-flex text-grey4">
          <span class="text-grey4">
            フリープラン利用中。 一部機能が制限されています。
          </span>
          (
          <span class="text-global sub-text">詳細はこちら</span>
          )
        </div>
      </div>
      <div
        class="d-flex align-items-center justify-content-between content-header-right mr-30"
      >
        <img :src="FOLDER" alt="" srcset="" class="h-35-px mr-5" />
        <div
          @click="showInvitation"
          v-if="isMatchedRoute('Home') || isMatchedRoute('MeetingDetail')"
        >
          <img :src="USER_ADD" alt="" srcset="" class="h-35-px mr-5 cur-p" />
        </div>
        <Avatar
          :imgUrl="accountDetail.avatar || avatarUser || USER_IMAGE"
          externalClass="w-35-px h-35-px"
        />
        <div class="name ml-10 text-center cur-p" @click="openProfileScreen">
          {{ getFullName() || getFullNameLocal() }}
        </div>
        <div
          @mouseenter="isHovered = true"
          @mouseleave="isHovered = false"
          class="list-menu position-relative cur-p"
        >
          <img
            :src="isHovered ? ARROW_UP : ARROW_DOWN"
            :class="[
              'toggle-sidebar-icon position-relative cur-p h-25-px arrow',
              isHovered && 'logo-xl-btn-collapse',
            ]"
          />

          <ul :class="['position-absolute p-0', isHovered && 'd-block']">
            <li
              class="fz-14 lh-35 h-35-px text-left item pl-20"
              @click="openProfileModel"
            >
              プロフィール
            </li>
            <li class="fz-14 lh-35 h-35-px text-left item pl-20">環境設定</li>
            <li
              @click="goToSettingAccount"
              class="fz-14 lh-35 h-35-px text-left item pl-20"
            >
              アカウント設定
            </li>
            <li
              class="fz-14 lh-35 h-35-px text-left item pl-20"
              @click="handleShowModalLogout"
            >
              ログアウト
            </li>
          </ul>
        </div>
      </div>
    </div>
    <ConfirmModal
      :showModal="showProfileScreen"
      @closeModal="closeProfileScreen"
      @save="closeProfileScreen"
      :showFooter="false"
      :widthCustom="875"
      :modalCustomClass="'top-20-px'"
    >
      <template #content>
        <Profile />
      </template>
    </ConfirmModal>
    <ConfirmModal
      :showModal="showModalInvitation"
      @closeModal="closeInvitation"
      @save="closeInvitation"
      :showFooter="false"
      :widthCustom="750"
      title="コンタクト管理"
    >
      <template #content>
        <Invite :data="data" @clicked="getTitleClick" :icon="true" />
        <Invitation v-if="titleInvite == INVITATION_CONTENT.INVITATION" />
        <FindingAcquaintances
          v-else-if="titleInvite == INVITATION_CONTENT.FIND_ACQUAINTANCES"
        />
        <ContactList
          v-else-if="titleInvite == INVITATION_CONTENT.CONTACT_LIST"
        />
        <Lobby v-else />
      </template>
    </ConfirmModal>
    <ConfirmModal
      :showModal="showModalLogout"
      @closeModal="closeModalLogout"
      @save="closeModalLogout"
      :showFooter="false"
      :widthCustom="400"
    >
      <template #content>
        <div
          class="d-flex gap-20 flex-column justify-content-center align-items-center"
        >
          <i class="fas fa-sign-out-alt text-global" />
          <div class="text-black1 fz-14 font-weight-600">
            本当にログアウトしますか?
          </div>
          <div
            class="d-flex fz-14 justify-content-between w-100 align-items-center"
          >
            <span class="text-underline text-grey1">キャンセル</span>
            <button
              class="button-ok bg-color-primary text-white w-70-px radius-12-px"
              @click="handleLogout"
            >
              OK
            </button>
          </div>
        </div>
      </template>
    </ConfirmModal>
  </div>
</template>
<script>
import Avatar from "../Avatar.vue";
import {
  USER_IMAGE,
  FOLDER,
  EXCLAMATION_MARK,
  USER_ADD,
  ARROW_DOWN,
  ARROW_UP,
} from "../../../js/constants/imageConst";
import ConfirmModal from "../ConfirmModal.vue";
import Profile from "../../views/Profile/Profile.vue";
import Invite from "../Invite.vue";
import Invitation from "../../views/Invitation/Invitation.vue";
import { INVITATION_CONTENT } from "../../constants";
import FindingAcquaintances from "../../views/Invitation/FindingAcquaintances";
import ContactList from "../../views/Invitation/ContactList.vue";
import Lobby from "../../views/Invitation/Lobby.vue";
import { mapMutations, mapState } from "vuex";
import { logout } from "../../apis/auth/auth";
import { getFile } from "../../apis/getFile";
export default {
  created() {
    this.FOLDER = FOLDER;
    this.EXCLAMATION_MARK = EXCLAMATION_MARK;
    this.USER_ADD = USER_ADD;
    this.ARROW_DOWN = ARROW_DOWN;
    this.ARROW_UP = ARROW_UP;
    this.USER_IMAGE = USER_IMAGE;
    this.INVITATION_CONTENT = INVITATION_CONTENT;
    this.userInfor = JSON.parse(localStorage.getItem("USER_INFO"));
    if (this.isMatchedRoute("HomeByLinkID")) {
      this.renderAvatar();
    }
  },
  components: {
    Avatar,
    ConfirmModal,
    Profile,
    Invite,
    Invitation,
    FindingAcquaintances,
    ContactList,
    Lobby,
  },
  computed: {
    ...mapState("account", {
      accountDetail: "accountDetail",
    }),
    ...mapState("invitation", [
      "invitation",
      "showModalInvitation",
      "titleInvite",
    ]),
  },

  data() {
    return {
      avatarUser: null,
      userInfor: null,
      isHovered: false,
      showProfile: false,
      showProfileScreen: false,
      showModalLogout: false,
      data: [
        {
          id: 1,
          subData: [
            {
              id: 1,
              subTitle: INVITATION_CONTENT.INVITATION,
            },
            {
              id: 2,
              subTitle: INVITATION_CONTENT.FIND_ACQUAINTANCES,
            },
            {
              id: 3,
              subTitle: INVITATION_CONTENT.CONTACT_LIST,
            },
            {
              id: 4,
              subTitle: INVITATION_CONTENT.LOBBY,
            },
          ],
        },
      ],
    };
  },
  methods: {
    ...mapMutations("invitation", ["setShowModalInvitation", "setTitleInvite"]),
    renderAvatar() {
      getFile(this.userInfor.avatar)
        .then((result) => {
          this.avatarUser = URL.createObjectURL(result.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getFullName() {
      const accountDetail = this.accountDetail;
      if (accountDetail.last_name || accountDetail.first_name) {
        this.userInfor.first_name = accountDetail.first_name;
        this.userInfor.last_name = accountDetail.last_name;
        localStorage.setItem("USER_INFO", JSON.stringify(this.userInfor));
        return (
          accountDetail.nickname ??
          `${accountDetail.last_name} ${accountDetail.first_name}`
        );
      }
    },
    getFullNameLocal() {
      if (this.userInfor.last_name || this.userInfor.first_name) {
        return (
          this.userInfor.nickname ||
          `${this.userInfor.last_name} ${this.userInfor.first_name}`
        );
      }
    },
    closeModalLogout() {
      this.showModalLogout = false;
    },
    handleShowModalLogout() {
      this.showModalLogout = true;
    },
    /**
     * get title
     */
    getTitleClick(data) {
      this.setTitleInvite(data.subTitle);
    },
    /**
     * show modal invitation
     */
    showInvitation() {
      this.setShowModalInvitation(true);
    },
    /**
     * close modal invitation
     */
    closeInvitation() {
      this.setShowModalInvitation(false);
    },
    /**
     * Open Profile Model
     */
    openProfileScreen() {
      const currentUser = JSON.parse(localStorage.getItem("USER_INFO"));
      window.open(window.origin + "/profile" + "/" + currentUser.profile_id);
    },

    /**
     * close Profile
     */
    closeProfileScreen() {
      this.showProfileScreen = false;
    },

    /**
     * Open Profile Model
     */
    openProfileModel() {
      this.showProfileScreen = true;
    },

    /**
     * close Profile
     */
    closeProfile() {
      this.showProfile = false;
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
    goToSettingAccount() {
      this.$router.push(`/account`);
    },
    /**
     * handle logout
     */
    handleLogout() {
      this.emitter.emit("isShowLoading", true);
      logout();
    },
  },
};
</script>
<style lang="scss">
.header {
  border-left: 1px solid #eee;
  padding-left: 20px;
}

.list-menu {
  list-style: none;
  z-index: 1;
}

.list-menu > li {
  color: #fff;
  display: block;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
}

.list-menu ul {
  overflow: hidden;
  right: 0;
  height: 0;
  transition: height 0.65s ease, opacity 0.65s ease;
  top: 40px;
  background-color: #469499;
  width: 150px !important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.list-menu:hover ul {
  height: 140px;
  opacity: 1;
}

.item {
  color: #fff;
  list-style-type: none;

  &:hover {
    background-color: #eee;
    color: #615a5a;
    cursor: pointer;
  }
}

.name {
  width: fit-content;
  padding: 0px 5px;
  letter-spacing: 2px;
  width: fit-content;
  color: #615a5a;
}

.sub-text {
  border-bottom: 1px solid;
  letter-spacing: 2px;
}

.content-header-right {
  width: 2́́́́80px;
}

.content-header-left {
  @include res_max-width(992) {
    margin-left: 20px;
  }
}

.toggle-sidebar-icon {
  transform: scale(1);
  transition: 0.3s ease;
  user-select: none;
}

.logo-xl-btn-collapse {
  transform: scaleX(-1);
}

.arrow-container {
  cursor: pointer;
}

.button-ok {
  border: none;
  padding: 2px 0;

  &:focus-visible {
    outline: none;
  }
}
</style>
