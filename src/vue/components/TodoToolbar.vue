<template>
  <div class="toolbar">
    <input
      :value="search"
      type="search"
      placeholder="Search tasks..."
      class="search-input"
      @input="$emit('update:search', $event.target.value)"
    />

    <div class="filters">
      <button
        v-for="item in filters"
        :key="item.value"
        :class="{ selected: filter === item.value }"
        @click="$emit('update:filter', item.value)"
      >
        {{ item.label }}
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  search: {
    type: String,
    default: "",
  },
  filter: {
    type: String,
    default: "all",
  },
});

defineEmits(["update:search", "update:filter"]);

const filters = [
  { value: "all", label: "All" },
  { value: "active", label: "Active" },
  { value: "completed", label: "Completed" },
];
</script>