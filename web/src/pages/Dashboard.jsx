import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../lib/AuthContext.jsx';
import { api } from '../lib/api.js';

function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}

// Status buckets, reusing the app's existing token families only (no new colors):
// not-yet-started work (neutral), awaiting the auditor (accent), needs attention (warn),
// and accepted (active/green). Two raw statuses fold into "needs attention" and two into
// "not yet submitted" so the chart stays at 4 legible segments instead of 6.
const STATUS_BUCKETS = [
  { key: 'notStarted', label: 'Not yet submitted', fill: 'var(--border)', text: 'var(--text-muted)', statuses: ['not_started', 'in_progress'] },
  { key: 'submitted', label: 'Awaiting auditor', fill: 'var(--accent-text)', text: 'var(--accent-text)', statuses: ['submitted'] },
  { key: 'needsAttention', label: 'Needs attention', fill: 'var(--warn-text)', text: 'var(--warn-text)', statuses: ['needs_clarification', 'non_compliant'] },
  { key: 'accepted', label: 'Accepted', fill: 'var(--active-text)', text: 'var(--active-text)', statuses: ['accepted'] },
];

function bucketize(statusCounts = {}) {
  return STATUS_BUCKETS.map((b) => ({
    ...b,
    count: b.statuses.reduce((sum, s) => sum + (statusCounts[s] || 0), 0),
  }));
}

function ProgressRing({ pct, size = 56 }) {
  const stroke = 6;
  const r = (size - stroke) / 2;
  const circumference = 2 * Math.PI * r;
  const offset = circumference * (1 - pct / 100);
  return (
    <div style={{ position: 'relative', width: size, height: size, flexShrink: 0 }}>
      <svg width={size} height={size} style={{ transform: 'rotate(-90deg)' }}>
        <circle cx={size / 2} cy={size / 2} r={r} fill="none" stroke="var(--surface-subtle)" strokeWidth={stroke} />
        <circle
          cx={size / 2}
          cy={size / 2}
          r={r}
          fill="none"
          stroke="var(--accent-text)"
          strokeWidth={stroke}
          strokeDasharray={circumference}
          strokeDashoffset={offset}
          strokeLinecap="round"
          style={{ transition: 'stroke-dashoffset 0.3s ease' }}
        />
      </svg>
      <div
        style={{
          position: 'absolute',
          inset: 0,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          fontFamily: 'var(--mono)',
          fontSize: size < 50 ? 11 : 12.5,
          fontWeight: 500,
        }}
      >
        {pct}%
      </div>
    </div>
  );
}

function StatusLegend({ buckets }) {
  return (
    <div style={{ display: 'flex', gap: 18, flexWrap: 'wrap' }}>
      {buckets.map((b) => (
        <div key={b.key} style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12 }}>
          <span style={{ width: 9, height: 9, borderRadius: 2, background: b.fill, flexShrink: 0 }} />
          <span style={{ color: 'var(--text-muted)' }}>{b.label}</span>
          <span style={{ fontFamily: 'var(--mono)', color: 'var(--text)' }}>{b.count}</span>
        </div>
      ))}
    </div>
  );
}

function StatusBreakdownBar({ buckets, total }) {
  return (
    <div>
      <div
        style={{
          display: 'flex',
          height: 10,
          borderRadius: 3,
          overflow: 'hidden',
          background: 'var(--surface-subtle)',
          marginBottom: 12,
        }}
      >
        {buckets.map((b) =>
          b.count > 0 ? (
            <div
              key={b.key}
              title={`${b.label}: ${b.count} of ${total}`}
              style={{
                width: `${(b.count / total) * 100}%`,
                background: b.fill,
                borderRight: '2px solid var(--surface)',
              }}
            />
          ) : null
        )}
      </div>
      <StatusLegend buckets={buckets} />
    </div>
  );
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
  const buckets = bucketize(data.statusCounts);
  const pctReviewed = data.totalControls > 0
    ? Math.round(((data.statusCounts?.accepted || 0) / data.totalControls) * 100)
    : 0;

  return (
    <>
      <div className="card" style={{ marginBottom: 24, display: 'flex', gap: 28, alignItems: 'center' }}>
        <ProgressRing pct={pctReviewed} size={64} />
        <div style={{ display: 'flex', gap: 40, flex: 1 }}>
          <Stat label="Active assessments" value={data.activeAssessmentCount} />
          <Stat label="Controls needing review" value={data.controlsNeedingReview} />
          <Stat label="Customers needing attention" value={data.customersNeedingAttention.length} />
          <Stat label="Behind schedule" value={data.behindSchedule.length} />
        </div>
      </div>

      {data.totalControls > 0 && (
        <div className="card" style={{ marginBottom: 24 }}>
          <div className="field-label" style={{ marginBottom: 10 }}>
            Controls across all active assessments ({data.totalControls})
          </div>
          <StatusBreakdownBar buckets={buckets} total={data.totalControls} />
        </div>
      )}

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
            <span style={{ color: 'var(--warn-text)' }}>Due {formatDate(b.dueDate)}</span>
          </div>
        )}
      />
    </>
  );
}

function CustomerDashboard({ data }) {
  const navigate = useNavigate();
  const buckets = bucketize(data.statusCounts);
  const overallPct = data.totalControls > 0
    ? Math.round((((data.statusCounts?.accepted || 0)) / data.totalControls) * 100)
    : 0;

  return (
    <>
      {data.totalControls > 0 && (
        <div className="card" style={{ marginBottom: 24, display: 'flex', gap: 28, alignItems: 'center' }}>
          <ProgressRing pct={overallPct} size={64} />
          <div style={{ flex: 1 }}>
            <div className="field-label" style={{ marginBottom: 10 }}>
              Accepted across all active assessments ({data.totalControls} controls)
            </div>
            <StatusBreakdownBar buckets={buckets} total={data.totalControls} />
          </div>
        </div>
      )}

      <div style={{ marginBottom: 20 }}>
        <h2 style={{ fontSize: 15, fontWeight: 600, margin: '0 0 10px', padding: '0 2px' }}>Active assessments</h2>
        <div className="table-card">
          {data.activeAssessments.length === 0 ? (
            <div style={{ padding: '16px 20px', fontSize: 13, color: 'var(--text-muted)' }}>
              No active assessments yet.
            </div>
          ) : (
            data.activeAssessments.map((a, idx) => (
              <div
                key={a._id}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: 14,
                  padding: '10px 20px',
                  borderBottom: idx === data.activeAssessments.length - 1 ? 'none' : '1px solid var(--border-subtle)',
                  cursor: 'pointer',
                }}
                onClick={() => navigate(`/assessments/${a._id}`)}
              >
                <ProgressRing pct={a.progress.pctComplete} size={38} />
                <div style={{ flex: 1, fontSize: 13.5 }}>{a.templateName}</div>
                <span className="muted" style={{ fontFamily: 'var(--mono)', fontSize: 12.5 }}>
                  {a.progress.done}/{a.progress.total}
                </span>
              </div>
            ))
          )}
        </div>
      </div>

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
