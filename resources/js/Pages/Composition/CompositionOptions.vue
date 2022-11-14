<template>
  <Menu as="div" class="relative ml-3 inline-block text-left">
    <div>
      <MenuButton
        :disabled="deleteForm.processing"
        class="focus:ring-none -my-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600 focus:outline-none"
      >
        <span class="sr-only">Open options</span>
        <icon aria-hidden="true" class="h-5 w-5 text-black" icon="ph:dots-three-vertical-bold" />
      </MenuButton>
    </div>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <MenuItems
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right space-y-1 divide-y rounded bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      >
        <div v-for="option in options" :key="option.label" class="py-1">
          <MenuItem v-slot="{ active }">
            <button
              :class="[
                active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                'flex w-full justify-between px-4 py-2 text-sm',
              ]"
              type="button"
              @click="option.clickHandler"
            >
              <span :class="[option.iconColor ?? '', 'flex items-center space-x-1.5']">
                <icon :icon="option.icon" class="h-4 w-4" />
                <span>{{ option.label }}</span>
              </span>
            </button>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>
  <DialogModal :show="confirmingDelete" @close="closeDeleteConfirmationModal">
    <template #title>Delete Composition</template>

    <template #content>
      <div class="font-medium">
        Are you sure you want to delete this composition? This cannot be undone.
      </div>
      <div class="mt-4 font-semibold text-red-600">
        ! This will also delete all its versions and results.
      </div>
    </template>

    <template #footer>
      <SecondaryButton @click="closeDeleteConfirmationModal"> Cancel</SecondaryButton>

      <DangerButton
        :class="{ 'opacity-25': deleteForm.processing }"
        :disabled="deleteForm.processing"
        class="ml-3"
        @click="deletePost"
      >
        Confirm
      </DangerButton>
    </template>
  </DialogModal>
</template>

<script lang="ts" setup>
import DangerButton from '@/Components/DangerButton.vue'
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { computed, PropType, ref } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { useForm } from '@inertiajs/inertia-vue3'
import route from 'ziggy-js'

const props = defineProps({
  composition: { type: Object as PropType<Composition>, required: true },
})

const options = computed(() => {
  return [
    {
      label: 'Delete Composition',
      icon: 'ph:trash-simple',
      iconColor: 'text-red-600',
      clickHandler: confirmDelete,
    },
  ]
})

const confirmingDelete = ref(false)

function confirmDelete() {
  confirmingDelete.value = true
}

const deleteForm = useForm({})

function deletePost() {
  deleteForm.delete(route('compositions.destroy', props.composition.id))
}

function closeDeleteConfirmationModal() {
  confirmingDelete.value = false
}
</script>
