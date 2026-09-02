import { createApp } from './app.js';
import { connectDb } from './db.js';
import { env } from './config/env.js';
import { logger } from './utils/logger.js';
import { ensureIndexes } from './migrate.js';

async function main() {
  await connectDb();
  await ensureIndexes();
  const app = createApp();
  app.listen(env.port, () => {
    logger.info('server_started', { port: env.port, storageBackend: env.storageBackend });
  });
}

main().catch((err) => {
  logger.error('server_start_failed', { message: err.message });
  process.exit(1);
});
