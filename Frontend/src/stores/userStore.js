import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
  }),
  actions: {
    setToken(token) {
      this.token = token
    },
    logout() {
      this.token = null
    },
  },
  getters: {
    isAuthenticated() {
      return this.token !== null
    },
    //Retourne l'id de l'user
    user() {
      if (!this.token) return null
      const payload = JSON.parse(atob(this.token.split('.')[1]))
      //On retourne la partie sub de l'user
      if (payload.user) return payload.user.sub
    },
  },
  persist: true,
})
