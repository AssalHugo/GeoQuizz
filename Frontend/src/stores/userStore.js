import { defineStore } from 'pinia'
import { validateToken } from '@/services/httpClient.js'

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
    async isAuthenticated() {
      if (!this.token) return Promise.resolve(false);
      return validateToken().then(
        (response) => {
          return Promise.resolve(true);
        },
        (error) => {
          return Promise.resolve(false);
        }
      );
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
