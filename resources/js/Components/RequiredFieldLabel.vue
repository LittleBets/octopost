<template>
  <div class='flex justify-between'>
    <label v-if='label' :for='label' class='block text-sm font-medium'>{{ label }}</label>
    <span v-if='required' v-tippy='`${label} required`'>
      <icon icon='ph:asterisk-simple' class='h-4 w-4' />
    </span>
  </div>
</template>

<script lang='ts' setup>
defineProps({
  label: { type: String, default: undefined },
  required: { type: Boolean, default: false },
})
</script>

<style scoped></style>
