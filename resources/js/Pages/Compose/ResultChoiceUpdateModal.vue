<template>
  <DialogModal :show="openDialog">
    <template #title>
      <p>Update Result</p>
    </template>
    <template #content>
      <Textarea ref="resultTextareaRef" v-model="form.text" :rows="20" />
    </template>
    <template #footer>
      <SecondaryButton @click="closeDialog"> Cancel</SecondaryButton>
      <Button class="ml-3" :disabled="!canUpdate" @click="save"> Save</Button>
    </template>
  </DialogModal>
</template>

<script lang="ts" setup>
import Button from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DialogModal from '@/Components/DialogModal.vue'
import { $computed } from 'vue/macros'
import { watchEffect, PropType } from 'vue'
import Textarea from '@/Components/Textarea.vue'
import { useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
  show: { type: Boolean, required: true },
  modelValue: { type: Object as PropType<CompositionResultChoice>, required: true },
})
const emit = defineEmits<Emits>()

const form = useForm({
  text: props.modelValue.text,
})

const canUpdate = $computed(() => form.text.trim().length)

async function save() {
  try {
    form.patch(route('compositions.results.choices.update', props.modelValue.id), {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        emit('update:modelValue', {
          ...props.modelValue,
          text: form.text,
        })
        closeDialog()
      },
    })
  } catch (error) {
    console.error(error)
  }
}

const resultTextareaRef = $ref<HTMLElement>()

watchEffect(() => {
  if (props.show) {
    resultTextareaRef?.focus()
  }
})

let openDialog = $computed({
  get() {
    return props.show
  },
  set() {
    emit('close')
  },
})

function closeDialog() {
  openDialog = false
}

interface Emits {
  (e: 'close'): void
  (e: 'update:modelValue', choice: CompositionResultChoice): void
}
</script>
