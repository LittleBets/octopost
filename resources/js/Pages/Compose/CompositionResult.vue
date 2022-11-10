<template>
  <div v-if="choices.length" class="flex h-full flex-col gap-8 overflow-y-hidden">
    <div class="h-full space-y-6 overflow-y-auto">
      <article v-for="choice in choices" :key="choice.id">
        <CompositionResultChoice :choice="choice" @deleted="choiceDeletedHandler" />
      </article>
    </div>
    <slot name="footer" />
  </div>
  <EmptyResult v-else />
</template>

<script setup lang="ts">
import { PropType, watch } from 'vue'
import CompositionResultChoice from '@/Pages/Compose/CompositionResultChoice.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'

const props = defineProps({
  result: { type: Object as PropType<CompositionResult>, required: true },
})

let choices = $ref(props.result.choices)

watch(
  () => props.result,
  (result) => {
    choices = result.choices
  }
)

function choiceDeletedHandler(id: string) {
  choices = choices.filter((choice) => choice.id !== id)
}
</script>
