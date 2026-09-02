import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { api } from '../lib/api.js';

const RESPONSE_TYPE_LABELS = {
  yes_no_na: 'Yes / No / N-A',
  short_text: 'Short text',
  long_text: 'Long text',
  file_required: 'File required',
};

function QuestionForm({ initial, onCancel, onSubmit }) {
  const [text, setText] = useState(initial?.text || '');
  const [controlRef, setControlRef] = useState(initial?.controlRef || '');
  const [guidance, setGuidance] = useState(initial?.guidance || '');
  const [expectedEvidence, setExpectedEvidence] = useState(initial?.expectedEvidence || '');
  const [required, setRequired] = useState(initial?.required ?? true);
  const [responseType, setResponseType] = useState(initial?.responseType || 'yes_no_na');
  const [error, setError] = useState('');
  const [saving, setSaving] = useState(false);

  async function handleSubmit(e) {
    e.preventDefault();
    setError('');
    setSaving(true);
    try {
      await onSubmit({ text, controlRef, guidance, expectedEvidence, required, responseType });
    } catch (err) {
      setError(err.message);
    } finally {
      setSaving(false);
    }
  }

  return (
    <div className="dialog-overlay" onClick={onCancel}>
      <div className="dialog" onClick={(e) => e.stopPropagation()}>
        <h2>{initial ? 'Edit question' : 'Add question'}</h2>
        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
          <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
            Question text
            <textarea value={text} onChange={(e) => setText(e.target.value)} required autoFocus />
          </label>
          <div style={{ display: 'flex', gap: 12 }}>
            <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13, flex: 1 }}>
              Control reference
              <input value={controlRef} onChange={(e) => setControlRef(e.target.value)} placeholder="e.g. 1.1.a" />
            </label>
            <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13, flex: 1 }}>
              Response type
              <select value={responseType} onChange={(e) => setResponseType(e.target.value)}>
                {Object.entries(RESPONSE_TYPE_LABELS).map(([value, label]) => (
                  <option key={value} value={value}>
                    {label}
                  </option>
                ))}
              </select>
            </label>
          </div>
          <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
            Guidance
            <textarea value={guidance} onChange={(e) => setGuidance(e.target.value)} />
          </label>
          <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
            Expected evidence
            <textarea value={expectedEvidence} onChange={(e) => setExpectedEvidence(e.target.value)} />
          </label>
          <label className="checkbox-row">
            <input type="checkbox" checked={required} onChange={(e) => setRequired(e.target.checked)} />
            Required
          </label>
          {error && <p className="error">{error}</p>}
          <div className="dialog-actions">
            <button type="button" className="secondary" onClick={onCancel}>
              Cancel
            </button>
            <button type="submit" disabled={saving}>
              {saving ? 'Saving…' : 'Save question'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default function TemplateEditor() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [template, setTemplate] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [addingSection, setAddingSection] = useState(false);
  const [sectionTitle, setSectionTitle] = useState('');
  const [questionDialog, setQuestionDialog] = useState(null); // { sectionId, question? }

  async function refresh() {
    try {
      setTemplate(await api.getTemplate(id));
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    refresh();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [id]);

  async function handleAddSection(e) {
    e.preventDefault();
    setError('');
    try {
      await api.addSection(id, sectionTitle);
      setSectionTitle('');
      setAddingSection(false);
      await refresh();
    } catch (err) {
      setError(err.message);
    }
  }

  async function handleToggleStatus() {
    setError('');
    try {
      await api.updateTemplate(id, { status: template.status === 'active' ? 'draft' : 'active' });
      await refresh();
    } catch (err) {
      setError(err.message);
    }
  }

  async function handleDuplicate() {
    setError('');
    try {
      const copy = await api.duplicateTemplate(id, `${template.name} (copy)`);
      navigate(`/templates/${copy._id}`);
    } catch (err) {
      setError(err.message);
    }
  }

  async function handleQuestionSubmit(sectionId, question, payload) {
    if (question) {
      await api.updateQuestion(id, sectionId, question._id, payload);
    } else {
      await api.addQuestion(id, sectionId, payload);
    }
    setQuestionDialog(null);
    await refresh();
  }

  async function handleToggleQuestion(sectionId, question) {
    setError('');
    try {
      await api.updateQuestion(id, sectionId, question._id, { enabled: !question.enabled });
      await refresh();
    } catch (err) {
      setError(err.message);
    }
  }

  if (loading) return <p className="muted">Loading…</p>;
  if (error && !template) return <p className="error">{error}</p>;
  if (!template) return null;

  const totalQuestions = template.sections.reduce((sum, s) => sum + s.questions.length, 0);

  return (
    <div className="page">
      <div style={{ fontSize: 12, color: 'var(--text-muted)', marginBottom: 14 }}>
        <Link to="/templates">Templates</Link> &nbsp;/&nbsp;{' '}
        <span style={{ color: 'var(--text)' }}>{template.name}</span>
      </div>

      <div className="card" style={{ marginBottom: 24 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 24 }}>
          <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: 14 }}>
            <div>
              <div className="field-label">Template name</div>
              <div style={{ fontFamily: 'var(--serif)', fontSize: 22, fontWeight: 600 }}>{template.name}</div>
            </div>
            <div style={{ display: 'flex', gap: 36 }}>
              <div>
                <div className="field-label">Category</div>
                <div style={{ fontSize: 13.5 }}>{template.category || '—'}</div>
              </div>
              <div>
                <div className="field-label">Status</div>
                <span className={`badge ${template.status === 'active' ? 'badge-active' : 'badge-draft'}`}>
                  {template.status === 'active' ? 'Active' : 'Draft'}
                </span>
              </div>
              <div>
                <div className="field-label">Contents</div>
                <div style={{ fontSize: 13.5, fontFamily: 'var(--mono)' }}>
                  {template.sections.length} section{template.sections.length === 1 ? '' : 's'} ·{' '}
                  {totalQuestions} question{totalQuestions === 1 ? '' : 's'}
                </div>
              </div>
            </div>
          </div>
          <div style={{ display: 'flex', gap: 10, flexShrink: 0 }}>
            <button className="secondary" onClick={handleDuplicate}>
              Duplicate
            </button>
            <button onClick={handleToggleStatus}>
              {template.status === 'active' ? 'Deactivate' : 'Activate'}
            </button>
          </div>
        </div>
      </div>

      {error && <p className="error">{error}</p>}

      {template.sections.map((section, sIdx) => (
        <div key={section._id} style={{ marginBottom: 20 }}>
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              marginBottom: 10,
              padding: '0 2px',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'baseline', gap: 10 }}>
              <span style={{ fontFamily: 'var(--mono)', fontSize: 11.5, color: 'var(--text-faint)' }}>
                {String(sIdx + 1).padStart(2, '0')}
              </span>
              <h2 style={{ fontSize: 15, fontWeight: 600, margin: 0 }}>{section.title}</h2>
              <span style={{ fontSize: 12, color: 'var(--text-muted)' }}>
                {section.questions.length} question{section.questions.length === 1 ? '' : 's'}
              </span>
            </div>
            <a
              href="#"
              onClick={(e) => {
                e.preventDefault();
                setQuestionDialog({ sectionId: section._id, question: null });
              }}
            >
              + Add question
            </a>
          </div>

          <div className="table-card">
            {section.questions.map((q, qIdx) => (
              <div
                key={q._id}
                style={{
                  display: 'flex',
                  gap: 16,
                  padding: '16px 20px',
                  borderBottom:
                    qIdx === section.questions.length - 1 ? 'none' : '1px solid var(--border-subtle)',
                  background: q.enabled ? 'transparent' : 'var(--surface-muted)',
                }}
              >
                <div style={{ flex: 1, minWidth: 0 }}>
                  <div style={{ display: 'flex', alignItems: 'baseline', gap: 10, marginBottom: 4 }}>
                    {q.controlRef && (
                      <span
                        style={{
                          fontFamily: 'var(--mono)',
                          fontSize: 11.5,
                          color: q.enabled ? 'var(--accent-text)' : 'var(--text-faint)',
                          background: q.enabled ? 'var(--accent-bg)' : 'var(--surface-subtle)',
                          padding: '1px 7px',
                          borderRadius: 2,
                        }}
                      >
                        {q.controlRef}
                      </span>
                    )}
                    <span
                      style={{
                        fontSize: 11,
                        fontWeight: 500,
                        letterSpacing: '0.02em',
                        color: !q.enabled
                          ? 'var(--text-faint)'
                          : q.required
                            ? 'oklch(48% 0.09 40)'
                            : 'var(--text-muted)',
                      }}
                    >
                      {!q.enabled ? 'DISABLED' : q.required ? 'REQUIRED' : 'OPTIONAL'}
                    </span>
                  </div>
                  <div
                    style={{
                      fontSize: 13.5,
                      color: q.enabled ? 'var(--text)' : 'var(--text-muted)',
                      lineHeight: 1.5,
                      marginBottom: q.guidance ? 5 : 0,
                    }}
                  >
                    {q.text}
                  </div>
                  {q.guidance && q.enabled && (
                    <div style={{ fontSize: 12, color: 'var(--text-muted)', lineHeight: 1.5 }}>
                      Guidance: {q.guidance}
                      {q.expectedEvidence && ` Evidence: ${q.expectedEvidence}`}
                    </div>
                  )}
                </div>
                <div style={{ flexShrink: 0, width: 130, textAlign: 'right' }}>
                  <span style={{ fontSize: 12, color: 'var(--text-muted)' }}>
                    {RESPONSE_TYPE_LABELS[q.responseType]}
                  </span>
                </div>
                <div style={{ flexShrink: 0, display: 'flex', gap: 12, paddingTop: 1 }}>
                  {q.enabled ? (
                    <>
                      <a
                        href="#"
                        onClick={(e) => {
                          e.preventDefault();
                          setQuestionDialog({ sectionId: section._id, question: q });
                        }}
                      >
                        Edit
                      </a>
                      <a
                        href="#"
                        style={{ color: 'var(--text-muted)' }}
                        onClick={(e) => {
                          e.preventDefault();
                          handleToggleQuestion(section._id, q);
                        }}
                      >
                        Disable
                      </a>
                    </>
                  ) : (
                    <a
                      href="#"
                      onClick={(e) => {
                        e.preventDefault();
                        handleToggleQuestion(section._id, q);
                      }}
                    >
                      Re-enable
                    </a>
                  )}
                </div>
              </div>
            ))}
            {section.questions.length === 0 && (
              <div style={{ padding: '16px 20px', fontSize: 13, color: 'var(--text-muted)' }}>
                No questions yet.
              </div>
            )}
          </div>
        </div>
      ))}

      {template.sections.length === 0 && (
        <p className="muted" style={{ marginBottom: 20 }}>
          Add a section to get started.
        </p>
      )}

      {addingSection ? (
        <form
          onSubmit={handleAddSection}
          style={{
            display: 'flex',
            gap: 10,
            border: '1px solid var(--border)',
            borderRadius: 4,
            padding: 14,
            background: 'var(--surface)',
          }}
        >
          <input
            value={sectionTitle}
            onChange={(e) => setSectionTitle(e.target.value)}
            placeholder="Section title"
            required
            autoFocus
            style={{ flex: 1 }}
          />
          <button type="submit">Add</button>
          <button type="button" className="secondary" onClick={() => setAddingSection(false)}>
            Cancel
          </button>
        </form>
      ) : (
        <button
          onClick={() => setAddingSection(true)}
          style={{
            width: '100%',
            background: 'var(--surface)',
            border: '1px dashed oklch(80% 0.008 85)',
            color: 'var(--text-muted)',
            padding: 16,
          }}
        >
          + Add section
        </button>
      )}

      {questionDialog && (
        <QuestionForm
          initial={questionDialog.question}
          onCancel={() => setQuestionDialog(null)}
          onSubmit={(payload) =>
            handleQuestionSubmit(questionDialog.sectionId, questionDialog.question, payload)
          }
        />
      )}
    </div>
  );
}
