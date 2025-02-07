import { useUserStore } from '@/stores/userStore.js'

const BASE_URL = 'http://localhost:1000'

let isRefreshing = false
let refreshSubscribers = []

const request = async (endpoint, method = 'GET', body = null, isAuthRequest = false) => {
  const token = useUserStore().token
  const headers = {
    'Content-Type': 'application/json',
    'Cache-Control': 'no-cache, no-store, must-revalidate',
    Pragma: 'no-cache',
    Expires: '0',
    ...(!isAuthRequest &&
      token && {
        Authorization: `Bearer ${token}`,
      }),
  }
  const config = {
    method,
    headers,
    credentials: 'include',
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

export function getSeries() {
  return request('/series')
}

export function login(email, password) {
  return request('/auth/signin', 'POST', { email: email, password: password })
}

export function register(nickname, email, password) {
  return request('/auth/register', 'POST', {
    nickname: nickname,
    email: email,
    password: password,
  })
}

export function createGame(user_id, serie_id) {
  return request('/games', 'POST', { user_id: user_id, serie_id: serie_id })
}

export function joinGame(gameId) {
  return request(`/games/${gameId}/start`, 'PUT')
}

export function getGameById(gameId) {
  return request(`/games/${gameId}`)
}

export function getCurrentPhoto(gameId) {
  return request(`/games/${gameId}/current-photo`)
}

export function getSerieById(serieId) {
  return request(`/series/${serieId}`)
}

export function validateAnswer(gameId, lat, long) {
  return request(`/games/${gameId}/answer`, 'POST', { latitude: lat, longitude: long })
}

export function nextPhoto(gameId) {
  return request(`/games/${gameId}/next-photo`)
}

export function validateToken() {
  return request('/tokens/validate', 'POST')
}
