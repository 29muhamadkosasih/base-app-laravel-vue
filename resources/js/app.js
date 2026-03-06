import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import 'sweetalert2/dist/sweetalert2.min.css';

const syncFavicon = (iconUrl) => {
  if (!iconUrl) {
    return
  }

  let favicon = document.querySelector("link[rel='icon']")

  if (!favicon) {
    favicon = document.createElement('link')
    favicon.rel = 'icon'
    document.head.appendChild(favicon)
  }

  favicon.href = iconUrl
}

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
    .mixin({
        mounted() {
          syncFavicon(this.$page.props.appSettings?.image_url)
        },

        updated() {
          syncFavicon(this.$page.props.appSettings?.image_url)
        },
    })
    .use(plugin)
    .mount(el)
  },
  progress: {
    // The delay after which the progress bar will appear, in milliseconds...
    delay: 250,

    // The color of the progress bar...
    color: '#29d',

    // Whether to include the default NProgress styles...
    includeCSS: true,

    // Whether the NProgress spinner will be shown...
    showSpinner: false,
  },
})
