<template>
    <div v-if="showCheckAction && visibleActions">
        <slot name="checkAction" />
    </div>
    <div v-else />
    <div class="base-table my-2">
        <div class="table-responsive">
            <table :class="`table ${tableClass}`">
                <thead>
                    <tr>
                        <template v-for="(column, index) in columns">
                            <th>{{ column.name }}</th>
                        </template>

                        <!-- Render last column for if table has action -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data" :key="`row${index}`">
                        <td
                            v-for="(column, indexCol) in columns"
                            :key="`row${index}-col${indexCol}`"
                        >
                            <template
                                v-if="column.renderType === RENDER_TYPE.slot"
                            >
                                <slot
                                    :name="column.slotName"
                                    :record="rowData"
                                    :column="column"
                                />
                            </template>
                            <div
                                v-else-if="column.render"
                                v-html="column.render(rowData, column)"
                            />
                            <div v-else>
                                {{ getCellData(rowData, column.key) }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <template v-if="data.length === 0">
                <slot v-if="hasEmptySlot()" name="empty" />
                <div v-else class="table-empty-text text-center pt-1 pb-3">
                    {{ emptyText }}
                </div>
            </template>
        </div>
    </div>
</template>
<script>
import { RENDER_TYPE, DOWN, UP, ACTION_TYPE } from "./../constants/table";
import {
    DETAIL_ICON,
    DELETE_GRAY_ICON,
    EDIT_GRAY_ICON,
    RETURN_PRODUCT,
    CANCEL_PRODUCT,
} from "../constants/imageConst";
export default {
    name: "BaseTable",
    props: {
        data: { type: Array, default: () => [] },
        columns: { type: Array, default: () => [] },
        onClickRow: { type: Function, default: () => {} },
        tableClass: { type: String, default: "" },
        total: { type: Number, default: 0 },
        fromIndex: { type: Number, default: 0 },
        toIndex: { type: Number, default: 0 },
        hasPagination: { type: Boolean, default: false },
        actions: { type: Array, default: () => [] },
        deletedField: { type: String, default: "" },
        emptyText: { type: String, default: "表示情報はありません。" },
        allowRestore: { type: Boolean, default: false },
        visibleActions: { type: Boolean, default: false },
        showCheckAction: { type: Boolean, default: false },
    },
    emits: [...Object.values(ACTION_TYPE)],
    created() {
        this.RENDER_TYPE = RENDER_TYPE;
        this.ACTION_TYPE = ACTION_TYPE;
        this.UP = UP;
        this.DOWN = DOWN;
        this.DETAIL_ICON = DETAIL_ICON;
        this.DELETE_GRAY_ICON = DELETE_GRAY_ICON;
        this.EDIT_GRAY_ICON = EDIT_GRAY_ICON;
        this.RETURN_PRODUCT = RETURN_PRODUCT;
        this.CANCEL_PRODUCT = CANCEL_PRODUCT;
    },
    data() {
        return {
            sort: this.initSort(),
        };
    },
    computed: {
        hasAction() {
            return this.actions.length > 0;
        },
    },
    methods: {
        initSort() {
            let sort = {};
            this.columns.forEach((column) => {
                if (column.sortColumn) {
                    sort[column.sortColumn] = {
                        currentDirection: null,
                        defaultDirection: DOWN,
                    };
                }
            });
            return sort;
        },
        changeDirection(fieldName) {
            this.$emit("sort", param);
        },
        hasEmptySlot() {
            return !!this.$slots?.empty;
        },
        clickRow(record) {
            this.onClickRow(record);
        },
        getCellData(rowData, key) {},
        getActionClass(actionType) {
            switch (actionType) {
                case ACTION_TYPE.delete:
                    return DELETE_GRAY_ICON;
                case ACTION_TYPE.detail:
                    return DETAIL_ICON;
                case ACTION_TYPE.edit:
                    return EDIT_GRAY_ICON;
                case ACTION_TYPE.copy:
                    return "fas fa-copy";
                case ACTION_TYPE.restore:
                    return "fas fa-redo";
                case ACTION_TYPE.return:
                    return RETURN_PRODUCT;
                case ACTION_TYPE.cancel:
                    return CANCEL_PRODUCT;
                default:
                    return "";
            }
        },
        getActionText(actionType) {
            switch (actionType) {
                case ACTION_TYPE.delete:
                    return "削除";
                case ACTION_TYPE.detail:
                    return "詳細";
                case ACTION_TYPE.edit:
                    return "変更";
                case ACTION_TYPE.copy:
                    return "変更";
                case ACTION_TYPE.restore:
                    return "復元";
                case ACTION_TYPE.return:
                    return "返品";
                case ACTION_TYPE.cancel:
                    return "廃棄";
                default:
                    return "";
            }
        },
        handleAction(type, rowData) {
            this.$emit(type, rowData);
        },
    },
};
</script>
