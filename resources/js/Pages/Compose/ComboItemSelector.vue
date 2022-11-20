<template>
  <Combobox
    v-slot="{ open }"
    v-model="selected"
    as="div"
    class="relative rounded border border-gray-300 px-4 py-1.5 focus-within:z-10 focus-within:border-gray-600 focus-within:ring-1 focus-within:ring-gray-600"
    @click="checkState = true"
  >
    <ComboboxLabel
      :class="{ 'text-gray-700': open, 'text-gray-500': !open }"
      class="block text-sm font-medium"
    >
      <RequiredFieldLabel :label="title" :with-tooltip="tooltip != null">
        <template #tooltip>
          <slot name="tooltip">
            {{ tooltip }}
          </slot>
        </template>
      </RequiredFieldLabel>
    </ComboboxLabel>
    <div class="relative">
      <div class="flex w-full items-center">
        <Checkbox
          v-if="withCheckbox"
          v-model:checked="checkState"
          class="mr-1.5"
          :disabled="disabled"
        />
        <ComboboxInput
          class="w-full flex-1 truncate border-none py-2 pl-0 pr-10 leading-5 focus:ring-0"
          :class="{ 'text-gray-500': isDisabled, 'text-gray-900': !isDisabled }"
          :display-value="(option) => option.title"
          :disabled="isDisabled"
          @change="query = $event.target.value"
        />
        <ComboboxButton
          class="relative cursor-default bg-transparent py-2 text-left focus-within:ring-0 focus:outline-none focus:ring-0"
          @click="checkState = true"
        >
          <span class="pointer-events-none right-0 ml-3 flex">
            <SelectorIcon aria-hidden="true" class="h-5 w-5 text-gray-500" />
          </span>
        </ComboboxButton>
      </div>
      <TransitionRoot
        leave="transition ease-in duration-100"
        leave-from="opacity-100"
        leave-to="opacity-0"
        @after-leave="query = ''"
      >
        <ComboboxOptions
          class="absolute mt-1 max-h-56 w-full overflow-auto rounded bg-gray-50 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
        >
          <ComboboxOption
            v-if="queryOption"
            :value="queryOption"
            class="group relative cursor-default select-none py-3 pl-3 pr-9 text-gray-900"
          >
            Hit enter to add "{{ query }}"
          </ComboboxOption>
          <ComboboxOption
            v-for="option in filteredOptions"
            :key="option.id"
            v-slot="{ selected, active }"
            :value="option"
          >
            <li
              :class="[
                active ? 'bg-gray-600 text-white' : 'text-gray-900',
                'group relative cursor-default select-none py-3 pl-3 pr-9',
              ]"
            >
              <div class="flex">
                <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                  {{ option.title }}
                </span>
                <span
                  v-if="selected"
                  class="absolute inset-y-0 left-0 flex items-center pl-3"
                  :class="{ 'text-white': active, 'text-teal-600': !active }"
                >
                  <icon icon="ph:check" class="h-5 w-5" aria-hidden="true" />
                </span>
              </div>
              <slot name="description" :selected="selected" :active="active" :item="option">
                <span :class="[active ? 'text-white' : 'text-gray-500', 'block text-sm']">
                  {{ option.description }}
                </span>
              </slot>
            </li>
          </ComboboxOption>
        </ComboboxOptions>
      </TransitionRoot>
    </div>
  </Combobox>
</template>

<script setup lang="ts">
import { ref, computed, watchEffect } from 'vue'
import {
  Combobox,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
  TransitionRoot,
  ComboboxLabel,
} from '@headlessui/vue'
import SelectorIcon from '@/Components/Icons/SelectorIcon.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InfoTooltip from '@/Components/InfoTooltip.vue'
import RequiredFieldLabel from '@/Components/RequiredFieldLabel.vue'

const {
  title,
  modelValue = null,
  withCheckbox = false,
  checked = true,
  disabled = false,
  options = [],
  tooltip = undefined,
} = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'update:checked'])

const selected = ref(options.find((o) => o.id === modelValue) || options[0])
watchEffect(() => {
  selected.value = options.find((s) => s.id === modelValue) ?? options[0]
})
watchEffect(() => {
  const option = selected.value
  emit('update:modelValue', option?.id)
})
const query = ref('')
const queryOption = computed(() => {
  return query.value === '' ? null : { id: query.value, title: query.value }
})

const filteredOptions = computed(() =>
  query.value === ''
    ? options
    : options.filter((option) =>
        option?.title
          ?.toLowerCase()
          ?.replace(/\s+/g, '')
          ?.includes(query.value.toLowerCase().replace(/\s+/g, ''))
      )
)

const checkState = $computed({
  get: () => checked,
  set: (val) => {
    emit('update:checked', val)
  },
})
const isDisabled = $computed(() => disabled || (withCheckbox && checkState === false))

interface Props {
  title: string
  modelValue?: string
  options: [{ id: string; title: string }]
  withCheckbox?: boolean
  checked?: boolean
  disabled?: boolean
  tooltip?: string
}
</script>
