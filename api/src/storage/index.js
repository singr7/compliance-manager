// Pluggable evidence storage abstraction. Callers only ever see storageKey — never a
// filesystem path or bucket name — so swapping STORAGE_BACKEND requires no caller changes.
import { env } from '../config/env.js';
import { FilesystemStorage } from './filesystem.js';
import { S3Storage } from './s3.js';

let instance = null;

export function getStorage() {
  if (instance) return instance;
  instance = env.storageBackend === 's3' ? new S3Storage() : new FilesystemStorage();
  return instance;
}
