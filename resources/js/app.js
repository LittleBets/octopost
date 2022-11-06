import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js/dist/vue.m'
import { Icon } from '@iconify/vue'
import VueTippy from 'vue-tippy'
import 'tippy.js/themes/light-border.css'
import 'tippy.js/dist/tippy.css' // default dark theme
import 'tippy.js/animations/shift-toward-subtle.css'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Octopost'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VueTippy)
      .component('icon', Icon)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' })
