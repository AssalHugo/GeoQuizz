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
    isAuthenticated() {
      if (!this.token) return false
      //On vérifie si le token est valide
      validateToken(this.token).then(
        (response) => {
          return response.ok;
        },
        (error) => {
          console.error('API Error:', error)
          return false
        }
      );
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
