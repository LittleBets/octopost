<template>
  <a :href="route('compositions.show-one', composition.id)" class="block hover:bg-gray-50">
    <div class="px-4 py-4 sm:px-6">
      <div class="flex items-center justify-between">
        <p class="truncate font-medium text-gray-800">{{ composition.label }}</p>
        <div class="ml-2 flex flex-shrink-0">
          <p
            class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800"
          >
            {{ typeLabel }}
          </p>
        </div>
      </div>
      <div class="mt-2 sm:flex sm:justify-between">
        <div class="sm:flex">
          <p class="flex items-center text-sm text-gray-500">
            <icon
              icon="carbon:user-avatar-filled"
              class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
            />
            {{ composition.user.name }}
          </p>
          <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
            <icon icon="carbon:version" class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" />
            {{ composition.versions + 1 }} Versions
            <!--            +1 for the root composition itself-->
          </p>
        </div>
        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
          <icon icon="carbon:calendar" class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" />
          <p>
            <time datetime="2020-01-07">{{ composition.created_at_short }}</time>
          </p>
        </div>
      </div>
    </div>
  </a>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import { Icon } from '@iconify/vue'
import { $computed } from 'vue/macros'
import { templates } from '@/Pages/Compose/Templates/templates'

const props = defineProps({
  composition: {
    type: Object as PropType<Composition>,
    required: true,
  },
})

const typeLabel = $computed(() => {
  return templates.find((template) => template.id === props.composition.template)?.title ?? ''
})
</script>
