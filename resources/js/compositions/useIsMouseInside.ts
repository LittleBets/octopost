import { computed, Ref } from 'vue'
import { useMouseInElement } from '@vueuse/core'

export function useIsMouseInside(containerRef: Ref<HTMLElement | null>) {
  const { isOutside } = useMouseInElement(containerRef)
  const isInside = computed(
    () => !isOutside.value && document.querySelectorAll('.mistyui-modal-show').length === 0
  )
  return { isInside }
}
