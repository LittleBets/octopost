<template>
  <RadioGroup v-model='selected'>
    <div
      class='group relative rounded border border-gray-300 px-4 pt-1.5 pb-0 focus-within:z-10 focus-within:border-gray-600 focus-within:ring-1 focus-within:ring-gray-600'
    >
      <slot v-if='label' name='label'>
        <RadioGroupLabel
          class='block text-sm font-medium text-gray-400 group-focus-within:text-gray-700'
        >
          {{ label }}
        </RadioGroupLabel>
      </slot>
      <div class='mt-2 -space-y-px divide-y divide-gray-100 rounded bg-transparent'>
        <RadioGroupOption
          v-for='(option, settingIdx) in options'
          :key='option.name'
          v-slot='{ checked, active }'
          :value='option'
          as='template'
        >
          <div
            :class="[
              checked ? 'z-10 bg-gray-100' : '',
              settingIdx === options.length - 1 ? 'rounded-bl rounded-br' : '',
              'relative -mx-4 flex cursor-pointer p-4 focus:outline-none',
            ]"
          >
            <span
              :class="[
                checked ? 'border-transparent bg-gray-600' : 'border-gray-300 bg-white',
                active ? 'ring-2 ring-gray-500 ring-offset-2' : '',
                'mt-0.5 flex h-4 w-4 cursor-pointer items-center justify-center rounded-full border',
              ]"
              aria-hidden='true'
            >
              <span class='h-1.5 w-1.5 rounded-full bg-white' />
            </span>
            <div class='ml-3 flex flex-col'>
              <RadioGroupLabel
                :class="[checked ? 'text-gray-900' : 'text-gray-900', 'block text-sm font-medium']"
                as='span'
              >
                {{ option.name }}
              </RadioGroupLabel>
              <RadioGroupDescription
                :class="[checked ? 'text-gray-700' : 'text-gray-500', 'block text-sm']"
                as='span'
              >
                {{ option.description }}
              </RadioGroupDescription>
            </div>
          </div>
        </RadioGroupOption>
      </div>
    </div>
  </RadioGroup>
</template>

<script lang='ts' setup>
import {
  RadioGroup,
  RadioGroupDescription,
  RadioGroupLabel,
  RadioGroupOption,
} from '@headlessui/vue'
import { PropType, ref, watchEffect } from 'vue'

type Option = {
  key: string
  description: string
  name: string
}

const props = defineProps({
  modelValue: { type: String, required: true },
  label: { type: String, default: undefined },
  options: { type: Array as PropType<Option[]>, required: true },
})
const emit = defineEmits(['update:modelValue'])

const selected = ref()
watchEffect(() => {
  selected.value = props.options.find((s) => s.key === props.modelValue)
})

watchEffect(() => {
  const option = selected.value
  emit('update:modelValue', option?.key)
})
</script>
