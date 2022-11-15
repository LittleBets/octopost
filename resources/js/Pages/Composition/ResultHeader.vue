<template>
  <div class="bg-gray-100 p-4 shadow-inner">
    <h3 v-if="!$slots.title && title" class="text-sm text-gray-500">
      {{ title }}
    </h3>
    <slot name="title" />
    <p class="pt-2 text-sm leading-6 text-gray-800 lg:font-medium lg:leading-8">
      <slot />
    </p>
  </div>
</template>

<script setup lang="ts">
defineProps({
  title: { type: String, default: undefined },
})
</script>
