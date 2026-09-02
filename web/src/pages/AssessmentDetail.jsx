import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { useAuth } from '../lib/AuthContext.jsx';
import { api } from '../lib/api.js';

const STATUS_LABELS = {
  not_started: 'Not started',
  in_progress: 'In progress',
  submitted: 'Submitted',
  needs_clarification: 'Needs clarification',
  accepted: 'Accepted',
  non_compliant: 'Non-compliant',
};

function statusBadgeClass(status) {
  if (status === 'accepted') return 'badge-active';
  return 'badge-draft';
}

function AnswerField({ responseType, value, onChange }) {
  if (responseType === 'yes_no_na') {
    return (
      <select value={value ?? ''} onChange={(e) => onChange(e.target.value)}>
        <option value="" disabled>
          Select an answer
        </option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
        <option value="na">N/A</option>
      </select>
    );
  }
  if (responseType === 'long_text') {
    return <textarea value={value ?? ''} onChange={(e) => onChange(e.target.value)} rows={5} />;
  }
  if (responseType === 'file_required') {
    return (
      <p className="muted" style={{ fontSize: 12.5 }}>
        Evidence upload is not available yet in this build.
      </p>
    );
  }
  return <input value={value ?? ''} onChange={(e) => onChange(e.target.value)} />;
}

