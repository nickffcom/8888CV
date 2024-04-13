<template lang="">
  <div class="d-flex vh-100 account">
    <ContentLeft :data="data" @clicked="getObjectData" />
    <div class="d-flex flex-column w-100 mt-15 ml-40">
      <span class="account__title fz-16">{{ title }}</span>
      <div v-if="title == ACCOUNT_CONTENT.USER_INFORMATION">
        <TableAccount
          :edit="true"
          :data="dataInfo"
          :rowData="rowDataInfo"
          @clicked="getEmit"
        >
          <template #password="{ record }">
            <input
              type="password"
              class="h-40-px account__input border-none"
              :value="record"
              :disabled="true"
              spellcheck="false"
            />
          </template>
        </TableAccount>
      </div>
      <div v-else-if="title == ACCOUNT_CONTENT.CHANGED_NAME">
        <AccountName
          :firstNameProps="rowDataInfo.firstName"
          :lastNameProps="rowDataInfo.lastName"
          @update="handleUpdateUser"
        />
      </div>
      <div v-else-if="title == ACCOUNT_CONTENT.CHANGED_EMAIL">
        <AccountEmail
          :currentEmailProps="rowDataInfo.email"
          :newEmailProps="rowDataInfo.newEmail"
          :confirmNewEmailProps="rowDataInfo.confirmNewEmail"
          @update="handleUpdateUser"
        />
      </div>
      <div v-else-if="title == ACCOUNT_CONTENT.CHANGED_PASSWORD">
        <AccountPassword
          :currentPasswordProps="rowDataInfo.oldPassword"
          :newPasswordProps="rowDataInfo.newPassword"
          :confirmNewPasswordProps="rowDataInfo.confirmNewPassword"
          @update="handleUpdateAccountPassword"
          ref="accountPassword"
        />
      </div>
    </div>
  </div>
</template>
<script>
import ContentLeft from "../../../components/Admin/ContentLeft.vue";
import TableAccount from "../../../components/TableAccount.vue";
import { RENDER_TYPE } from "../../../constants/table";
import {
  ACCOUNT_CONTENT,
  NOTIFICATION_MESSAGE,
} from "../../../constants/index";
import AccountName from "./AccountName.vue";
import AccountEmail from "./AccountEmail.vue";
import AccountPassword from "./AccountPassword.vue";
import { mapMutations } from "vuex";
import {
  getUserDetail,
  updateAccount,
  updateUserDetail,
} from "../../../apis/account/user";
import { notification } from "ant-design-vue";

export default {
  name: "Account",
  components: {
    ContentLeft,
    TableAccount,
    AccountName,
    AccountEmail,
    AccountPassword,
  },
  created() {
    this.RENDER_TYPE = RENDER_TYPE;
    this.ACCOUNT_CONTENT = ACCOUNT_CONTENT;
    this.getUserInfo();
  },
  data() {
    return {
      arrayEdit: {},
      enableEdit: false,
      showPassword: false,
      data: [
        {
          id: 1,
          title: "アカウント設定",
          subData: [
            {
              id: 1,
              subTittle: ACCOUNT_CONTENT.USER_INFORMATION,
            },
            {
              id: 2,
              subTittle: ACCOUNT_CONTENT.CHANGED_NAME,
            },
            {
              id: 3,
              subTittle: ACCOUNT_CONTENT.CHANGED_EMAIL,
            },
            {
              id: 4,
              subTittle: ACCOUNT_CONTENT.CHANGED_PASSWORD,
            },
          ],
        },
      ],
      title: ACCOUNT_CONTENT.USER_INFORMATION,
      dataInfo: [
        {
          classTitle: "text-center text-global",
          title: "名前 (登録名)",
          key: "name",
        },
        {
          classTitle: "text-center text-global",
          title: "メールアドレス",
          key: "email",
        },
        {
          classTitle: "text-center text-global",
          title: "パスワード",
          renderType: RENDER_TYPE.slot,
          slotName: "password",
        },
      ],
      rowDataInfo: {},
    };
  },
  methods: {
    ...mapMutations("account", ["setActive", "isActive", "setAccountDetail"]),
    getObjectData(data) {
      this.title = data.subTittle;
    },
    /**
     * Active when click
     * @param {*} data
     */
    getEmit(data) {
      if (data === "name") {
        this.title = ACCOUNT_CONTENT.CHANGED_NAME;
        this.setActive({ index: 1, subIndex: 2 });
      } else if (data === "email") {
        this.title = ACCOUNT_CONTENT.CHANGED_EMAIL;
        this.setActive({ index: 1, subIndex: 3 });
      } else {
        this.title = ACCOUNT_CONTENT.CHANGED_PASSWORD;
        this.setActive({ index: 1, subIndex: 4 });
      }
    },
    /**
     * Get current user info
     */
    async getUserInfo() {
      this.rowDataInfo = {};
      this.emitter.emit("isShowLoading", true);

      try {
        const { data } = await getUserDetail();
        const userDetail = {
          name: data?.last_name + " " + data?.first_name,
          lastName: data?.last_name,
          firstName: data?.first_name,
          email: data?.email,
          newEmail: "",
          confirmEmail: "",
          password: "*******",
          oldPassword: "",
          newPassword: "",
          confirmPassword: "",
        };
        this.setAccountDetail(userDetail);
        this.rowDataInfo = {
          ...userDetail,
        };
        this.emitter.emit("isShowLoading", false);
      } catch (error) {
        console.error(error);
        this.emitter.emit("isShowLoading", false);
      }
    },
    /**
     * Update current user
     * @param {} data
     */
    async handleUpdateUser(data) {
      this.emitter.emit("isShowLoading", true);
      try {
        await updateUserDetail(data);
        notification.success({
          message: NOTIFICATION_MESSAGE.UPDATE_SUCCESS,
        });
        this.getUserInfo();
      } catch (error) {
        console.error(error);
        notification.error({
          message: NOTIFICATION_MESSAGE.UPDATE_FAIL,
        });
        this.emitter.emit("isShowLoading", false);
      }
    },
    /**
     * Update current user password
     * @param {} data
     */
    async handleUpdateAccountPassword(data) {
      this.emitter.emit("isShowLoading", true);
      try {
        await updateAccount(data);
        this.$refs.accountPassword.wrongPassword = false;
        notification.success({
          message: NOTIFICATION_MESSAGE.UPDATE_SUCCESS,
        });
        this.getUserInfo();
      } catch (error) {
        console.error(error);
        this.$refs.accountPassword.wrongPassword = true;
        notification.error({
          message: NOTIFICATION_MESSAGE.UPDATE_FAIL,
        });
        this.emitter.emit("isShowLoading", false);
      }
    },
  },
};
</script>
<style lang="scss">
@import "./Account.scss";
</style>
