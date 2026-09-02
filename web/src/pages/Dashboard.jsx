import { useAuth } from '../lib/AuthContext.jsx';

export default function Dashboard() {
  const { user } = useAuth();

  return (
    <div className="page">
      <h1>Welcome, {user.fullName}</h1>
      <p>
        Role: <strong>{user.role === 'auditor' ? 'Auditor / Admin' : 'Customer User'}</strong>
      </p>
      <p className="muted">
        Dashboard content (active assessments, needs-attention lists) arrives in a later session.
      </p>
    </div>
  );
}
