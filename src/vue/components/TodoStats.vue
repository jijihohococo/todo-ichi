<template>
  <section class="statistics">
    <div class="stat-card">
      <span class="stat-label">Total Tasks</span>
      <strong>{{ total }}</strong>
    </div>

    <div class="stat-card">
      <span class="stat-label">Active</span>
      <strong>{{ active }}</strong>
    </div>

    <div class="stat-card">
      <span class="stat-label">Completed</span>
      <strong>{{ completed }}</strong>
    </div>
  </section>
</template>

<script setup>
defineProps({
  total: {
    type: Number,
    default: 0,
  },
  active: {
    type: Number,
    default: 0,
  },
  completed: {
    type: Number,
    default: 0,
  },
});
</script>