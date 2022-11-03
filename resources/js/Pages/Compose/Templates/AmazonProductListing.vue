<template>
  <TextInput
    v-model='payload.name'
    label='Product Name'
    required
  />
  <Textarea
    v-model='payload.features'
    name='product_features'
    label='Product Features'
    required
  />
  <ToneSelector v-model='payload.tone' />
  <AudienceSelector v-model='payload.audience' />
  <TextInput
    v-model.number='payload.variations' type='number' min='1' max='5'
    label='Number of Variations'
    required
  />
</template>

<script setup lang='ts'>
import TextInput from '@/Components/TextInput.vue'
import ToneSelector from '@/Pages/Compose/ToneSelector.vue'
import Textarea from '@/Components/Textarea.vue'
import { reactive, watch } from 'vue'
import { tones } from '@/Pages/Compose/Templates/templates'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'

interface Emit {
  (event: 'update:modelValue', args: { payload: Payload, isValid: boolean }): void
}

interface Payload {
  name: string
  tone: string
  features: string
  variations: number
  audience: string
}

interface Props {
  modelValue?: { payload: Payload, isValid: boolean }
}

const emit = defineEmits<Emit>()
const { modelValue } = defineProps<Props>()

const payload = reactive<Payload>({
  name: modelValue?.payload?.name ?? '',
  tone: modelValue?.payload?.tone ?? tones[0].id,
  features: modelValue?.payload?.features ?? '',
  variations: modelValue?.payload?.variations ?? 1,
  audience: modelValue?.payload?.audience ?? '',
})

const isValid = $computed<boolean>(() => {
  return payload.name.trim() !== '' && payload.features.trim() !== ''
})

watch(payload, () => {
  emit('update:modelValue', { payload, isValid: isValid })
}, { deep: true, immediate: true })
</script>
