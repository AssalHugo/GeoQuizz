import { useUserStore } from '@/stores/userStore.js'

const BASE_URL = ''

let isRefreshing = false
let refreshSubscribers = []

const request = async (endpoint, method = 'GET', body = null, isAuthRequest = false) => {
  const token = localStorage.getItem('token')
  const headers = {
    'Content-Type': 'application/json',
    ...(!isAuthRequest &&
      token && {
        Authorization: `Bearer ${token}`,
      }),
  }
  const config = {
    method,
    headers,
    ...(body && { body: JSON.stringify(body) }),
  }
  try {
    const response = await fetch(`${BASE_URL}${endpoint}`, config)
    if (response.status === 403 || response.status === 401) {
      if (!isRefreshing) {
        isRefreshing = true
        try {
          const data = await refreshToken()
          const userStore = useUserStore()
          userStore.setToken(data.token)
          refreshSubscribers.forEach((callback) => callback(data.token))
          refreshSubscribers = []
        } catch (error) {
          console.error('API Error:', error)
          throw error
        } finally {
          isRefreshing = false
        }
      }
      return new Promise((resolve) => {
        refreshSubscribers.push((token) => {
          config.headers.Authorization = `Bearer ${token}`
          resolve(request(endpoint, method, body, isAuthRequest))
        })
      })
    }
    if (!response.ok) {
      const errorBody = await response.json()
      throw new Error(errorBody.message || 'Something went wrong')
    }
    const contentType = response.headers.get('content-type')
    return contentType && contentType.includes('application/json') ? await response.json() : null
  } catch (error) {
    console.error('API Error:', error)
    throw error
  }
}

function refreshToken() {
  return request('/auth/refresh', 'POST', null, true)
}

export function getGamesUser(userId) {
  return request(`/users/${userId}/games`)
}

export function login(email, password) {
  return request('/auth/login', 'POST', { email: email, password: password })
}

export function register(nickname, email, password) {
  return request('/auth/register', 'POST', {
    nickname: nickname,
    email: email,
    password: password,
  })
}

export function createGame() {
  return request('/games', 'POST')
}

export function joinGame(gameId) {
  return request(`/games/${gameId}/start`, 'PUT')
}
