import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: null
  }),
  actions: {
    setUser(userData, userToken) {
      this.user = userData
      this.token = userToken
    },
    logout() {
      this.user = null
      this.token = null
    }
  },
  persist: true
})
