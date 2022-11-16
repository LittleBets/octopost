<template>
  <tippy arrow interactive :hide-on-click="false" theme="light-border">
    <template #default>
      <div class="inline-flex items-center">
        <slot />
        <icon icon="ph:question-light" class="h-auto w-5" />
      </div>
    </template>

    <template #content="{ hide, state }">
      <div class="p-4 text-left">
        <slot name="content" :hide="hide" :state="state" />
      </div>
    </template>
  </tippy>
</template>
