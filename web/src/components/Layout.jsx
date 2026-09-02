import { NavLink, Outlet, useNavigate } from 'react-router-dom';
import { useAuth } from '../lib/AuthContext.jsx';

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
        <h2>Compliance Manager</h2>
        <nav>
          <NavLink to="/">Dashboard</NavLink>
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
        <button className="logout" onClick={handleLogout}>
          Log out
        </button>
      </aside>
      <main className="content">
        <Outlet />
      </main>
    </div>
  );
}
