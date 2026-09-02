import { verifyToken } from '../utils/jwt.js';

export function requireAuth(req, res, next) {
  const header = req.headers.authorization || '';
  const [scheme, token] = header.split(' ');
  if (scheme !== 'Bearer' || !token) {
    return res.status(401).json({ error: 'Missing or malformed Authorization header' });
  }
  try {
    req.auth = verifyToken(token);
    next();
  } catch {
    res.status(401).json({ error: 'Invalid or expired token' });
  }
}

export function requireRole(...roles) {
  return (req, res, next) => {
    if (!req.auth || !roles.includes(req.auth.role)) {
      return res.status(403).json({ error: 'Forbidden' });
    }
    next();
  };
}

// A customer_user may only ever act within their own organisation.
// This never trusts a client-supplied organisationId for that role.
export function resolveOrgScope(req, res, next) {
  if (req.auth.role === 'customer_user') {
    req.scopedOrganisationId = req.auth.organisationId;
  } else {
    req.scopedOrganisationId = req.query.organisationId || null;
  }
  next();
}
