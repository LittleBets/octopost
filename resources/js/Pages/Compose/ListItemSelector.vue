<template>
  <Listbox
    v-slot="{ open }"
    v-model="selected"
    as="div"
    class="relative rounded border border-gray-300 px-4 py-1.5 focus-within:z-10 focus-within:border-gray-600 focus-within:ring-1 focus-within:ring-gray-600"
    :disabled="isDisabled"
  >
    <ListboxLabel
      :class="{ 'text-gray-700': open, 'text-gray-500': !open }"
      class="block text-sm font-medium"
      >{{ title }}
    </ListboxLabel>
    <div class="relative">
      <div class="flex items-center">
        <Checkbox
          v-if="withCheckbox"
          v-model:checked="checkState"
          class="mr-1.5"
          :disabled="disabled"
        />
        <ListboxButton
          class="relative w-full cursor-default bg-transparent py-2 pr-10 text-left focus-within:ring-0 focus:outline-none focus:ring-0"
        >
          <span class="truncate" :class="{ 'text-gray-500': isDisabled }">{{
            selected.title
          }}</span>
          <span class="pointer-events-none absolute inset-y-0 right-0 ml-3">
            <SelectorIcon aria-hidden="true" class="h-5 w-5 text-gray-500" />
          </span>
        </ListboxButton>
      </div>
      <transition
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions
          class="absolute mt-1 max-h-56 w-full overflow-auto rounded bg-gray-50 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
        >
          <ListboxOption
            v-for="option in options"
            :key="option.id"
            v-slot="{ active, selected }"
            :value="option"
            as="template"
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
                  :class="[
                    active ? 'text-white' : 'text-gray-600',
                    'absolute inset-y-0 right-0 flex items-center pr-4',
                  ]"
                >
                  <icon aria-hidden="true" class="h-5 w-5" icon="ph:check" />
                </span>
              </div>
              <slot name="description" :selected="selected" :active="active" :item="option">
                <span :class="[active ? 'text-white' : 'text-gray-500', 'block text-sm']">
                  {{ option.description }}
                </span>
              </slot>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>

<script lang="ts" setup>
import SelectorIcon from '@/Components/Icons/SelectorIcon.vue'
import { ref, watchEffect } from 'vue'
import {
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
} from '@headlessui/vue'
import Checkbox from '@/Components/Checkbox.vue'

const {
  title,
  modelValue = null,
  options = [],
  withCheckbox = false,
  checked = true,
  disabled = false,
} = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'update:checked'])

const selected = ref(options[0])
watchEffect(() => {
  selected.value = options.find((s) => s.id === modelValue) ?? options[0]
})
watchEffect(() => {
  const option = selected.value
  emit('update:modelValue', option?.id)
})

const checkState = $computed({
  get: () => checked,
  set: (val) => {
    emit('update:checked', val)
  },
})

const isDisabled = $computed(() => disabled || (withCheckbox && checkState === false))
</script>

<script lang="ts">
interface Props {
  title: string
  modelValue?: string
  options: [{ id: string; title: string }]
  withCheckbox?: boolean
  checked?: boolean
  disabled?: boolean
}
</script>
