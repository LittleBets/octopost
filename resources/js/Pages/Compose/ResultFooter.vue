<template>
  <div class="mx-auto bg-gray-100 leading-relaxed text-gray-600">
    <p>
      All your composition iterations are saved as versions. This is version
      {{ compositionVersion }}.
      <LinkButton
        as="anchor"
        :href="route('compositions.show-one', compositionId)"
        label="Check out"
        underline
        :target="compositionId"
      />
      all the versions.
    </p>
  </div>
</template>

<script setup lang="ts">
import LinkButton from '@/Components/LinkButton.vue'
defineProps({
  compositionId: { type: String, required: true },
  compositionVersion: { type: Number, required: true },
})
</script>
