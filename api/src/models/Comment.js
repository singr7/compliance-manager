import mongoose from 'mongoose';

const commentSchema = new mongoose.Schema(
  {
    assessmentResponseId: { type: mongoose.Schema.Types.ObjectId, ref: 'AssessmentResponse', required: true },
    authorId: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
    authorRole: { type: String, enum: ['auditor', 'customer_user'], required: true },
    text: { type: String, required: true, trim: true },
  },
  { timestamps: { createdAt: 'createdAt', updatedAt: false } }
);

commentSchema.index({ assessmentResponseId: 1, createdAt: 1 });

export const Comment = mongoose.model('Comment', commentSchema);
