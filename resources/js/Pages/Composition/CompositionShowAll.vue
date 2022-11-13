<template>
  <AppLayout title="All Compositions">
    <div class="h-full w-full overflow-y-auto py-12">
      <div v-if="compositions.length" class="mx-auto my-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow sm:rounded-md">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="composition in compositions" :key="composition.id">
              <CompositionItem :composition="composition" />
            </li>
          </ul>
        </div>
      </div>
      <div v-else class="mx-auto my-auto flex h-full w-full max-w-2xl px-6">
        <Link
          :href="route('compose.new')"
          class="relative my-auto block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
        >
          <icon class="mx-auto h-10 w-10 text-gray-400" icon="ph:note-pencil" />
          <span class="mt-2 block text-lg font-medium text-gray-900">Compose a New Post</span>
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { PropType } from 'vue'
import CompositionItem from '@/Pages/Composition/CompositionShowAllItem.vue'
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
  compositions: {
    type: Array as PropType<Composition[]>,
    required: true,
  },
})
</script>
