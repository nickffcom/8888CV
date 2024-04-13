<template lang="">
  <div
    class="list-invitation d-flex flex-column w-100 h-100 text-black1 fz-14 align-items-center mt-5"
  >
    <div class="w-100 d-flex flex-column p-20">
      <div class="d-flex flex-grow-1 align-items-center position-relative">
        <input
          type="text"
          v-model="content"
          class="w-100 h-40-px pl-40 radius-12-px"
          placeholder="コンタクト一覧から検索(名前、 Chat meeting)"
        />
        <div class="position-absolute fz-16 text-grey1 left-16-px">
          <i class="fa fa-search" />
        </div>
      </div>
      <div class="mt-20 participant-box">
        <div
          class="d-flex align-items-center justify-content-between padding-line"
        >
          <div class="d-flex align-items-center">
            <label class="mt-5">
              <input
                type="checkbox"
                class="primary"
                key="checkAll"
                :checked="isCheckAll"
                @change="(event) => handleCheckAllBox(event.target.checked)"
              />
              <span />
            </label>
            <div class="text-grey1">すべて選択</div>
          </div>
          <select
            v-model="isChangeAllRole"
            @change="handleChangeAllRole"
            class="select-list cur-p text-global w-135-px"
          >
            <option :value="null">
              <span class="px-3">権限一括設定</span>
            </option>
            <option :value="PARTICIPANT_ROLE.manager" class="cur-p text-global">
              <span class="px-3">管理者</span>
            </option>
            <option :value="PARTICIPANT_ROLE.guest" class="cur-p text-global">
              <span class="px-3">ゲスト</span>
            </option>
          </select>
        </div>
        <div class="mt-20">
          <BaseTable
            :columns="columns"
            :data="participants"
            emptyText="表示情報はありません。"
            :isHaveHeader="false"
          >
            <template #checked="{ record }">
              <div class="d-flex align-items-center">
                <label
                  v-if="
                    record.id !== meetingDetail.current_participant_id &&
                    record.userId
                  "
                  class="mt-5"
                >
                  <input
                    type="checkbox"
                    class="primary"
                    :disable="false"
                    :checked="isCheckedBox(record)"
                    v-model="record.showSelect"
                    :key="record?.id"
                    @change="
                      (event) => handleCheckOneBox(event.target.checked, record)
                    "
                  />
                  <span />
                </label>
                <div v-else class="w-20-px h-20-px ml-20" />
                <Avatar
                  :imgUrl="record?.avatar ?? USER_IMAGE"
                  class="avatar h-30-px w-30-px mr-10"
                />
                <div class="text-grey1">
                  {{ record?.name }}
                </div>
              </div>
            </template>
            <template #role="{ record }">
              <select
                v-if="
                  checkedItems.find(
                    (id) =>
                      id === record.id &&
                      id !== meetingDetail.current_participant_id &&
                      record.userId
                  )
                "
                class="select-list cur-p text-global w-100-px"
                :key="`select-${record?.id}`"
                v-model="record.role"
              >
                <option
                  :value="PARTICIPANT_ROLE.manager"
                  class="cur-p text-global"
                >
                  <span class="px-3">管理者</span>
                </option>
                <option
                  :value="PARTICIPANT_ROLE.guest"
                  class="cur-p text-global"
                >
                  <span class="px-3">ゲスト</span>
                </option>
              </select>
            </template>
          </BaseTable>
        </div>
        <div class="mt-10 padding-line d-flex cur-p" @click="handleGoToEdit">
          <img :src="PENCIL" class="w-20-px h-20-px mr-10" />
          <div class="text-global">メンバーの編集</div>
        </div>
      </div>
      <div class="participant-box mt-20 d-flex flex-column">
        <span class="text-black1 font-weight-600 fz-16">
          ミーティングに招待する
        </span>
        <span class="text-grey1 fz-14 mt-10">
          リンクを共有することで、ミーティングに招待できます。
        </span>
        <div class="d-flex mt-10 align-items-center">
          <div class="invite-link-box" :style="{ opacity: isCopied ? 0.5 : 1 }">
            {{ linkMeeting }}
          </div>
          <div
            @click="onCopyMeetingLink"
            class="h-35-px status-primary-outline text-global bg-color-white radius-10-px cur-p w-140-px"
          >
            リンクコピー
          </div>
        </div>
      </div>
      <div class="d-flex w-100 mt-20 padding-space justify-content-around">
        <div
          class="h-35-px status-primary-outline text-global bg-color-white radius-10-px cur-p"
          @click="handleClose"
        >
          キャンセル
        </div>
        <div
          class="h-35-px btn-group btn btn-primary radius-10-px"
          @click="handleSave"
        >
          保存する
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import BaseTable from "../../../components/BaseTable.vue";
import Avatar from "../../../components/Avatar.vue";
import { USER_IMAGE, PENCIL } from "../../../constants/imageConst";
import { RENDER_TYPE } from "../../../constants/table";
import { PARTICIPANT_ROLE } from "../../../constants/index";

