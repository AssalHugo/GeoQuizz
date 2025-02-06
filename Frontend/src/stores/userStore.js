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
      return !!this.token
    },
    // Return the user's id
    user() {
      if (!this.token) return null;
      const payload = JSON.parse(atob(this.token.split('.')[1]));
      // Return the user's sub part
      if (payload.user) return payload.user.sub;
    },
  },
  persist: true,
})
