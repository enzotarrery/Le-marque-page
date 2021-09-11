import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    connected: false,
    count: 0,
    username: '',
    beforeConnection: null
  },
  mutations: {
    connect(state, data) {
      state.connected = true
      state.username = data.username
      sessionStorage.setItem('token', data.token)
      state.count++
    },
    disconnect(state) {
      state.connected = false
      state.username = ''
      sessionStorage.removeItem('token')
      state.count--
    }
  },
  actions: {
  },
  modules: {
  }
})