export default {
  components: { BaseTable, Avatar },
  props: {
    participantsProps: { type: Array },
    linkMeetingProps: { type: String },
    isCopiedProps: { type: Boolean },
    meetingDetailProps: { type: Object },
    isShowModal: { type: Boolean, default: false },
  },
  created() {
    this.USER_IMAGE = USER_IMAGE;
    this.PENCIL = PENCIL;
    this.PARTICIPANT_ROLE = PARTICIPANT_ROLE;
    this.linkMeeting = this.linkMeetingProps;
    this.isCopied = this.isCopiedProps;
    this.meetingDetail = this.meetingDetailProps;
    this.participants = JSON.parse(JSON.stringify(this.participantsProps));
    this.sortParticipants();
  },
  data() {
    return {
      isCheckAll: false,
      isChangeAllRole: null,
      checkedItems: [],
      linkMeeting: "",
      isCopied: false,
      meetingDetail: {},

      columns: [
        {
          columnClass: "pl-10",
          slotName: "checked",
          renderType: RENDER_TYPE.slot,
        },
        {
          columnClass: "text-grey1",
          key: "companyName",
          render(record) {
            return record?.company;
          },
        },

        {
          columnClass: "text-center",
          renderType: RENDER_TYPE.slot,
          slotName: "role",
        },
      ],
      participants: [],
      content: "",
    };
  },
  watch: {
    isCopiedProps(newVal) {
      this.isCopied = newVal;
    },
    meetingDetailProps(newVal) {
      this.meetingDetail = newVal;
    },
    participantsProps(newVal) {
      this.participants = JSON.parse(JSON.stringify(newVal));
    },
    content(newVal) {
      this.participants = this.participantsProps.filter((item) =>
        item.name.toLowerCase().includes(newVal.toLowerCase())
      );
    },
    isShowModal(newVal) {
      if (newVal == false) {
        this.isCheckAll = false;
        this.isChangeAllRole = null;
        this.checkedItems = [];
        this.participants = JSON.parse(JSON.stringify(this.participantsProps));
        this.content = "";
      }
    },
  },
  methods: {
    /**
     * On copy link
     */
    onCopyMeetingLink() {
      this.$emit("copyLink");
    },

    /**
     * Change all role
     */
    handleChangeAllRole() {
      if (this.isChangeAllRole !== null) {
        this.participants.forEach((participant) => {
          this.checkedItems.find((id) => {
            if (id === participant.id) {
              participant.role = this.isChangeAllRole;
            }
          });
        });
      }
    },

    /**
     * Return true if that box is checked
     */
    isCheckedBox(record) {
      return !!this.checkedItems.find((id) => id === record.id);
    },

    /**
     * Handle check all
     */
    handleCheckAllBox(checked) {
      this.checkedItems = [];
      if (checked) {
        this.participants.forEach((participant) => {
          if (participant.id) {
            this.checkedItems.push(participant.id);
          }
        });
        this.isCheckAll = true;
      } else {
        this.isCheckAll = false;
      }
    },

    /**
     * Handle check one
     */
    handleCheckOneBox(checked, record) {
      if (checked) {
        this.checkedItems.push(record.id);
      } else {
        this.checkedItems = this.checkedItems.filter((id) => id !== record.id);
      }
      this.isCheckAll =
        this.checkedItems.length ===
        this.participants.filter((item) => !this.checkedItems.includes(item.id))
          .length
          ? true
          : false;
    },

    /**
     * Handle save update participant
     */
    handleSave() {
      const dataUpdate = this.participants
        .filter(
          (participant) =>
            participant?.id !== participant?.currentParticipantID &&
            participant.userId
        )
        .map((participant) => ({
          participant_id: participant?.id,
          role: participant?.role,
        }));
      this.$emit("update", { dataUpdate: dataUpdate, dataDelete: [] });
    },

    /**
     * Handle close participant modal
     */
    handleClose() {
      this.$emit("close");
    },
    /**
     * Sort current participant to top index
     */
    sortParticipants() {
      // this.participants.sort((a, b) => {
      //   if (a.id === this.meetingDetail.current_participant_id) {
      //     return -1;
      //   } else if (b.id === this.meetingDetail.current_participant_id) {
      //     return 1;
      //   }
      //   return 0;
      // });
    },

    /**
     * Handle go to edit modal
     */
    handleGoToEdit() {
      this.isCheckAll = false;
      this.isChangeAllRole = null;
      this.checkedItems = [];
      this.content = "";
      this.$emit("goToEdit");
    },
  },
};
</script>
<style lang="scss" scoped>
@import "./Home.scss";

.select-list {
  border: 1px solid $primary-color !important;
}
.participant-box {
  min-width: 550px;
  border: 2px solid $white3 !important;
  padding: 15px;
  border-radius: 12px;
}

.padding-line {
  padding: 0 10px;
}

.padding-space {
  padding: 0 30%;
}
</style>
