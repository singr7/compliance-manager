import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { api } from '../lib/api.js';

const STATUS_FILTERS = [
  { key: 'all', label: 'All' },
  { key: 'active', label: 'Active' },
  { key: 'draft', label: 'Draft' },
];

function formatDate(iso) {
  return new Date(iso).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

function countQuestions(template) {
  return template.sections.reduce((sum, s) => sum + s.questions.length, 0);
}

export default function Templates() {
  const navigate = useNavigate();
  const [templates, setTemplates] = useState([]);
  const [filter, setFilter] = useState('all');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [creating, setCreating] = useState(false);
  const [name, setName] = useState('');
  const [category, setCategory] = useState('');

  async function refresh() {
    setLoading(true);
    try {
      setTemplates(await api.listTemplates());
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
      const template = await api.createTemplate({ name, category });
      navigate(`/templates/${template._id}`);
    } catch (err) {
      setError(err.message);
    }
  }

  async function handleDuplicate(id, sourceName) {
    setError('');
    try {
      const copy = await api.duplicateTemplate(id, `${sourceName} (copy)`);
      await refresh();
      navigate(`/templates/${copy._id}`);
    } catch (err) {
      setError(err.message);
    }
  }

  async function handleToggleStatus(template) {
    setError('');
    try {
      await api.updateTemplate(template._id, {
        status: template.status === 'active' ? 'draft' : 'active',
      });
      await refresh();
    } catch (err) {
      setError(err.message);
    }
  }

  const visible = templates.filter((t) => filter === 'all' || t.status === filter);

  return (
    <div className="page">
      <div className="page-header">
        <div>
          <div className="page-eyebrow">Templates</div>
          <h1>Checklist Templates</h1>
        </div>
        <button onClick={() => setCreating(true)}>New Template</button>
      </div>
      <p className="page-lede">
        Templates define the questions an assessment asks. Activate a template to make it
        available for new assessments; existing assessments keep the questions they were created
        with.
      </p>

      {creating && (
        <div className="dialog-overlay" onClick={() => setCreating(false)}>
          <div className="dialog" onClick={(e) => e.stopPropagation()}>
            <h2>New template</h2>
            <form onSubmit={handleCreate} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
                Name
                <input value={name} onChange={(e) => setName(e.target.value)} required autoFocus />
              </label>
              <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
                Category
                <input value={category} onChange={(e) => setCategory(e.target.value)} placeholder="e.g. Payment Security" />
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

      <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 16 }}>
        <div style={{ display: 'flex', gap: 6 }}>
          {STATUS_FILTERS.map((f) => (
            <div
              key={f.key}
              onClick={() => setFilter(f.key)}
              style={{
                padding: '6px 13px',
                fontSize: 12.5,
                fontWeight: 500,
                borderRadius: 3,
                cursor: 'pointer',
                background: filter === f.key ? 'var(--accent)' : 'transparent',
                color: filter === f.key ? 'white' : 'var(--text-muted)',
              }}
            >
              {f.label}
            </div>
          ))}
        </div>
        <div style={{ marginLeft: 'auto', fontSize: 12.5, color: 'var(--text-muted)' }}>
          {visible.length} template{visible.length === 1 ? '' : 's'}
        </div>
      </div>

      {error && <p className="error">{error}</p>}

      {loading ? (
        <p className="muted">Loading…</p>
      ) : (
        <div className="table-card">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Category</th>
                <th style={{ textAlign: 'center' }}>Sections</th>
                <th style={{ textAlign: 'center' }}>Questions</th>
                <th>Status</th>
                <th>Updated</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {visible.map((t) => (
                <tr key={t._id}>
                  <td>
                    <a href={`#/templates/${t._id}`} onClick={(e) => { e.preventDefault(); navigate(`/templates/${t._id}`); }}>
                      {t.name}
                    </a>
                  </td>
                  <td>{t.category || '—'}</td>
                  <td className="num">{t.sections.length}</td>
                  <td className="num">{countQuestions(t)}</td>
                  <td>
                    <span className={`badge ${t.status === 'active' ? 'badge-active' : 'badge-draft'}`}>
                      {t.status === 'active' ? 'Active' : 'Draft'}
                    </span>
                  </td>
                  <td>{formatDate(t.updatedAt)}</td>
                  <td className="actions">
                    <a href="#" onClick={(e) => { e.preventDefault(); navigate(`/templates/${t._id}`); }}>
                      Edit
                    </a>
                    <a href="#" onClick={(e) => { e.preventDefault(); handleDuplicate(t._id, t.name); }}>
                      Duplicate
                    </a>
                    <a href="#" onClick={(e) => { e.preventDefault(); handleToggleStatus(t); }}>
                      {t.status === 'active' ? 'Deactivate' : 'Activate'}
                    </a>
                  </td>
                </tr>
              ))}
              {visible.length === 0 && (
                <tr>
                  <td colSpan={7} className="muted">
                    No templates yet.
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
