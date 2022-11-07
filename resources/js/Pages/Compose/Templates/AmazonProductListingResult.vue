<template>
  <div class="flex h-full flex-col gap-8 overflow-y-hidden">
    <div class="h-full space-y-6 overflow-y-auto">
      <article v-for="choice in result.choices" :key="choice.id">
        <div class="group mx-auto rounded bg-white p-4 shadow-sm hover:bg-gray-50 lg:max-w-4xl">
          <div class="flex flex-col items-start">
            <p class="mt-1 text-base leading-7 text-slate-700">
              {{ choice.text }}
            </p>
            <div
              class="mt-4 flex w-full items-center justify-end gap-4 opacity-25 transition duration-300 ease-in-out hover:transition-all group-hover:opacity-100"
            >
              <LinkButton
                v-if="isClipboardSupported"
                v-tippy="'Copy to Clipboard'"
                @click="copy(choice.text)"
              >
                <icon icon="ph:clipboard" class="h-5 w-auto" />
              </LinkButton>
            </div>
          </div>
        </div>
      </article>
    </div>
    <slot name="footer" />
  </div>
</template>

<script setup lang="ts">
import { useClipboard } from '@vueuse/core'
import LinkButton from '@/Components/LinkButton.vue'
const { copy, isSupported: isClipboardSupported } = useClipboard()
const { result } = defineProps<Props>()

interface Props {
  result: CompositionResult
}
</script>
