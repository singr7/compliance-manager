import { useAuth } from '../lib/AuthContext.jsx';

export default function Profile() {
  const { user } = useAuth();
  return (
    <div className="page">
      <h1>Profile</h1>
      <dl>
        <dt>Full name</dt>
        <dd>{user.fullName}</dd>
        <dt>Email</dt>
        <dd>{user.email}</dd>
        <dt>Role</dt>
        <dd>{user.role}</dd>
      </dl>
    </div>
  );
}
