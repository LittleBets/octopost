<template>
  <div class="relative mb-2 pb-4">
    <span class="absolute top-3 left-1 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true" />
    <div class="relative flex items-start space-x-3">
      <div class="relative">
        <span
          class="flex h-2 w-2 items-center justify-center rounded-full border-2 border-gray-300 bg-white font-medium text-white"
        />
      </div>
      <div ref="shortcutActiveContainer" class="group -mt-3 min-w-0 flex-1 p-2">
        <div
          class="mt-1 cursor-pointer rounded border p-2"
          :class="{
            'border-transparent hover:border-gray-200 hover:bg-gray-100': !active,
            'border-sky-200 bg-sky-50': active,
          }"
        >
          <p class="mt-0.5 text-sm text-gray-500">
            {{ composition.created_at_short }}
          </p>
          <div class="mt-2 flex flex-col gap-2 text-sm text-gray-700">
            <Component :is="versionInfoComponent" :composition="composition" />
          </div>
          <div class="flex justify-end opacity-10 group-hover:opacity-50">
            <LinkButton
              v-tippy="'Delete Version &nbsp;&nbsp;&nbsp;D'"
              @click="confirmingDeletion = true"
            >
              <icon icon="ph:trash" class="h-5 w-auto text-gray-500 hover:text-red-500" />
            </LinkButton>
          </div>
        </div>
      </div>
    </div>
    <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
      <template #title> Delete Version </template>
      <template #content>
        <div class="font-semibold">
          Are you sure you want to delete this version and all its results?
        </div>
        <div class="my-4 rounded bg-gray-100 py-2 px-4 shadow-inner">
          <Component :is="versionInfoComponent" :composition="composition" />
        </div>
      </template>
      <template #footer>
        <SecondaryButton @click="confirmingDeletion = false"> Cancel </SecondaryButton>
        <DangerButton class="ml-3" :disabled="deleteForm.processing" @click="deleteResult">
          Yes, Delete
        </DangerButton>
      </template>
    </ConfirmationModal>
  </div>
</template>

<script setup lang="ts">
import { defineAsyncComponent, nextTick, PropType, ref } from 'vue'
import { type Component } from 'vue'
import { CompositionTemplateType } from '@/enums'
import { $computed } from 'vue/macros'
import LinkButton from '@/Components/LinkButton.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import { useIsMouseInside } from '@/compositions/useIsMouseInside'
import { useMagicKeys, whenever } from '@vueuse/core'
import { and } from '@vueuse/math'

const props = defineProps({
  composition: { type: Object as PropType<Composition>, required: true },
  rootComposition: { type: Object as PropType<Composition>, default: null },
  active: { type: Boolean, default: false },
})

const AmazonListingVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/AmazonListingVersionInfo.vue')
)

const FreeformVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/FreeformVersionInfo.vue')
)

const ResponseVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/ResponseVersionInfo.vue')
)
const RewriteVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/RewriteVersionInfo.vue')
)

const SummaryVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/SummaryVersionInfo.vue')
)

const versionInfoComponent = $computed<Component>(() => {
  switch (props.composition.template) {
    case CompositionTemplateType.AmazonProductListing:
      return AmazonListingVersionInfo
    case CompositionTemplateType.Freeform:
      return FreeformVersionInfo
    case CompositionTemplateType.Response:
      return ResponseVersionInfo
    case CompositionTemplateType.Rewrite:
      return RewriteVersionInfo
    case CompositionTemplateType.Summary:
      return SummaryVersionInfo
  }
  return null
})

let confirmingDeletion = $ref(false)
const deleteForm = useForm({})

async function deleteResult() {
  deleteForm.delete(route('compositions.destroy', props.composition.id), {
    preserveScroll: true,
    preserveState: true,
    onFinish: () => {
      confirmingDeletion = false
    },
    onError: (error) => {
      console.error(error)
    },
  })
}

const shortcutActiveContainer = ref<HTMLElement | null>(null)
const { isInside } = useIsMouseInside(shortcutActiveContainer)

const { d } = useMagicKeys()

whenever(and(d, isInside), () => {
  confirmingDeletion = true
})
</script>
