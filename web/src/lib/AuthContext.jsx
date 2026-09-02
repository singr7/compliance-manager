import { createContext, useContext, useEffect, useState } from 'react';
import { api, setAuthToken } from './api.js';

const AuthContext = createContext(null);

const STORAGE_KEY = 'cm_auth';

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [ready, setReady] = useState(false);

  useEffect(() => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
      const { token, user: storedUser } = JSON.parse(stored);
      setAuthToken(token);
      setUser(storedUser);
    }
    setReady(true);
  }, []);

  async function login(email, password) {
    const { token, user: loggedInUser } = await api.login(email, password);
    setAuthToken(token);
    setUser(loggedInUser);
    localStorage.setItem(STORAGE_KEY, JSON.stringify({ token, user: loggedInUser }));
  }

  function logout() {
    setAuthToken(null);
    setUser(null);
    localStorage.removeItem(STORAGE_KEY);
  }

  return (
    <AuthContext.Provider value={{ user, ready, login, logout }}>{children}</AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
}
