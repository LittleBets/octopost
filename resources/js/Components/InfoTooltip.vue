<template>
  <tippy arrow interactive :hide-on-click="false" theme="light-border" :append-to="appendToBody">
    <div class="inline-flex items-center">
      <slot />
      <icon icon="uiw:question-circle-o" class="h-4 w-auto" />
    </div>

    <template #content="{ hide, state }">
      <div class="p-4 text-left leading-6">
        <slot name="content" :hide="hide" :state="state" />
      </div>
    </template>
  </tippy>
</template>

<script setup>
const appendToBody = () => document.body
</script>
