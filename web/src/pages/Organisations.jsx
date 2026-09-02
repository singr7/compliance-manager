import { useEffect, useState } from 'react';
import { api } from '../lib/api.js';

export default function Organisations() {
  const [orgs, setOrgs] = useState([]);
  const [name, setName] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  async function refresh() {
    setLoading(true);
    try {
      setOrgs(await api.listOrganisations());
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    refresh();
  }, []);

  async function handleCreate(e) {
    e.preventDefault();
    setError('');
    try {
      await api.createOrganisation(name);
      setName('');
      await refresh();
    } catch (err) {
      setError(err.message);
    }
  }

  return (
    <div className="page">
      <h1>Organisations</h1>
      <form className="inline-form" onSubmit={handleCreate}>
        <input
          placeholder="New organisation name"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
        />
        <button type="submit">Add organisation</button>
      </form>
      {error && <p className="error">{error}</p>}
      {loading ? (
        <p>Loading…</p>
      ) : (
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {orgs.map((org) => (
              <tr key={org._id}>
                <td>{org.name}</td>
                <td>{org.status}</td>
              </tr>
            ))}
            {orgs.length === 0 && (
              <tr>
                <td colSpan={2} className="muted">
                  No organisations yet.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      )}
    </div>
  );
}
