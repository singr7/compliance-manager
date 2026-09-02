import { useEffect, useState } from 'react';
import { api } from '../lib/api.js';

export default function Users() {
  const [users, setUsers] = useState([]);
  const [orgs, setOrgs] = useState([]);
  const [form, setForm] = useState({
    fullName: '',
    email: '',
    password: '',
    role: 'customer_user',
    organisationId: '',
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);

  async function refresh() {
    setLoading(true);
    try {
      const [userList, orgList] = await Promise.all([api.listUsers(), api.listOrganisations()]);
      setUsers(userList);
      setOrgs(orgList);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    refresh();
  }, []);

  async function handleInvite(e) {
    e.preventDefault();
    setError('');
    try {
      const payload = { ...form };
      if (payload.role === 'auditor') delete payload.organisationId;
      await api.inviteUser(payload);
      setForm({ fullName: '', email: '', password: '', role: 'customer_user', organisationId: '' });
      await refresh();
    } catch (err) {
      setError(err.message);
    }
  }

  return (
    <div className="page">
      <h1>Users</h1>
      <form className="stacked-form" onSubmit={handleInvite}>
        <input
          placeholder="Full name"
          value={form.fullName}
          onChange={(e) => setForm({ ...form, fullName: e.target.value })}
          required
        />
        <input
          type="email"
          placeholder="Email"
          value={form.email}
          onChange={(e) => setForm({ ...form, email: e.target.value })}
          required
        />
        <input
          type="password"
          placeholder="Temporary password"
          value={form.password}
          onChange={(e) => setForm({ ...form, password: e.target.value })}
          required
        />
        <select value={form.role} onChange={(e) => setForm({ ...form, role: e.target.value })}>
          <option value="customer_user">Customer User</option>
          <option value="auditor">Auditor / Admin</option>
        </select>
        {form.role === 'customer_user' && (
          <select
            value={form.organisationId}
            onChange={(e) => setForm({ ...form, organisationId: e.target.value })}
            required
          >
            <option value="">Select organisation…</option>
            {orgs.map((org) => (
              <option key={org._id} value={org._id}>
                {org.name}
              </option>
            ))}
          </select>
        )}
        <button type="submit">Invite user</button>
      </form>
      {error && <p className="error">{error}</p>}
      {loading ? (
        <p>Loading…</p>
      ) : (
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {users.map((u) => (
              <tr key={u.id}>
                <td>{u.fullName}</td>
                <td>{u.email}</td>
                <td>{u.role}</td>
                <td>{u.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
