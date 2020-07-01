import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import colors from 'vuetify/lib/util/colors'
import '@mdi/font/css/materialdesignicons.css'
import ru from 'vuetify/lib/locale/ru'

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: colors.indigo,
                secondary: colors.grey.darken1,
                accent: colors.shades.black,
                error: colors.red.accent3,
                background: '#d6d6d6', // Not automatically applied
            },
            dark: {
                primary: colors.blue.lighten3,
                background: colors.indigo.base, // If not using lighten/darken, use base to return hex
            },
        },

    },
    lang: {
        locales: { ru },
        current: 'ru',
    },
    icons: {
        iconfont: 'mdi', // default - only for display purposes
    },
})
