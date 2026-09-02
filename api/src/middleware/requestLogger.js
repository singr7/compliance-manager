import { logger } from '../utils/logger.js';

export function requestLogger(req, res, next) {
  if (process.env.NODE_ENV === 'test') return next();
  const start = Date.now();
  res.on('finish', () => {
    logger.info('request', {
      method: req.method,
      path: req.originalUrl.split('?')[0],
      status: res.statusCode,
      durationMs: Date.now() - start,
    });
  });
  next();
}
