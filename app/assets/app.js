import { createApp } from 'vue'
import App from './App.vue'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as directives from 'vuetify/directives'
import * as components from 'vuetify/components'
import * as labsComponents from 'vuetify/labs/components'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import { fa } from "vuetify/iconsets/fa";
import "@mdi/font/css/materialdesignicons.css";

const vuetify = createVuetify({
    theme: {
        defaultTheme: "dark",
    },
    components: {
        ...components,
        ...directives,
        ...labsComponents,
    },
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
            fa
        }
    }
})

createApp(App).use(vuetify).mount('#app')


