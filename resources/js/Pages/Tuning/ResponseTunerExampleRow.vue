<template>
  <Disclosure v-slot="{ open }" default-open>
    <DisclosureButton
      ref="disclosureBtnRef"
      class="flex w-full justify-between rounded bg-gray-100 px-4 py-4 text-left text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus-visible:ring focus-visible:ring-gray-500 focus-visible:ring-opacity-75"
      @click="expandedState = !expandedState"
    >
      <span>{{ label }}</span>
      <icon
        icon="ph:caret-up"
        :class="open ? 'rotate-180 transform' : ''"
        class="h-5 w-5 text-gray-500"
      />
    </DisclosureButton>
    <DisclosurePanel class="space-y-6 px-4 pt-4 pb-2 text-sm text-gray-500">
      <Textarea
        ref="messageRef"
        v-model="payload.message"
        label="Message"
        name="message"
        :rows="10"
        required
        class="bg-white"
      />
      <Textarea
        v-model="payload.response"
        label="Example Response"
        name="message"
        :rows="10"
        class="bg-white"
        required
      />

      <div class="flex w-full justify-end pb-4">
        <LinkButton v-tippy="'Remove this example'" @click="emit('remove', id)">
          <icon icon="ph:trash" class="h-4 w-auto text-gray-500 hover:text-red-500" />
        </LinkButton>
      </div>
    </DisclosurePanel>
  </Disclosure>
</template>

<script setup lang="ts">
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import Textarea from '@/Components/Textarea.vue'
import { nextTick, reactive, ref, watch } from 'vue'
import LinkButton from '@/Components/LinkButton.vue'
import { debounce } from 'ts-debounce'

const props = defineProps({
  label: { type: String, required: true },
  id: { type: String, required: true },
  expanded: { type: Boolean, default: true },
})

const payload = reactive({
  message: '',
  response: '',
})

let expandedState = $ref(props.expanded)

watch(
  () => props.expanded,
  (newValue) => {
    if (expandedState != newValue) {
      togglePanel()
    }
  }
)

const emit = defineEmits<{
  (e: 'remove', id: string): void
  (
    e: 'update',
    value: { id: string; payload: { message: string; response: string }; isValid: boolean }
  ): void
}>()

const isValid = $computed(
  () => !(payload.message.trim().length > 0 != payload.response.trim().length > 0)
)

const debounceEmitUpdate = debounce(emitUpdate, 100)
watch(payload, () => {
  debounceEmitUpdate()
})

function emitUpdate() {
  emit('update', { id: props.id, payload, isValid })
}

const messageRef = $ref<HTMLElement>()

function setFocus() {
  nextTick(() => {
    messageRef?.focus()
  })
}

setFocus()

const disclosureBtnRef = ref<{ $el: { click: () => void } } | null>(null)

function togglePanel() {
  disclosureBtnRef?.value?.$el?.click()
  expandedState = !expandedState
}
</script>
