<template>
  <div class="flex">
    <label v-if="label" :for="label" class="block flex-1 text-sm font-medium"
      ><slot :label="label">{{ label }}</slot></label
    >
    <div class="flex items-center space-x-1 text-gray-700">
      <InfoTooltip v-if="withTooltip" class="px-1">
        <template #content>
          <slot name="tooltip" />
        </template>
      </InfoTooltip>
      <span v-if="required" v-tippy="'Required Field'">
        <icon icon="ph:asterisk" class="h-4 w-auto" />
      </span>
    </div>
  </div>
</template>

<script lang="ts" setup>
import InfoTooltip from '@/Components/InfoTooltip.vue'

defineProps({
  label: { type: String, default: undefined },
  required: { type: Boolean, default: false },
  withTooltip: { type: Boolean, default: false },
})
</script>

<style scoped></style>
