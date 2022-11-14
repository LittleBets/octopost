<template>
  <DialogModal :show="openDialog">
    <template #title>
      <p>Update Composition Label</p>
    </template>
    <template #content>
      <TextInput ref="labelInputRef" v-model="label" />
    </template>
    <template #footer>
      <SecondaryButton @click="closeDialog"> Cancel</SecondaryButton>
      <Button class="ml-3" :disabled="!canUpdate" @click="update"> Update</Button>
    </template>
  </DialogModal>
</template>

<script lang="ts" setup>
import Button from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DialogModal from '@/Components/DialogModal.vue'
import TextInput from '@/Components/TextInput.vue'
import { $computed } from 'vue/macros'
import { nextTick, watchEffect } from 'vue'

const { show = false, modelValue, compositionId } = defineProps<Props>()
const emit = defineEmits<Emits>()

const label = $ref(modelValue ?? '')

const canUpdate = $computed(() => label !== modelValue && label.trim().length)

const labelInputRef = $ref<HTMLElement>()

watchEffect(() => {
  if (show) {
    labelInputRef?.focus()
  }
})

let openDialog = $computed({
  get() {
    return show
  },
  set() {
    emit('close')
  },
})

function closeDialog() {
  openDialog = false
}

async function update() {
  try {
    const { data } = await axios.patch(route('compositions.update', compositionId), {
      label: label.trim(),
    })
    closeDialog()
    await nextTick()
    emit('update:modelValue', data.label)
  } catch (error) {
    console.error(error)
  }
}

interface Props {
  show: boolean
  modelValue?: string
  compositionId: string
}

interface Emits {
  (e: 'close'): void
  (e: 'update:modelValue', label: string): void
}
</script>
