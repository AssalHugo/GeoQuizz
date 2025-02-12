import { useUserStore } from '@/stores/userStore.js'

const BASE_URL = 'http://localhost:1000'
let hasRefreshed = false

const request = async (endpoint, method = 'GET', body = null, isAuthRequest = false, refreshToken = null) => {
  if (refreshToken) {
    const response = await fetch(`${BASE_URL}/auth/refresh`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Cache-Control': 'no-cache, no-store, must-revalidate',
        Pragma: 'no-cache',
        Expires: '0',
        Authorization: `Bearer ${refreshToken}`,
      }
    })
    if (!response.ok) {
      throw new Error('Invalid refresh token')
    }
    const { token } = await response.json()
    useUserStore().setToken(token)
  }

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
    if (!response.ok) {
      const errorBody = await response.json()
      throw new Error(errorBody.message || 'Something went wrong')
    }
    const contentType = response.headers.get('content-type')
    return contentType && contentType.includes('application/json') ? await response.json() : null
  } catch (error) {
    if (!hasRefreshed) {
      hasRefreshed = true
      await request(endpoint, method, body, isAuthRequest, useUserStore().refreshToken)
    }
    throw error
  }
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
  return request('/games', 'POST', { userId: user_id, serieId: serie_id })
}

export function joinGame(gameId) {
  return request(`/games/${gameId}/start`, 'PATCH')
}

export function getHighestScore(userId, serieId) {
  if (serieId) {
    return request(`/users/${userId}/highest-score?serie_id=${serieId}`)
  }
  return request(`/users/${userId}/highest-score`)
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
