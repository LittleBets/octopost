<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import ApplicationMark from '@/Components/ApplicationMark.vue'

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
})
</script>

<template>
  <Head title="Welcome" />

  <div
    class="items-top relative flex min-h-screen justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
  >
    <div v-if="canLogin" class="fixed top-0 right-0 px-6 py-4">
      <Link
        v-if="$page.props.user"
        :href="route('compose.new')"
        class="text-sm text-gray-700 underline dark:text-gray-500"
        >Compose</Link
      >

      <template v-else>
        <Link :href="route('login')" class="text-sm text-gray-700 underline dark:text-gray-500"
          >Log in</Link
        >

        <Link
          v-if="canRegister"
          :href="route('register')"
          class="ml-4 text-sm text-gray-700 underline dark:text-gray-500"
          >Register</Link
        >
      </template>
    </div>

    <div class="mx-auto my-auto max-w-6xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-center gap-4 pt-8 sm:justify-start sm:pt-0">
        <ApplicationMark class="h-16 w-auto text-gray-700 sm:h-20" />
        <div class="text-5xl font-semibold uppercase text-gray-700">Octopost</div>
      </div>
    </div>
  </div>
</template>
