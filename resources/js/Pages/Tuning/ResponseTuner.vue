<template>
  <TunerShell
    :can-submit="canSubmit"
    :template-type="CompositionTemplateType.Response"
    @submit="submit"
  >
    <slot name="header" />
    <ResponseTypeSelector v-model="payloadForm.response_type" />
    <div class="flex w-full justify-end">
      <LinkButton @click="prependNewExample">
        <div class="inline-flex items-center gap-1 text-sm">
          <icon icon="ph:plus" class="h-4 w-auto" />
          <span> Insert New Example </span>
        </div>
      </LinkButton>
    </div>
    <div class="space-y-2">
      <ResponseTunerExampleRow
        v-for="(example, idx) in tuningExamples"
        :id="example.id"
        :key="example.id"
        :label="`Example ${tuningExamples.length - idx}`"
        :expanded="idx === 0"
        @update="exampleRowUpdateHandler"
        @remove="exampleRowRemoveHandler"
      />
    </div>
    <template #actions>
      <LinkButton @click="prependNewExample">
        <div class="inline-flex items-center gap-1 text-sm">
          <icon icon="ph:plus" class="h-4 w-auto" />
          <span> Insert New Example </span>
        </div>
      </LinkButton>
    </template>

    <!--    <template #result>-->
    <!--    </template>-->
    <!--    <template v-else #emptyResult>-->
    <!--      <EmptyResult />-->
    <!--    </template>-->
  </TunerShell>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import ResponseTypeSelector from '@/Pages/Compose/ResponseTypeSelector.vue'
import { CompositionTemplateType } from '@/enums'
import TunerShell from '@/Pages/Tuning/TunerShell.vue'
import ResponseTunerExampleRow from '@/Pages/Tuning/ResponseTunerExampleRow.vue'
import LinkButton from '@/Components/LinkButton.vue'
import { responseTypes } from '@/Pages/Compose/templates'

const { props: pageProps } = usePage<{ model?: string }>()

const template = 'response'

const model = $computed(() => pageProps.value.model)

let tuningExamples = $ref<{ id: string; message: string; response: string; isValid: boolean }[]>([])

const payloadForm = useForm<Fields>({
  response_type: responseTypes[0].id,
})

const isValid = $computed(() => {
  return tuningExamples.every((example) => example.isValid)
})

const canSubmit = $computed(() => {
  return !payloadForm.processing && isValid
})

function exampleRowUpdateHandler(row: {
  id: string
  payload: { message: string; response: string }
  isValid: boolean
}) {
  const example = tuningExamples.find((e) => e.id === row.id)
  if (example) {
    example.message = row.payload.message
    example.response = row.payload.response
    example.isValid = row.isValid
  }
}

function exampleRowRemoveHandler(id: string) {
  tuningExamples = tuningExamples.filter((e) => e.id !== id)
  if (tuningExamples.length === 0) {
    prependNewExample()
  }
}

async function submit() {
  payloadForm.transform((data) => {
    return {
      template,
      model,
      ...data,
      examples: tuningExamples.map((e) => ({
        message: e.message,
        response: e.response,
      })),
    }
  })
  await payloadForm.post(route('tuning.store'), {
    preserveScroll: true,
    onSuccess: () => {
      payloadForm.reset()
      tuningExamples = []
      prependNewExample()
    },
    onError: (e) => {
      console.log(e)
    },
  })
}

function prependNewExample() {
  tuningExamples.unshift({
    id: Date.now().toString(),
    message: '',
    response: '',
    isValid: false,
  })
}

prependNewExample()

interface Fields {
  response_type: string
}
</script>
