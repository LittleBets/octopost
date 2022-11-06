<template>
  <modal :closeable="closeable" :max-width="maxWidth" :show="show" @close="close">
    <div class="px-6 py-4">
      <div class="text-lg">
        <slot name="title" />
      </div>

      <div class="mt-4">
        <slot name="content" />
      </div>
    </div>

    <div class="flex flex-row justify-end bg-gray-100 px-6 py-4 text-right">
      <slot name="footer" />
    </div>
  </modal>
</template>

<script setup>
import Modal from './Modal.vue'

const emit = defineEmits(['close'])
const props = defineProps({
  show: { default: false },
  maxWidth: { default: '2xl' },
  closeable: { default: true },
})

function close() {
  emit('close')
}
</script>
