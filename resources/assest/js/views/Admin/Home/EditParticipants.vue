<template lang="">
  <div
    class="list-invitation d-flex flex-column w-100 h-100 text-black1 fz-14 align-items-center mt-5"
  >
    <div class="w-100 d-flex flex-column p-20">
      <div
        class="d-flex justify-content-between align-items-center position-relative"
      >
        <input
          type="text"
          v-model="content"
          class="w-60 h-40-px pl-40 radius-12-px"
          placeholder="メンバー名を検索"
        />
        <div class="position-absolute fz-16 text-grey1 left-16-px">
          <i class="fa fa-search" />
        </div>
        <div class="fz-14 text-grey1 min-w-fit-content">絞り込み:</div>
        <div
          class="d-flex align-items-center fz-14 text-grey1 h-40-px min-w-fit-content border-1-px radius-12-px pl-10 pr-10"
        >
          すべてのメンバー({{ participantCounter }})
        </div>
      </div>
      <div class="mt-20 participant-box">
        <div class="mt-20">
          <BaseTable
            :columns="columns"
            :data="participants"
            emptyText="表示情報はありません。"
            :isHaveHeader="false"
          >
            <template #checked="{ record }">
              <div class="d-flex align-items-center">
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
              <template
                v-if="
                  record?.userId &&
                  record.id !== meetingDetail.current_participant_id
                "
              >
                <select
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
            </template>
            <template #deleteAction="{ record }">
              <i
                v-if="record.id !== meetingDetail.current_participant_id"
                class="fas fa-times text-grey1 fz-14 pl-15 d-flex align-items-center mb-5 cur-p"
                @click="handleDelete(record)"
              />
            </template>
          </BaseTable>
        </div>
        <div
          v-for="(item, index) in inputArrayParticipant"
          :key="index"
          class="d-flex align-items-center mt-10"
        >
          <AutoComplete
            @updateValue="(value) => handleUpdateValueParticipant(index, value)"
            :modelValue="item.checkEmail"
            :suggestList="suggestListData"
            class="w-100"
          />
          <i
            @click="handleRemove(index)"
            class="fas fa-times text-grey1 fz-14 pl-15 d-flex align-items-center mb-5 cur-p"
          />
        </div>
        <div
          class="mt-10 padding-line d-flex cur-p"
          @click="handleAddParticipant"
        >
          <div class="home__right-icon text-white mr-10">+</div>
          <div class="text-global">メンバー追加</div>
        </div>
        <div v-if="isErrorEmail" class="error">
          {{ ERROR_MESSAGE.emailEmptyAndError }}
        </div>
        <div v-if="isDuplicateEmail" class="error">
          {{ ERROR_MESSAGE.emailDuplicates }}
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
import { PARTICIPANT_ROLE, ERROR_MESSAGE } from "../../../constants/index";
import { deleteEmail } from "../../../apis/meeting/meeting";
import { validEmail, isDuplicateItems } from "../../../utils/helper";
import AutoComplete from "../../../components/AutoComplete.vue";
export default {
  components: { BaseTable, Avatar, AutoComplete },
  props: {
    participantsProps: { type: Array },
    linkMeetingProps: { type: String },
    isCopiedProps: { type: Boolean },
    meetingDetailProps: { type: Object },
    isShowModal: { type: Boolean, default: false },
    suggestList: { type: Array, default: () => [] },
  },
  created() {
    this.USER_IMAGE = USER_IMAGE;
    this.PENCIL = PENCIL;
    this.PARTICIPANT_ROLE = PARTICIPANT_ROLE;
    this.ERROR_MESSAGE = ERROR_MESSAGE;
    this.linkMeeting = this.linkMeetingProps;
    this.isCopied = this.isCopiedProps;
    this.meetingDetail = this.meetingDetailProps;
    this.participants = JSON.parse(JSON.stringify(this.participantsProps));
    this.sortParticipants();
    this.suggestListData = this.suggestList;
  },
  data() {
    return {
      linkMeeting: "",
      meetingDetail: {},
      inputArrayParticipant: [],
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
        {
          columnClass: "pl-10",
          slotName: "deleteAction",
          renderType: RENDER_TYPE.slot,
        },
      ],
      participants: [],
      content: "",
      deletedRecord: [],
      isErrorEmail: false,
      isDuplicateEmail: false,
      suggestListData: null,
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
      if (this.deletedRecord.length !== 0) {
        this.participants = this.participants.filter(
          (item) => !this.deletedRecord.includes(item.id)
        );
      }
    },
    suggestList(newVal) {
      this.suggestListData = newVal;
    },
    content(newVal) {
      this.participants = this.participantsProps.filter(
        (item) =>
          item.name.toLowerCase().includes(newVal.toLowerCase()) &&
          !this.deletedRecord.includes(item.id)
      );
    },
    isShowModal(newVal) {
      if (newVal == false) {
        this.participants = JSON.parse(JSON.stringify(this.participantsProps));
        this.content = "";
        this.deletedRecord = [];
      }
    },
  },
  computed: {
    participantCounter() {
      return this.participants.length;
    },
  },
  methods: {
    /**
     * Handle save update participant
     */
    handleSave() {
      const dataUpdate = this.participants
        .filter(
          (participant) =>
            participant?.id !== participant?.currentParticipantID &&
            participant.user_id
        )
        .map((participant) => ({
          participant_id: participant?.id,
          role: participant?.role,
        }));
      const dataUpdateAddParticipant = [];
      this.isErrorEmail = false;
      this.isDuplicateEmail = false;

      if (this.inputArrayParticipant.length > 0) {
        const newParticipantArray = [
          ...this.participants,
          ...this.inputArrayParticipant,
        ];

        const shouldCheck = this.inputArrayParticipant.some(
          (participant) => !!participant.checkEmail
        );

        this.isErrorEmail = !this.inputArrayParticipant.every((participant) => {
          return validEmail(participant.checkEmail);
        });

        if (shouldCheck) {
          this.isDuplicateEmail = isDuplicateItems(
            newParticipantArray.map((participant) => participant.checkEmail)
          );
        } else {
          this.isDuplicateEmail = false;
        }

        if (!this.isErrorEmail && !this.isDuplicateEmail) {
          this.$emit("update", {
            dataUpdate: dataUpdate,
            dataDelete: this.deletedRecord,
            dataNewParticipant: this.inputArrayParticipant,
          });
          this.inputArrayParticipant = [];
        }
      } else {
        this.$emit("update", {
          dataUpdate: dataUpdate,
          dataDelete: this.deletedRecord,
        });
      }
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
     * Handle go to add
     */
    handleAddParticipant() {
      // this.deletedRecord = [];
      // this.$emit("goToAdd");
      const newParticipant = {
        checkEmail: "",
      };
      this.inputArrayParticipant.push(newParticipant);
    },

    handleRemove(indexNumber) {
      this.inputArrayParticipant = this.inputArrayParticipant.filter(
        (item, index) => indexNumber !== index
      );
      if (this.inputArrayParticipant.length === 0) {
        this.isErrorEmail = false;
        this.isDuplicateEmail = false;
      }
    },

    /**
     * subtract input
     */
    handleDelete(record) {
      this.deletedRecord.push(record.id);
      this.participants = this.participants.filter(
        (item) => !this.deletedRecord.includes(item.id)
      );
    },

    handleUpdateValueParticipant(index, value) {
      this.inputArrayParticipant[index].checkEmail = value;
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
