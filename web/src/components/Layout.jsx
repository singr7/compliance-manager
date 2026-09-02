import { NavLink, Outlet, useNavigate } from 'react-router-dom';
import { useAuth } from '../lib/AuthContext.jsx';

function initials(fullName) {
  return fullName
    .split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
}

export default function Layout() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  function handleLogout() {
    logout();
    navigate('/login');
  }

  return (
    <div className="shell">
      <aside className="sidebar">
        <div className="sidebar-brand">
          <div className="wordmark">Compliance Manager</div>
        </div>

        <nav>
          <NavLink to="/" end>
            Dashboard
          </NavLink>
          <NavLink to="/assessments">Assessments</NavLink>
          {user.role === 'auditor' && (
            <>
              <NavLink to="/templates">Templates</NavLink>
              <NavLink to="/organisations">Customers</NavLink>
              <NavLink to="/users">Users</NavLink>
            </>
          )}
          <NavLink to="/profile">Profile</NavLink>
        </nav>

        <div className="sidebar-footer">
          <div style={{ display: 'flex', alignItems: 'center', gap: 9 }}>
            <div
              style={{
                width: 26,
                height: 26,
                borderRadius: '50%',
                background: 'oklch(40% 0.03 235)',
                color: 'oklch(94% 0.004 250)',
                fontSize: 11,
                fontWeight: 600,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0,
              }}
            >
              {initials(user.fullName)}
            </div>
            <div>
              <div style={{ fontSize: 12.5, color: 'var(--ink-text-bright)' }}>{user.fullName}</div>
              <div style={{ fontSize: 11, color: 'var(--ink-text-dim)' }}>
                {user.role === 'auditor' ? 'Auditor / Admin' : 'Customer User'}
              </div>
            </div>
          </div>
          <button className="logout" onClick={handleLogout}>
            Log out
          </button>
        </div>
      </aside>
      <main className="content">
        <Outlet />
      </main>
    </div>
  );
}
