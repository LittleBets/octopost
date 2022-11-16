<template>
  <div>
    About {{ composition.payload.composition_length }} word long
    {{ composition.payload.response_type }} freeform prompt.
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: { type: Object as PropType<Composition>, required: true },
})
</script>
