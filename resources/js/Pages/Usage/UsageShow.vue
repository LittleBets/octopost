<template>
  <AppLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Credits Usage</h2>
    </template>

    <div class="lg:px- mx-auto mt-8 h-full w-full max-w-6xl space-y-12 px-4 sm:px-6">
      <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900">Overall usage this month</h3>

        <dl
          class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow md:grid-cols-3 md:divide-y-0 md:divide-x"
        >
          <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">Total</dt>
            <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
              <div class="flex items-baseline text-2xl font-semibold text-gray-700">
                {{ totals.total }}
              </div>
            </dd>
          </div>

          <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">Prompts</dt>
            <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
              <div class="flex items-baseline text-2xl font-semibold text-gray-700">
                {{ totals.prompts }}
              </div>
            </dd>
          </div>

          <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">Completions</dt>
            <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
              <div class="flex items-baseline text-2xl font-semibold text-gray-700">
                {{ totals.completions }}
              </div>
            </dd>
          </div>
        </dl>
      </div>

      <div class="">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Usage by team</h3>
        <div class="mt-5 rounded-lg bg-white px-4 py-4 shadow">
          <ListItemSelector v-model="selectedTeam" :options="teams" title="Select a Team" />

          <dl
            class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden bg-white md:grid-cols-3 md:divide-y-0 md:divide-x"
          >
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-normal text-gray-900">Total</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-gray-700">
                  {{ selectedTeamUsage?.total_credits }}
                </div>
              </dd>
            </div>

            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-normal text-gray-900">Prompts</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-gray-700">
                  {{ selectedTeamUsage?.total_prompt_credits }}
                </div>
              </dd>
            </div>

            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-normal text-gray-900">Completions</dt>
              <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-gray-700">
                  {{ selectedTeamUsage?.total_completion_credits }}
                </div>
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { PropType } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { $computed } from 'vue/macros'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'

interface Usage {
  total_prompt_credits: number
  total_completion_credits: number
  total_credits: number
  team_id: string
}

const user = $computed(() => usePage().props.value.user as User)
const teams = $computed(() =>
  (user?.all_teams ?? []).map((team) => ({ id: team.id, title: team.name }))
)

const props = defineProps({
  usageByTeams: { type: Object as PropType<Usage[]>, required: true },
})

const selectedTeam = $ref(teams[0].id)

const selectedTeamUsage = $computed<Usage | undefined>(() =>
  props.usageByTeams.find((usage) => usage.team_id === selectedTeam)
)

const totals = $computed(() => {
  return props.usageByTeams.reduce(
    (agg, usage) => {
      agg.prompts += usage.total_prompt_credits
      agg.completions += usage.total_completion_credits
      agg.total += usage.total_credits
      return agg
    },
    { prompts: 0, completions: 0, total: 0 }
  )
})
</script>
