<template>
  <div class="mx-auto flex h-full w-full flex-col overflow-y-hidden">
    <CompositionLabel
      class="hidden md:block"
      :value="compositionLabel"
      :composition-id="rootCompositionId"
    />
    <div
      class="mx-auto w-full gap-8 overflow-y-auto py-1 md:flex md:flex-1 md:flex-row md:gap-0 md:overflow-y-hidden"
    >
      <form
        ref="shortcutActiveContainer"
        class="mx-6 flex max-w-2xl flex-col bg-white shadow sm:rounded-lg md:mx-0 md:ml-8 md:h-full md:w-3/5 md:overflow-y-hidden"
        :class="{ 'ring-2 ring-orange-200': model === 'fake' }"
        @submit.prevent="emit('submit')"
      >
        <div class="flex h-full flex-1 flex-col space-y-6 overflow-y-hidden bg-white">
          <div class="flex-1 space-y-6 overflow-y-auto py-6 px-4 sm:p-6">
            <slot />
          </div>
          <div class="flex items-center justify-between space-x-4 bg-gray-50 py-4 px-6 text-right">
            <slot name="action" />
            <CompositionCostCounter
              :disabled="!canSubmit"
              :template="templateType"
              :payload="payload"
            />
            <div class="inline-flex items-center gap-8 text-right">
              <LinkButton
                v-tippy="'Clear form and start a new composition &nbsp;&nbsp;&nbsp;&#8984;&#8679;'"
                class="hidden text-sm md:inline"
                label="Start Over"
                @click="emit('startOver')"
              />
              <LinkButton
                v-tippy="'Clear form and start a new composition'"
                class="inline text-sm md:hidden"
                label="New"
                @click="emit('startOver')"
              />
              <PrimaryButton :disabled="!canSubmit">
                <span>
                  <span>{{ rootCompositionId == null ? 'Compose' : 'Recompose' }}</span
                  ><span class="hidden md:inline">&nbsp; &nbsp; &#8984;&#8617;</span>
                </span>
              </PrimaryButton>
            </div>
          </div>
        </div>
      </form>
      <div class="flex w-full flex-col px-6 md:h-full md:overflow-y-hidden">
        <slot name="result" />
        <slot name="emptyResult" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { usePage } from '@inertiajs/inertia-vue3'
import CompositionLabel from '@/Pages/Compose/CompositionLabel.vue'
import LinkButton from '@/Components/LinkButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import CompositionCostCounter from '@/Components/CompositionCostCounter.vue'
import { CompositionTemplateType } from '@/enums'
import { PropType, ref } from 'vue'
import { useMagicKeys, whenever } from '@vueuse/core'
import { and } from '@vueuse/math'
import { useIsMouseInside } from '@/compositions/useIsMouseInside'

const { props: pageProps } = $(usePage<{ model?: string }>())

const props = defineProps({
  rootCompositionId: { type: String, default: undefined },
  compositionLabel: { type: String, default: undefined },
  canSubmit: { type: Boolean, required: true },
  payload: { type: Object, required: true },
  templateType: { type: String as PropType<CompositionTemplateType>, required: true },
})
const emit = defineEmits<{
  (e: 'submit'): void
  (e: 'startOver'): void
}>()

const model = $computed(() => pageProps.model)

const shortcutActiveContainer = ref<HTMLElement | null>(null)
const { isInside } = useIsMouseInside(shortcutActiveContainer)
const keys = useMagicKeys()

whenever(and(keys.cmd_enter, isInside), () => {
  if (props.canSubmit) {
    emit('submit')
  }
})

whenever(and(keys.cmd_shift_S, isInside), () => {
  emit('startOver')
})
</script>
