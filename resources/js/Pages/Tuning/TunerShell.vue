<template>
  <div class="h-full w-full overflow-y-hidden py-1">
    <form
      ref="shortcutActiveContainer"
      class="mx-auto max-w-3xl bg-white shadow sm:rounded-lg md:h-full md:overflow-y-hidden"
      :class="{ 'ring-2 ring-orange-200': model === 'fake' }"
      @submit.prevent="emit('submit')"
    >
      <div class="flex h-full flex-1 flex-col space-y-6 overflow-y-hidden bg-white">
        <div class="flex-1 space-y-6 overflow-y-auto py-6 px-4 sm:p-6">
          <slot />
        </div>
        <div class="flex items-center justify-between space-x-4 bg-gray-50 py-4 px-6 text-right">
          <span />
          <div class="inline-flex items-center gap-8">
            <slot name="actions" />
            <PrimaryButton :disabled="!canSubmit">
              <span>Submit for Tuning</span>
            </PrimaryButton>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { usePage } from '@inertiajs/inertia-vue3'
import { CompositionTemplateType } from '@/enums'
import { PropType, ref } from 'vue'
import { useMagicKeys, whenever } from '@vueuse/core'
import { and } from '@vueuse/math'
import { useIsMouseInside } from '@/compositions/useIsMouseInside'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const { props: pageProps } = $(usePage<{ model?: string }>())

defineProps({
  canSubmit: { type: Boolean, required: true },
  templateType: { type: String as PropType<CompositionTemplateType>, required: true },
})
const emit = defineEmits<{
  (e: 'submit'): void
  (e: 'addNew'): void
}>()

const model = $computed(() => pageProps.model)

const shortcutActiveContainer = ref<HTMLElement | null>(null)
const { isInside } = useIsMouseInside(shortcutActiveContainer)
const keys = useMagicKeys()

whenever(and(keys.cmd_shift_enter, isInside), () => {
  emit('addNew')
})
</script>
