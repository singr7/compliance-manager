const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:4000';

let authToken = null;

export function setAuthToken(token) {
  authToken = token;
}

async function request(path, { method = 'GET', body, auth = true } = {}) {
  const headers = { 'Content-Type': 'application/json' };
  if (auth && authToken) headers.Authorization = `Bearer ${authToken}`;

  const res = await fetch(`${API_BASE_URL}${path}`, {
    method,
    headers,
    body: body ? JSON.stringify(body) : undefined,
  });

  if (res.status === 204) return null;

  const data = await res.json().catch(() => null);
  if (!res.ok) {
    throw new Error(data?.error || `Request failed with status ${res.status}`);
  }
  return data;
}

export const api = {
  login: (email, password) => request('/auth/login', { method: 'POST', body: { email, password }, auth: false }),
  me: () => request('/users/me'),
  listOrganisations: () => request('/organisations'),
  createOrganisation: (name) => request('/organisations', { method: 'POST', body: { name } }),
  listUsers: (organisationId) =>
    request(`/users${organisationId ? `?organisationId=${organisationId}` : ''}`),
  inviteUser: (payload) => request('/users', { method: 'POST', body: payload }),

  listTemplates: () => request('/templates'),
  getTemplate: (id) => request(`/templates/${id}`),
  createTemplate: (payload) => request('/templates', { method: 'POST', body: payload }),
  duplicateTemplate: (id, newName) =>
    request(`/templates/${id}/duplicate`, { method: 'POST', body: { newName } }),
  updateTemplate: (id, payload) => request(`/templates/${id}`, { method: 'PATCH', body: payload }),
  addSection: (templateId, title) =>
    request(`/templates/${templateId}/sections`, { method: 'POST', body: { title } }),
  updateSection: (templateId, sectionId, payload) =>
    request(`/templates/${templateId}/sections/${sectionId}`, { method: 'PATCH', body: payload }),
  addQuestion: (templateId, sectionId, payload) =>
    request(`/templates/${templateId}/sections/${sectionId}/questions`, {
      method: 'POST',
      body: payload,
    }),
  updateQuestion: (templateId, sectionId, questionId, payload) =>
    request(`/templates/${templateId}/sections/${sectionId}/questions/${questionId}`, {
      method: 'PATCH',
      body: payload,
    }),
};
