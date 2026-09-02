import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../lib/AuthContext.jsx';
import { api } from '../lib/api.js';

function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}

function Stat({ label, value }) {
  return (
    <div>
      <div className="field-label">{label}</div>
      <div style={{ fontFamily: 'var(--mono)', fontSize: 22, fontWeight: 500 }}>{value}</div>
    </div>
  );
}

function ListCard({ title, emptyLabel, items, renderItem }) {
  return (
    <div style={{ marginBottom: 20 }}>
      <h2 style={{ fontSize: 15, fontWeight: 600, margin: '0 0 10px', padding: '0 2px' }}>{title}</h2>
      <div className="table-card">
        {items.length === 0 ? (
          <div style={{ padding: '16px 20px', fontSize: 13, color: 'var(--text-muted)' }}>{emptyLabel}</div>
        ) : (
          items.map((item, idx) => (
            <div
              key={idx}
              style={{
                padding: '12px 20px',
                borderBottom: idx === items.length - 1 ? 'none' : '1px solid var(--border-subtle)',
              }}
            >
              {renderItem(item)}
            </div>
          ))
        )}
      </div>
    </div>
  );
}

function AuditorDashboard({ data }) {
  const navigate = useNavigate();
  return (
    <>
      <div className="card" style={{ marginBottom: 24, display: 'flex', gap: 40 }}>
        <Stat label="Active assessments" value={data.activeAssessmentCount} />
        <Stat label="Controls needing review" value={data.controlsNeedingReview} />
        <Stat label="Customers needing attention" value={data.customersNeedingAttention.length} />
        <Stat label="Behind schedule" value={data.behindSchedule.length} />
      </div>

      <ListCard
        title="Customers needing attention"
        emptyLabel="No customers currently have responses awaiting review."
        items={data.customersNeedingAttention}
        renderItem={(c) => (
          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5 }}>
            <span>{c.organisationName}</span>
            <span className="muted">{c.submittedCount} awaiting review</span>
          </div>
        )}
      />

      <ListCard
        title="Recent submissions"
        emptyLabel="No submissions yet."
        items={data.recentSubmissions}
        renderItem={(s) => (
          <div
            style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5, cursor: 'pointer' }}
            onClick={() => navigate(`/assessments/${s.assessmentId}`)}
          >
            <span>
              {s.organisationName} — {s.questionText}
            </span>
            <span className="muted">{formatDate(s.submittedAt)}</span>
          </div>
        )}
      />

      <ListCard
        title="Behind schedule"
        emptyLabel="No active assessments are past their due date."
        items={data.behindSchedule}
        renderItem={(b) => (
          <div
            style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5, cursor: 'pointer' }}
            onClick={() => navigate(`/assessments/${b.assessmentId}`)}
          >
            <span>{b.organisationName}</span>
            <span style={{ color: 'oklch(48% 0.09 40)' }}>Due {formatDate(b.dueDate)}</span>
          </div>
        )}
      />
    </>
  );
}

function CustomerDashboard({ data }) {
  const navigate = useNavigate();
  return (
    <>
      <ListCard
        title="Active assessments"
        emptyLabel="No active assessments yet."
        items={data.activeAssessments}
        renderItem={(a) => (
          <div
            style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5, cursor: 'pointer' }}
            onClick={() => navigate(`/assessments/${a._id}`)}
          >
            <span>{a.templateName}</span>
            <span className="muted">
              {a.progress.done}/{a.progress.total} ({a.progress.pctComplete}%)
            </span>
          </div>
        )}
      />

      <ListCard
        title="Needs your attention"
        emptyLabel="Nothing needs your attention right now."
        items={data.needsMyAttention}
        renderItem={(item) => (
          <div
            style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5, cursor: 'pointer' }}
            onClick={() => navigate(`/assessments/${item.assessmentId}`)}
          >
            <span>
              {item.templateName} — {item.questionText}
            </span>
            <span className="badge badge-warn">Needs clarification</span>
          </div>
        )}
      />

      <ListCard
        title="Awaiting auditor"
        emptyLabel="Nothing is currently waiting on the auditor."
        items={data.awaitingAuditor}
        renderItem={(item) => (
          <div
            style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5, cursor: 'pointer' }}
            onClick={() => navigate(`/assessments/${item.assessmentId}`)}
          >
            <span>
              {item.templateName} — {item.questionText}
            </span>
            <span className="muted">Submitted</span>
          </div>
        )}
      />

      <ListCard
        title="Due soon"
        emptyLabel="Nothing is due in the next 7 days."
        items={data.dueSoon}
        renderItem={(a) => (
          <div
            style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13.5, cursor: 'pointer' }}
            onClick={() => navigate(`/assessments/${a.assessmentId}`)}
          >
            <span>{a.templateName}</span>
            <span className="muted">Due {formatDate(a.dueDate)}</span>
          </div>
        )}
      />
    </>
  );
}

export default function Dashboard() {
  const { user } = useAuth();
  const [data, setData] = useState(null);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);

  async function refresh() {
    try {
      setData(await api.getDashboard());
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    refresh();
    const interval = setInterval(refresh, 30000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div className="page">
      <div className="page-header">
        <div>
          <div className="page-eyebrow">Dashboard</div>
          <h1>Welcome, {user.fullName}</h1>
        </div>
      </div>

      {error && <p className="error">{error}</p>}
      {loading ? (
        <p className="muted">Loading…</p>
      ) : data.role === 'auditor' ? (
        <AuditorDashboard data={data} />
      ) : (
        <CustomerDashboard data={data} />
      )}
    </div>
  );
}
