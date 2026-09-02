import mongoose from 'mongoose';

const evidenceSchema = new mongoose.Schema(
  {
    assessmentResponseId: { type: mongoose.Schema.Types.ObjectId, ref: 'AssessmentResponse', required: true },
    originalFilename: { type: String, required: true },
    storageKey: { type: String, required: true },
    mimeType: { type: String, required: true },
    sizeBytes: { type: Number, required: true },
    uploadedBy: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
    description: { type: String, default: '' },
    isActive: { type: Boolean, default: true },
  },
  { timestamps: { createdAt: 'uploadedAt', updatedAt: false } }
);

evidenceSchema.index({ assessmentResponseId: 1 });

export const Evidence = mongoose.model('Evidence', evidenceSchema);
