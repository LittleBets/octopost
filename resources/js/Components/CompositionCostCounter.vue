<template>
  <div class="space-x-1 rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold">
    <InfoTooltip>
      <div class="mr-2 inline-flex items-center gap-1">
        <icon icon="wpf:coins" class="h-4 w-4 text-gray-500" />
        <span class="text-gray-700">{{ usage.total_tokens }}</span>
      </div>
      <template #content>
        <div>
          <h3 class="text-lg font-medium leading-6 text-gray-900">Usage Cost</h3>
          <div class="pt-2 font-normal leading-relaxed">
            This is an approximation of the total cost of your composition in terms of credits. The
            actual cost may vary depending on the number of characters in your final output.
          </div>
          <dl
            class="mt-4 grid grid-cols-1 divide-x overflow-hidden rounded bg-white md:grid-cols-3"
          >
            <div class="px-4">
              <dt class="font-normal text-gray-900">Input</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline font-bold text-gray-800">
                  {{ usage.prompt_tokens }}
                </div>
              </dd>
            </div>
            <div class="px-4">
              <dt class="font-normal text-gray-900">Output</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline font-bold text-gray-800">
                  {{ usage.completion_tokens }}
                </div>
              </dd>
            </div>
            <div class="px-4">
              <dt class="font-normal text-gray-900">Total</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline font-bold text-gray-800">
                  {{ usage.total_tokens }}
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
import { reactive, watch } from 'vue'
import { debounce } from 'ts-debounce'
import InfoTooltip from '@/Components/InfoTooltip.vue'

const { template, payload } = defineProps<Props>()

const form = reactive({
  processing: false,
})

const debouncedFetch = debounce(fetchUsage, 500)

watch(payload, () => debouncedFetch(), { deep: true, immediate: true })

const usage = reactive<Usage>({
  prompt_tokens: 0,
  completion_tokens: 0,
  total_tokens: 0,
})

async function fetchUsage() {
  form.processing = true
  try {
    const { data } = await axios.get(route('usage.guess'), {
      params: {
        template: template,
        payload: payload,
      },
    })
    usage.total_tokens = data.total_tokens
    usage.prompt_tokens = data.prompt_tokens
    usage.completion_tokens = data.completion_tokens
  } catch (error) {
    console.log(error)
    // todo: show an error
  } finally {
    form.processing = false
  }
}

interface Props {
  template: string
  payload: Record<string, unknown>
}

interface Usage {
  prompt_tokens: number
  completion_tokens: number
  total_tokens: number
}
</script>
