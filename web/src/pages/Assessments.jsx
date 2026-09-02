import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../lib/AuthContext.jsx';
import { api } from '../lib/api.js';

const STATUS_LABELS = {
  draft: 'Draft',
  active: 'Active',
  under_review: 'Under review',
  completed: 'Completed',
};

function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

function progressLabel(progress) {
  if (!progress || progress.total === 0) return '—';
  const done = Object.entries(progress.counts || {})
    .filter(([status]) => status !== 'not_started')
    .reduce((sum, [, count]) => sum + count, 0);
  return `${done}/${progress.total}`;
}

export default function Assessments() {
  const navigate = useNavigate();
  const { user } = useAuth();
  const isAuditor = user.role === 'auditor';

  const [assessments, setAssessments] = useState([]);
  const [templates, setTemplates] = useState([]);
  const [organisations, setOrganisations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [creating, setCreating] = useState(false);
  const [templateId, setTemplateId] = useState('');
  const [organisationId, setOrganisationId] = useState('');
  const [dueDate, setDueDate] = useState('');

  async function refresh() {
    setLoading(true);
    try {
      setAssessments(await api.listAssessments());
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    refresh();
    if (isAuditor) {
      api.listTemplates().then((all) => setTemplates(all.filter((t) => t.status === 'active')));
      api.listOrganisations().then(setOrganisations);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  async function handleCreate(e) {
    e.preventDefault();
    setError('');
    try {
      const assessment = await api.createAssessment({
        templateId,
        organisationId,
        dueDate: dueDate || undefined,
      });
      setCreating(false);
      setTemplateId('');
      setOrganisationId('');
      setDueDate('');
      await refresh();
      navigate(`/assessments/${assessment._id}`);
    } catch (err) {
      setError(err.message);
    }
  }

  function templateName(id) {
    return templates.find((t) => t._id === id)?.name;
  }
  function orgName(id) {
    return organisations.find((o) => o._id === id)?.name;
  }

  return (
    <div className="page">
      <div className="page-header">
        <div>
          <div className="page-eyebrow">Assessments</div>
          <h1>Assessments</h1>
        </div>
        {isAuditor && <button onClick={() => setCreating(true)}>New Assessment</button>}
      </div>
      <p className="page-lede">
        {isAuditor
          ? 'Create an assessment from an active template for a customer organisation.'
          : 'Assessments assigned to your organisation.'}
      </p>

      {creating && (
        <div className="dialog-overlay" onClick={() => setCreating(false)}>
          <div className="dialog" onClick={(e) => e.stopPropagation()}>
            <h2>New assessment</h2>
            <form onSubmit={handleCreate} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
                Template
                <select value={templateId} onChange={(e) => setTemplateId(e.target.value)} required autoFocus>
                  <option value="" disabled>
                    Select an active template
                  </option>
                  {templates.map((t) => (
                    <option key={t._id} value={t._id}>
                      {t.name}
                    </option>
                  ))}
                </select>
              </label>
              <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
                Organisation
                <select value={organisationId} onChange={(e) => setOrganisationId(e.target.value)} required>
                  <option value="" disabled>
                    Select an organisation
                  </option>
                  {organisations.map((o) => (
                    <option key={o._id} value={o._id}>
                      {o.name}
                    </option>
                  ))}
                </select>
              </label>
              <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
                Due date (optional)
                <input type="date" value={dueDate} onChange={(e) => setDueDate(e.target.value)} />
              </label>
              <div className="dialog-actions">
                <button type="button" className="secondary" onClick={() => setCreating(false)}>
                  Cancel
                </button>
                <button type="submit">Create</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {error && <p className="error">{error}</p>}

      {loading ? (
        <p className="muted">Loading…</p>
      ) : (
        <div className="table-card">
          <table>
            <thead>
              <tr>
                <th>Template</th>
                {isAuditor && <th>Organisation</th>}
                <th style={{ textAlign: 'center' }}>Progress</th>
                <th>Status</th>
                <th>Due</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {assessments.map((a) => (
                <tr key={a._id}>
                  <td>
                    <a
                      href={`#/assessments/${a._id}`}
                      onClick={(e) => {
                        e.preventDefault();
                        navigate(`/assessments/${a._id}`);
                      }}
                    >
                      {templateName(a.templateId) || a.templateId}
                    </a>
                  </td>
                  {isAuditor && <td>{orgName(a.organisationId) || a.organisationId}</td>}
                  <td className="num">{progressLabel(a.progress)}</td>
                  <td>
                    <span className={`badge ${a.status === 'active' ? 'badge-active' : 'badge-draft'}`}>
                      {STATUS_LABELS[a.status] || a.status}
                    </span>
                  </td>
                  <td>{formatDate(a.dueDate)}</td>
                  <td className="actions">
                    <a
                      href="#"
                      onClick={(e) => {
                        e.preventDefault();
                        navigate(`/assessments/${a._id}`);
                      }}
                    >
                      Open
                    </a>
                  </td>
                </tr>
              ))}
              {assessments.length === 0 && (
                <tr>
                  <td colSpan={isAuditor ? 6 : 5} className="muted">
                    No assessments yet.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
