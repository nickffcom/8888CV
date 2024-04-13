<template lang="">
  <TableAccount :data="dataName" :rowData="rowDataName">
    <template #newName>
      <div class="w-100 d-flex align-items-center justify-content-center">
        <input
          type="text"
          class="w-45 h-40-px form-control pl-12 mr-20"
          v-model="lastName"
          ref="lastName"
          placeholder="姓"
        />
        <input
          type="text"
          class="w-45 h-40-px form-control pl-12 mr-20"
          v-model="firstName"
          placeholder="名"
          ref="firstName"
        />
      </div>
    </template>
  </TableAccount>
  <div class="text-black1 mt-10">
    サービスにご登録のお名前を変更できます。
    登録名は管理者および本人以外には公開されません。
  </div>
  <button class="status-primary-outline mt-25" @click="handleUpdateUser">
    保存する
  </button>
</template>
<script>
import TableAccount from "../../../components/TableAccount.vue";
import { RENDER_TYPE } from "../../../constants/table";

export default {
  name: "AccountName",
  components: {
    TableAccount,
  },
  props: {
    firstNameProps: { type: String },
    lastNameProps: { type: String },
  },
  created() {
    this.RENDER_TYPE = RENDER_TYPE;
    this.firstName = this.firstNameProps;
    this.lastName = this.lastNameProps;
  },
  emits: ["update"],
  watch: {
    firstName() {
      this.$refs.firstName?.classList.add("error-input-email");
      this.$refs.firstName?.classList.remove("error-input-email");
    },
    lastName() {
      this.$refs.lastName?.classList.add("error-input-email");
      this.$refs.lastName?.classList.remove("error-input-email");
    },
  },
  data() {
    return {
      arrayEdit: {},
      dataName: [
        {
          classTitle: "text-center text-global",
          title: "現在の名前 (登録名)",
          key: "currentName",
        },
        {
          classTitle: "text-center text-global",
          title: "新しい名前(登録名)",
          renderType: RENDER_TYPE.slot,
          slotName: "newName",
        },
      ],
      rowDataName: {
        currentName: this.lastNameProps + " " + this.firstNameProps,
        newName: "",
      },
      firstName: "",
      lastName: "",
    };
  },
  methods: {
    /**
     * Emit update user
     */
    handleUpdateUser() {
      this.onValidateFirstName();
      this.onValidateLastName();
      // Check duplicates
      if (!this.onValidateFirstName() || !this.onValidateLastName()) {
        return;
      }

      if (
        this.lastName !== this.lastNameProps ||
        this.firstName !== this.firstNameProps
      ) {
        this.$emit("update", {
          last_name: this.lastName,
          first_name: this.firstName,
        });
        this.rowDataName.currentName = this.lastName + " " + this.firstName;
      }
    },

    /**
     * Check empty first name
     */
    onValidateFirstName() {
      if (!this.firstName) {
        this.$refs.firstName.classList.add("error-input-email");
        return false;
      } else {
        this.$refs.firstName.classList.remove("error-input-email");
        return true;
      }
    },

    /**
     * Check empty last name
     */
    onValidateLastName() {
      if (!this.lastName) {
        this.$refs.lastName.classList.add("error-input-email");
        return false;
      } else {
        this.$refs.lastName.classList.remove("error-input-email");
        return true;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../Auth/Login/Login.scss";
</style>
