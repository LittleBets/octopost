<template>
  <div class="space-x-1 rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold">
    <InfoTooltip>
      <div class="mr-2 inline-flex items-center gap-1">
        <icon icon="wpf:coins" class="h-4 w-4 text-gray-500" />
        <span class="text-gray-700">{{ usage.total_credits }}</span>
      </div>
      <template #content>
        <div>
          <h3 class="text-lg font-medium leading-6 text-gray-900">Usage Cost</h3>
          <div class="pt-2 font-normal leading-relaxed">
            This is an approximation of the total cost of your composition in terms of word credits.
            The actual cost may vary depending on the number of characters/words in your final
            output.
          </div>
          <dl
            class="mt-4 grid grid-cols-1 divide-x overflow-hidden rounded bg-white md:grid-cols-3"
          >
            <div class="px-4">
              <dt class="font-normal text-gray-900">Input</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline font-bold text-gray-800">
                  {{ usage.prompt_credits }}
                </div>
              </dd>
            </div>
            <div class="px-4">
              <dt class="font-normal text-gray-900">Output</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline font-bold text-gray-800">
                  {{ usage.completion_credits }}
                </div>
              </dd>
            </div>
            <div class="px-4">
              <dt class="font-normal text-gray-900">Total</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline font-bold text-gray-800">
                  {{ usage.total_credits }}
                </div>
              </dd>
            </div>
          </dl>
        </div>
      </template>
    </InfoTooltip>
  </div>
</template>

<script setup lang="ts">
import { PropType, reactive, watch } from 'vue'
import { debounce } from 'ts-debounce'
import InfoTooltip from '@/Components/InfoTooltip.vue'

const props = defineProps({
  template: { type: String, required: true },
  payload: { type: Object as PropType<Record<string, unknown>>, required: true },
})

const form = reactive({
  processing: false,
})

const debouncedFetch = debounce(fetchUsage, 500)

watch(props.payload, () => debouncedFetch(), { deep: true, immediate: true })

const usage = reactive<Usage>({
  prompt_credits: 0,
  completion_credits: 0,
  total_credits: 0,
})

async function fetchUsage() {
  form.processing = true
  try {
    const { data } = await axios.get(route('usage.guess'), {
      params: {
        template: props.template,
        payload: props.payload,
      },
    })
    usage.total_credits = data.total_credits
    usage.prompt_credits = data.prompt_credits
    usage.completion_credits = data.completion_credits
  } catch (error) {
    console.log(error)
    // todo: show an error
  } finally {
    form.processing = false
  }
}

interface Usage {
  prompt_credits: number
  completion_credits: number
  total_credits: number
}
</script>
