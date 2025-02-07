import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
    refreshToken: null,
  }),
  actions: {
    setToken(token) {
      this.token = token
    },
    setRefreshToken(token) {
      this.refreshToken = token
    },
    logout() {
      this.token = null
      this.refreshToken = null
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
      if (payload) return payload.sub;
    },
  },
  //Ceci permet de persister les données de l'utilisateur même après un refresh de la page
  persist: true,
})
