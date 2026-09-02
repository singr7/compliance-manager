import { S3Client, PutObjectCommand, GetObjectCommand, DeleteObjectCommand } from '@aws-sdk/client-s3';
import { env } from '../config/env.js';

function streamToBuffer(stream) {
  return new Promise((resolve, reject) => {
    const chunks = [];
    stream.on('data', (chunk) => chunks.push(chunk));
    stream.on('error', reject);
    stream.on('end', () => resolve(Buffer.concat(chunks)));
  });
}

export class S3Storage {
  constructor() {
    this.bucket = env.s3Bucket;
    this.client = new S3Client({
      region: env.s3Region,
      endpoint: env.s3Endpoint || undefined,
      forcePathStyle: Boolean(env.s3Endpoint),
      credentials:
        env.s3AccessKeyId && env.s3SecretAccessKey
          ? { accessKeyId: env.s3AccessKeyId, secretAccessKey: env.s3SecretAccessKey }
          : undefined,
    });
  }

  async put(storageKey, buffer) {
    await this.client.send(
      new PutObjectCommand({ Bucket: this.bucket, Key: storageKey, Body: buffer })
    );
  }

  async get(storageKey) {
    const res = await this.client.send(
      new GetObjectCommand({ Bucket: this.bucket, Key: storageKey })
    );
    return streamToBuffer(res.Body);
  }

  async delete(storageKey) {
    await this.client.send(new DeleteObjectCommand({ Bucket: this.bucket, Key: storageKey }));
  }
}
