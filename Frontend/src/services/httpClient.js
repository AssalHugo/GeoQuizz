const BASE_URL = "";

const request = async (
  endpoint,
  method = 'GET',
  body = null,
) => {
  // Récupérer le token
  // Définir les en-têtes
  const headers = {
    'Content-Type': 'application/json',
  };
  // Configurer la requête
  const config = {
    method,
    headers,
    ...(body && {body: JSON.stringify(body)}),
  };
  try {
    //Si le endpoint est une URL complète, on ne rajoute pas le BASE_URL
    const url = endpoint.startsWith('http') ? endpoint : `${BASE_URL}${endpoint}`;
    // Envoyer la requête
    const response = await fetch(url, config);

    //Si la réponse est Not Found, on renvoie une erreur
    if (response.status === 404) {
      throw new Error('Not Found');
    }
    // Gérer la réponse
    if (!response.ok) {
      const errorBody = await response.json();
      throw new Error(errorBody.message || 'Something went wrong');
    }
    // Vérifier si la réponse a du contenu
    const contentType = response.headers.get('content-type');
    return contentType && contentType.includes('application/json')
      ? await response.json()
      : null;
  } catch (error) {
    // Gestion des erreurs
    console.error('API Error:', error);
    throw error;
  }
};

export function getGamesUser(userId) {
  return request(`/users/${userId}/games`);
}

export function login(username, password) {
  return request('/auth/login', 'POST', {username, password});
}
