<template>
  <button
    class="inline-flex items-center space-x-1 text-sky-800 hover:text-gray-800"
    :class="{ 'border-b border-dashed border-sky-600 hover:border-gray-600': underline }"
    type="button"
  >
    <icon v-if="icon" :icon="icon" class="h-3 w-3" />
    <span
      ><slot> {{ label }} </slot></span
    >
  </button>
</template>

<script setup lang="ts">
defineProps({
  icon: { type: String, default: undefined },
  label: { type: String, default: undefined },
  underline: { type: Boolean, default: false },
})
</script>
