import { MongoMemoryServer } from 'mongodb-memory-server';
import { connectDb, disconnectDb } from '../src/db.js';
import mongoose from 'mongoose';

let mongod;

export async function startTestDb() {
  mongod = await MongoMemoryServer.create();
  await connectDb(mongod.getUri());
}

export async function stopTestDb() {
  await disconnectDb();
  if (mongod) await mongod.stop();
}

export async function clearTestDb() {
  const collections = mongoose.connection.collections;
  for (const key of Object.keys(collections)) {
    await collections[key].deleteMany({});
  }
}
