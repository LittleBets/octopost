<template>
  <div v-if="choices.length" class="flex flex-col gap-8 md:h-full md:overflow-y-hidden">
    <div class="mt-10 space-y-6 md:mt-0 md:h-full md:overflow-y-auto">
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

let choices = $ref<CompositionResultChoice[]>(props.result.choices)

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
