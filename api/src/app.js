import express from 'express';
import cors from 'cors';
import { authRouter } from './routes/auth.js';
import { organisationsRouter } from './routes/organisations.js';
import { usersRouter } from './routes/users.js';

export function createApp() {
  const app = express();

  app.use(cors());
  app.use(express.json());

  app.get('/health', (req, res) => res.json({ status: 'ok' }));

  app.use('/auth', authRouter);
  app.use('/organisations', organisationsRouter);
  app.use('/users', usersRouter);

  app.use((req, res) => res.status(404).json({ error: 'Not found' }));

  // eslint-disable-next-line no-unused-vars
  app.use((err, req, res, next) => {
    console.error(err.message);
    res.status(500).json({ error: 'Internal server error' });
  });

  return app;
}
