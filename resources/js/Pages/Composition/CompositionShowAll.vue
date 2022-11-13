<template>
  <AppLayout>
    <div class="overflow-y-auto py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow sm:rounded-md">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="composition in compositions" :key="composition.id">
              <CompositionItem :composition="composition" />
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { PropType } from 'vue'
import CompositionItem from '@/Pages/Composition/CompositionShowAllItem.vue'

defineProps({
  compositions: {
    type: Array as PropType<Composition[]>,
    required: true,
  },
})
</script>
