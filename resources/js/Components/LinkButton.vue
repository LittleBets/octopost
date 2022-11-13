<template>
  <Link v-if="as === 'inertia_link'" :class="classes">
    <icon v-if="icon" :icon="icon" class="h-3 w-3" />
    <span
      ><slot> {{ label }} </slot></span
    >
  </Link>

  <a v-else-if="as === 'anchor'" :class="classes">
    <icon v-if="icon" :icon="icon" class="h-3 w-3" />
    <span
      ><slot> {{ label }} </slot></span
    >
  </a>
  <button v-else :class="classes" type="button">
    <icon v-if="icon" :icon="icon" class="h-3 w-3" />
    <span
      ><slot> {{ label }} </slot></span
    >
  </button>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/inertia-vue3'
import { PropType } from 'vue'
import { $computed } from 'vue/macros'

const props = defineProps({
  icon: { type: String, default: undefined },
  label: { type: String, default: undefined },
  underline: { type: Boolean, default: false },
  as: { type: String as PropType<'anchor' | 'button' | 'inertia_link'>, default: 'anchor' },
})

const classes = $computed(() => {
  const baseClasses = 'inline-flex items-center space-x-1 text-sky-800 hover:text-gray-800'
  if (props.underline) {
    return `${baseClasses} border-b border-dashed border-sky-600 hover:border-gray-600`
  }
  return baseClasses
})
</script>
