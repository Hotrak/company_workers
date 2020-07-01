import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user.js'
import worker from './modules/worker.js'
import position from './modules/position.js'
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

export default new Vuex.Store({
    modules:{
        user,
        worker,
        position,
    },
    plugins: [createPersistedState()],
});