function QuestionDrawer({ response, onClose, onSaved, canEdit }) {
  const question = response.question || {};
  const [value, setValue] = useState(response.answer?.value ?? '');
  const [customerNote, setCustomerNote] = useState(response.customerNote || '');
  const [error, setError] = useState('');
  const [saving, setSaving] = useState(false);

  async function handleSave(submit) {
    setError('');
    setSaving(true);
    try {
      await onSaved({
        answer: { type: question.responseType, value },
        customerNote,
        submit,
      });
      onClose();
    } catch (err) {
      setError(err.message);
    } finally {
      setSaving(false);
    }
  }

  return (
    <div className="dialog-overlay" onClick={onClose}>
      <div className="dialog" onClick={(e) => e.stopPropagation()} style={{ width: 520 }}>
        <div style={{ display: 'flex', alignItems: 'baseline', gap: 10, marginBottom: 4 }}>
          {response.controlRefSnapshot && (
            <span
              style={{
                fontFamily: 'var(--mono)',
                fontSize: 11.5,
                color: 'var(--accent-text)',
                background: 'var(--accent-bg)',
                padding: '1px 7px',
                borderRadius: 2,
              }}
            >
              {response.controlRefSnapshot}
            </span>
          )}
          <span className={`badge ${statusBadgeClass(response.status)}`}>
            {STATUS_LABELS[response.status] || response.status}
          </span>
        </div>
        <h2 style={{ marginTop: 8 }}>{response.questionTextSnapshot}</h2>
        {question.guidance && (
          <p className="muted" style={{ fontSize: 12.5, marginTop: -4 }}>
            Guidance: {question.guidance}
          </p>
        )}

        <div style={{ display: 'flex', flexDirection: 'column', gap: 12, marginTop: 12 }}>
          <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
            Answer{question.required ? ' (required)' : ''}
            <AnswerField responseType={question.responseType} value={value} onChange={setValue} />
          </label>
          <label style={{ display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13 }}>
            Note (optional)
            <textarea value={customerNote} onChange={(e) => setCustomerNote(e.target.value)} rows={2} />
          </label>
          {error && <p className="error">{error}</p>}
          <div className="dialog-actions">
            <button type="button" className="secondary" onClick={onClose}>
              Close
            </button>
            {canEdit && (
              <>
                <button type="button" className="secondary" disabled={saving} onClick={() => handleSave(false)}>
                  {saving ? 'Saving…' : 'Save draft'}
                </button>
                <button type="button" disabled={saving} onClick={() => handleSave(true)}>
                  {saving ? 'Submitting…' : 'Submit'}
                </button>
              </>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

export default function AssessmentDetail() {
  const { id } = useParams();
  const { user } = useAuth();
  const isCustomer = user.role === 'customer_user';

  const [assessment, setAssessment] = useState(null);
  const [responses, setResponses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [openResponseId, setOpenResponseId] = useState(null);

  async function refresh() {
    try {
      const [a, r] = await Promise.all([api.getAssessment(id), api.listResponses(id)]);
      setAssessment(a);
      setResponses(r);
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

  async function handleResponseSaved(responseId, payload) {
    const updated = await api.saveResponse(id, responseId, payload);
    setResponses((prev) => prev.map((r) => (r._id === updated._id ? updated : r)));
    setAssessment(await api.getAssessment(id));
    return updated;
  }

  if (loading) return <p className="muted">Loading…</p>;
  if (error && !assessment) return <p className="error">{error}</p>;
  if (!assessment) return null;

  const total = assessment.progress?.total || 0;
  const counts = assessment.progress?.counts || {};
  const done = Object.entries(counts)
    .filter(([status]) => status !== 'not_started')
    .reduce((sum, [, count]) => sum + count, 0);
  const pct = total > 0 ? Math.round((done / total) * 100) : 0;

  const sections = [];
  const sectionsById = {};
  for (const r of responses) {
    const key = String(r.sectionId);
    if (!sectionsById[key]) {
      sectionsById[key] = { sectionId: key, title: r.sectionTitle, order: r.sectionOrder ?? 0, responses: [] };
      sections.push(sectionsById[key]);
    }
    sectionsById[key].responses.push(r);
  }
  sections.sort((a, b) => a.order - b.order);

  const openResponse = responses.find((r) => r._id === openResponseId);

  return (
    <div className="page">
      <div style={{ fontSize: 12, color: 'var(--text-muted)', marginBottom: 14 }}>
        <Link to="/assessments">Assessments</Link>
      </div>

      <div className="card" style={{ marginBottom: 24 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: 24 }}>
          <div>
            <div className="field-label">Status</div>
            <div style={{ fontFamily: 'var(--serif)', fontSize: 22, fontWeight: 600, textTransform: 'capitalize' }}>
              {assessment.status.replace('_', ' ')}
            </div>
          </div>
          <div style={{ textAlign: 'right' }}>
            <div className="field-label">Progress</div>
            <div style={{ fontFamily: 'var(--mono)', fontSize: 15 }}>
              {done}/{total} ({pct}%)
            </div>
          </div>
        </div>
        <div
          style={{
            marginTop: 14,
            height: 6,
            borderRadius: 3,
            background: 'var(--surface-subtle)',
            overflow: 'hidden',
          }}
        >
          <div
            style={{
              width: `${pct}%`,
              height: '100%',
              background: 'var(--accent-text)',
              transition: 'width 0.2s ease',
            }}
          />
        </div>
      </div>

      {error && <p className="error">{error}</p>}

      {sections.map((section, sIdx) => (
        <div key={section.sectionId} style={{ marginBottom: 20 }}>
          <div style={{ display: 'flex', alignItems: 'baseline', gap: 10, marginBottom: 10, padding: '0 2px' }}>
            <span style={{ fontFamily: 'var(--mono)', fontSize: 11.5, color: 'var(--text-faint)' }}>
              {String(sIdx + 1).padStart(2, '0')}
            </span>
            <h2 style={{ fontSize: 15, fontWeight: 600, margin: 0 }}>{section.title || 'Section'}</h2>
            <span style={{ fontSize: 12, color: 'var(--text-muted)' }}>
              {section.responses.length} question{section.responses.length === 1 ? '' : 's'}
            </span>
          </div>
          <div className="table-card">
            {section.responses.map((r, rIdx) => (
              <div
                key={r._id}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: 16,
                  padding: '14px 20px',
                  borderBottom: rIdx === section.responses.length - 1 ? 'none' : '1px solid var(--border-subtle)',
                  cursor: 'pointer',
                }}
                onClick={() => setOpenResponseId(r._id)}
              >
                <div style={{ flex: 1, minWidth: 0 }}>
                  {r.controlRefSnapshot && (
                    <span
                      style={{
                        fontFamily: 'var(--mono)',
                        fontSize: 11.5,
                        color: 'var(--accent-text)',
                        background: 'var(--accent-bg)',
                        padding: '1px 7px',
                        borderRadius: 2,
                        marginRight: 8,
                      }}
                    >
                      {r.controlRefSnapshot}
                    </span>
                  )}
                  <span style={{ fontSize: 13.5 }}>{r.questionTextSnapshot}</span>
                </div>
                <span className={`badge ${statusBadgeClass(r.status)}`}>{STATUS_LABELS[r.status] || r.status}</span>
              </div>
            ))}
          </div>
        </div>
      ))}

      {sections.length === 0 && <p className="muted">No questions on this assessment.</p>}

      {openResponse && (
        <QuestionDrawer
          response={openResponse}
          canEdit={isCustomer}
          onClose={() => setOpenResponseId(null)}
          onSaved={(payload) => handleResponseSaved(openResponse._id, payload)}
        />
      )}
    </div>
  );
}
