<template>
  <div class="">
    <input
      v-model="inputValue"
      type="text"
      class="pl-20 pr-20 radius-8-px h-40-px form-control fz-14 input-email"
      spellcheck="false"
      placeholder="メールアドレスを入力"
      @input="handleInput"
      :disabled="index == indexCreator"
    />
    <ul
      v-if="showSuggestions && suggestions.length > 0"
      class="autocomplete-suggestions"
    >
      <li
        v-for="suggestion in suggestions"
        :key="suggestion.id"
        @click="selectSuggestion(suggestion.email)"
      >
        {{
          `${suggestion.email} - ${suggestion.last_name}${suggestion.first_name}`
        }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: String,
    suggestList: Array,
    index: { type: Number, default: 0 },
    indexCreator: { type: Number, default: 0 },
  },
  data() {
    return {
      showSuggestions: false,
      suggestions: [],
      inputValue: "",
    };
  },
  created() {
    this.inputValue = this.modelValue;
  },
  watch: {
    modelValue: function (newVal) {
      this.inputValue = newVal;
    },
    suggestList: function (newVal) {
      this.suggestions = newVal;
    },
  },
  methods: {
    /**
     * handle emit new value when iput
     */
    handleInput() {
      if (this.inputValue !== "") {
        this.showSuggestions = true;
      } else this.showSuggestions = false;
      this.handleFilterSugesstionslist();
      this.$emit("updateValue", this.inputValue);
    },
    /**
     * handle emit new value when select option
     */
    selectSuggestion(suggestion) {
      this.inputValue = suggestion;
      this.showSuggestions = false;
      this.$emit("updateValue", this.inputValue);
    },
    /**
     * handle filter suggesstions when input
     */
    handleFilterSugesstionslist() {
      this.suggestions = this.suggestList.filter(
        (suggestion) =>
          suggestion.email
            .toLowerCase()
            .indexOf(this.inputValue.toLowerCase()) > -1
      );
    },
  },
};
</script>

<style scoped>
.autocomplete-suggestions {
  list-style: none;
  background: white;
  border: 1px solid #ccc;
  max-height: 150px;
  overflow-y: auto;
  width: 100%;
  padding: 0;
}

.autocomplete-suggestions li {
  padding: 8px 20px 8px 20px;
  cursor: pointer;
}

.autocomplete-suggestions li:hover {
  background-color: #f0f0f0;
}

.invisible {
  visibility: hidden;
  cursor: not-allowed;
}
</style>
