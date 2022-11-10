/// <reference types="vue/macros-global" />
import type { Axios } from 'axios'
import ziggyRoute from 'ziggy-js'

declare global {
  const axios: Axios
  const route: typeof ziggyRoute

  interface Composition {
    id: string
    children: Composition[]
    created_at: string
    created_at_short: string
    template: string
    payload: Record<string, unknown>
    composition_result: CompositionResult
  }

  interface CompositionResult {
    id: string
    usage: CompositionUsage
    choices: CompositionResultChoice[]
  }

  interface CompositionUsage {
    completion_tokens: number
    prompt_tokens: number
    total_tokens: number
  }

  interface CompositionResultChoice {
    id: string
    created_at: string
    created_at_short: string
    text: string
  }

  type TemplateTypeIds = 'response' | 'amazon-product-listing' | 'freeform'
  interface TemplateType {
    id: TemplateTypeIds
    title: string
  }
}
