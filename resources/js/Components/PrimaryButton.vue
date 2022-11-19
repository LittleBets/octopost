<template>
  <button
    v-bind="$attrs"
    :type="type"
    class="inline-flex items-center rounded border border-transparent bg-zinc-600 py-3 px-3.5 text-sm font-semibold uppercase tracking-widest text-white transition hover:bg-zinc-800 focus:border-zinc-900 focus:outline-none focus:ring focus:ring-zinc-300 active:bg-zinc-900 disabled:opacity-25"
  >
    <slot />
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'submit',
  },
})
</script>
