<template>
  <div class="border-b bg-gray-50 p-4">
    <h3 v-if="!$slots.title && title" class="text-sm font-bold text-gray-600">
      {{ title }}
    </h3>
    <slot name="title" />
    <div class="pt-2 text-sm leading-6 text-gray-800 lg:font-medium lg:leading-8">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  title: { type: String, default: undefined },
})
</script>
