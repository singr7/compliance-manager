import dotenv from 'dotenv';

dotenv.config();

export const env = {
  port: process.env.PORT || 4000,
  mongoUri: process.env.MONGO_URI || 'mongodb://localhost:27017/compliance_manager',
  jwtSecret: process.env.JWT_SECRET || 'dev-secret-change-me',
  jwtExpiresIn: process.env.JWT_EXPIRES_IN || '8h',
  nodeEnv: process.env.NODE_ENV || 'development',

  storageBackend: process.env.STORAGE_BACKEND || 'filesystem', // 'filesystem' | 's3'
  evidenceStorageDir: process.env.EVIDENCE_STORAGE_DIR || './evidence-storage',
  evidenceMaxSizeBytes: Number(process.env.EVIDENCE_MAX_SIZE_BYTES) || 25 * 1024 * 1024,
  s3Bucket: process.env.S3_BUCKET || 'compliance-manager-evidence',
  s3Region: process.env.S3_REGION || 'us-east-1',
  s3Endpoint: process.env.S3_ENDPOINT || '',
  s3AccessKeyId: process.env.S3_ACCESS_KEY_ID || '',
  s3SecretAccessKey: process.env.S3_SECRET_ACCESS_KEY || '',

  // Auto-seed the reference checklist templates the moment the first auditor account is
  // created (see routes/auth.js bootstrap-admin) — on by default so `docker compose up`
  // plus one bootstrap call gives a working deployment with real content. Idempotent
  // either way (never overwrites an existing template), so this is safe to leave on.
  seedTemplatesOnBootstrap: process.env.SEED_TEMPLATES_ON_BOOTSTRAP !== 'false',
};
