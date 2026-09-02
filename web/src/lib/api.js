const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:4000';

let authToken = null;

export function setAuthToken(token) {
  authToken = token;
}

export function getAuthToken() {
  return authToken;
}

export function apiBaseUrl() {
  return API_BASE_URL;
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

async function requestForm(path, formData) {
  const headers = {};
  if (authToken) headers.Authorization = `Bearer ${authToken}`;
  const res = await fetch(`${API_BASE_URL}${path}`, { method: 'POST', headers, body: formData });
  if (res.status === 204) return null;
  const data = await res.json().catch(() => null);
  if (!res.ok) {
    throw new Error(data?.error || `Request failed with status ${res.status}`);
  }
  return data;
}

export async function downloadEvidence(evidenceId, filename) {
  const headers = {};
  if (authToken) headers.Authorization = `Bearer ${authToken}`;
  const res = await fetch(`${API_BASE_URL}/evidence/${evidenceId}/download`, { headers });
  if (!res.ok) throw new Error(`Download failed with status ${res.status}`);
  const blob = await res.blob();
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = filename;
  document.body.appendChild(link);
  link.click();
  link.remove();
  URL.revokeObjectURL(url);
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

  listAssessments: (params = {}) => {
    const qs = new URLSearchParams(params).toString();
    return request(`/assessments${qs ? `?${qs}` : ''}`);
  },
  createAssessment: (payload) => request('/assessments', { method: 'POST', body: payload }),
  getAssessment: (id) => request(`/assessments/${id}`),

  listResponses: (assessmentId) => request(`/assessments/${assessmentId}/responses`),
  saveResponse: (assessmentId, responseId, payload) =>
    request(`/assessments/${assessmentId}/responses/${responseId}`, { method: 'PATCH', body: payload }),

  listEvidence: (assessmentId, responseId) =>
    request(`/assessments/${assessmentId}/responses/${responseId}/evidence`),
  uploadEvidence: (assessmentId, responseId, file, description) => {
    const formData = new FormData();
    formData.append('file', file);
    if (description) formData.append('description', description);
    return requestForm(`/assessments/${assessmentId}/responses/${responseId}/evidence`, formData);
  },
  deleteEvidence: (assessmentId, responseId, evidenceId) =>
    request(`/assessments/${assessmentId}/responses/${responseId}/evidence/${evidenceId}`, {
      method: 'DELETE',
    }),

  reviewResponse: (assessmentId, responseId, payload) =>
    request(`/assessments/${assessmentId}/responses/${responseId}/review`, { method: 'POST', body: payload }),
  listComments: (assessmentId, responseId) =>
    request(`/assessments/${assessmentId}/responses/${responseId}/comments`),
  addComment: (assessmentId, responseId, text) =>
    request(`/assessments/${assessmentId}/responses/${responseId}/comments`, {
      method: 'POST',
      body: { text },
    }),
};
