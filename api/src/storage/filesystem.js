import fs from 'fs/promises';
import path from 'path';
import { env } from '../config/env.js';

// Both backends key files as (orgId, assessmentId, questionId, evidenceId) so a filename
// alone never leaks which org/assessment it belongs to and there is no collision risk.
export function buildStorageKey({ organisationId, assessmentId, questionId, evidenceId }) {
  return `${organisationId}/${assessmentId}/${questionId}/${evidenceId}`;
}

export class FilesystemStorage {
  constructor(rootDir = env.evidenceStorageDir) {
    this.rootDir = rootDir;
  }

  #resolve(storageKey) {
    const resolved = path.resolve(this.rootDir, storageKey);
    if (!resolved.startsWith(path.resolve(this.rootDir))) {
      throw new Error('Invalid storage key');
    }
    return resolved;
  }

  async put(storageKey, buffer) {
    const filePath = this.#resolve(storageKey);
    await fs.mkdir(path.dirname(filePath), { recursive: true });
    await fs.writeFile(filePath, buffer);
  }

  async get(storageKey) {
    return fs.readFile(this.#resolve(storageKey));
  }

  async delete(storageKey) {
    await fs.rm(this.#resolve(storageKey), { force: true });
  }
}
