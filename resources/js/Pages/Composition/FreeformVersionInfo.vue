<template>
  <div>
    A {{ composition.payload.composition_length }} tokens long
    {{ composition.payload.response_type }} freeform prompt.
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: { type: Object as PropType<Composition>, required: true },
})
</script>
