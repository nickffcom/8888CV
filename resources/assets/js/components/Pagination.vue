<template>
  <!-- ///// ページネーション START ///// -->
  <div class="base-pagination flex-wrap" v-if="total == 0 ? false : true">
    <span class="mr-10 text-gray fz-16">
      {{ total }} 件中 {{ firstIndex }} ~ {{ lastIndex }} 件
    </span>
    <div class="d-flex align-items-center gap-20">
      <button
        v-if="visibleFirst"
        @click="onClickFirstPage"
        :class="`pagination__prev ${isInFirstPage ? 'is-disabled' : ''}`"
      >
        First
      </button>
      <button
        v-if="!isInFirstPage"
        :class="`pagination__prev ${isInFirstPage ? 'is-disabled' : ''}`"
        @click="onClickPreviousPage"
      >
        <i class="fas fa-chevron-left" />
      </button>
      <template v-for="(page, index) in pages">
        <span
          v-if="page.name === MORE_PAGE"
          class="pagination__omit"
          :key="`omit_${index}`"
        >
          …
        </span>
        <button
          v-else
          :key="page.name"
          @click="onClickPage(page.name)"
          :disabled="page.isDisabled"
          :class="[
            'pagination__num',
            { 'is-current': isPageActive(page.name) },
          ]"
        >
          {{ page.name }}
        </button>
      </template>
      <button
        v-if="!isInLastPage"
        :class="`pagination__next ${isInLastPage ? 'is-disabled' : ''}`"
        @click="onClickNextPage"
      >
        <i class="fas fa-chevron-right" />
      </button>
      <button
        v-if="visibleLast"
        @click="onClickLastPage"
        :disabled="isInLastPage"
        :class="`pagination__next ${isInLastPage ? 'is-disabled' : ''}`"
      >
        Last
      </button>
    </div>
  </div>
</template>

<script>
import { FIRST_PAGE, MORE_PAGE } from "../constants/table";
import { paginationGenerator } from "../utils/pagination";
export default {
  name: "Pagination",
  props: {
    offset: {
      type: Number,
      required: false,
      default: 2,
    },
    totalPages: {
      type: Number,
      required: true,
    },
    currentPage: {
      type: Number,
      required: true,
    },
    visibleFirst: {
      type: Boolean,
      default: false,
    },
    visibleLast: {
      type: Boolean,
      default: false,
    },
    total: { type: Number, default: 0 },
    pageSize: { type: Number, default: 5 },
  },
  created() {
    this.MORE_PAGE = MORE_PAGE;
  },
  computed: {
    firstIndex() {
      return this.pageSize * (this.currentPage - 1) + 1;
    },
    lastIndex() {
      return this.currentPage === this.totalPages
        ? this.total
        : this.pageSize * this.currentPage;
    },
    pages() {
      const range = paginationGenerator(
        this.currentPage,
        this.totalPages,
        this.offset
      );

      const result = [];
      range.forEach((page) => {
        result.push({
          name: page,
          isDisabled: page === this.currentPage,
        });
      });

      return result;
    },
    isInFirstPage() {
      return this.currentPage === FIRST_PAGE;
    },
    isInLastPage() {
      return this.currentPage === this.totalPages;
    },
  },
  methods: {
    onClickFirstPage() {
      this.$emit("pagechanged", FIRST_PAGE);
    },
    onClickPreviousPage() {
      this.$emit("pagechanged", this.currentPage - 1);
    },
    onClickPage(page) {
      this.$emit("pagechanged", page);
    },
    onClickNextPage() {
      this.$emit("pagechanged", this.currentPage + 1);
    },
    onClickLastPage() {
      this.$emit("pagechanged", this.totalPages);
    },
    isPageActive(page) {
      return this.currentPage === page;
    },
  },
};
</script>
