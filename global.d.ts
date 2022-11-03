/// <reference types="vue/macros-global" />
import type { Axios } from 'axios'
import ziggyRoute from 'ziggy-js'

declare global {
  const axios: Axios
  const route: typeof ziggyRoute
}
