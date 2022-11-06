<template>
  <div class="gap-8 overflow-y-hidden h-full flex flex-col">
    <div class="space-y-6 overflow-y-auto h-full">
      <article v-for="choice in result.choices" :key="choice.id">
        <div class="mx-auto p-4 lg:max-w-4xl rounded bg-white group shadow-sm hover:bg-gray-50">
          <div class="flex flex-col items-start">
            <p class="mt-1 text-base leading-7 text-slate-700">
              {{ choice.text }}
            </p>
            <div
              class="mt-4 flex items-center gap-4 w-full justify-end opacity-0 group-hover:opacity-100 transition hover:transition-all ease-in-out duration-300"
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
