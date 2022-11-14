/// <reference types="vue/macros-global" />
import type { Axios } from 'axios'
import ziggyRoute from 'ziggy-js'
import { CompositionTemplateType } from '@/enums'

declare global {
  const axios: Axios
  const route: typeof ziggyRoute

  interface User {
    name: string
  }

  interface CompositionPayload extends Record<string, unknown> {
    name: string
    audience?: string
    response_type: string
    composition_length: string | number
    tone: string
    variations: number
  }

  interface ResponseCompositionPayload extends CompositionPayload {
    message: string
  }

  interface AmazonListingCompositionPayload extends CompositionPayload {
    features: string
  }

  interface FreeformCompositionPayload extends CompositionPayload {
    input_prompt: string
  }

  interface Composition<PayloadType extends CompositionPayload = CompositionPayload> {
    id: string
    children: Composition<PayloadType>[]
    created_at: string
    created_at_short: string
    template: CompositionTemplateType
    label: string
    payload: PayloadType
    composition_result: CompositionResult
    user?: User
    versions?: number
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

  const AmazonProductListingTemplateType = 'amazon-product-listing'

  interface TemplateType {
    id: CompositionTemplateType
    title: string
  }
}
