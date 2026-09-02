import { Navigate, Route, Routes } from 'react-router-dom';
import { AuthProvider, useAuth } from './lib/AuthContext.jsx';
import Layout from './components/Layout.jsx';
import Login from './pages/Login.jsx';
import Dashboard from './pages/Dashboard.jsx';
import Organisations from './pages/Organisations.jsx';
import Users from './pages/Users.jsx';
import Profile from './pages/Profile.jsx';
import Placeholder from './pages/Placeholder.jsx';
import Templates from './pages/Templates.jsx';
import TemplateEditor from './pages/TemplateEditor.jsx';
import Assessments from './pages/Assessments.jsx';
import AssessmentDetail from './pages/AssessmentDetail.jsx';

function RequireAuth({ children }) {
  const { user, ready } = useAuth();
  if (!ready) return null;
  if (!user) return <Navigate to="/login" replace />;
  return children;
}

function RequireAuditor({ children }) {
  const { user } = useAuth();
  if (user.role !== 'auditor') return <Navigate to="/" replace />;
  return children;
}

function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route
        path="/"
        element={
          <RequireAuth>
            <Layout />
          </RequireAuth>
        }
      >
        <Route index element={<Dashboard />} />
        <Route path="assessments" element={<Assessments />} />
        <Route path="assessments/:id" element={<AssessmentDetail />} />
        <Route
          path="templates"
          element={
            <RequireAuditor>
              <Templates />
            </RequireAuditor>
          }
        />
        <Route
          path="templates/:id"
          element={
            <RequireAuditor>
              <TemplateEditor />
            </RequireAuditor>
          }
        />
        <Route
          path="organisations"
          element={
            <RequireAuditor>
              <Organisations />
            </RequireAuditor>
          }
        />
        <Route
          path="users"
          element={
            <RequireAuditor>
              <Users />
            </RequireAuditor>
          }
        />
        <Route path="profile" element={<Profile />} />
      </Route>
    </Routes>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <AppRoutes />
    </AuthProvider>
  );
}
