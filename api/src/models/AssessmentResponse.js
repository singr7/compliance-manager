import mongoose from 'mongoose';

const assessmentResponseSchema = new mongoose.Schema(
  {
    assessmentId: { type: mongoose.Schema.Types.ObjectId, ref: 'Assessment', required: true },
    questionId: { type: mongoose.Schema.Types.ObjectId, required: true },
    sectionId: { type: mongoose.Schema.Types.ObjectId, required: true },
    questionTextSnapshot: { type: String, required: true },
    controlRefSnapshot: { type: String, default: '' },
    status: {
      type: String,
      enum: ['not_started', 'in_progress', 'submitted', 'needs_clarification', 'accepted', 'non_compliant'],
      default: 'not_started',
    },
    answer: {
      type: { type: String, default: null },
      value: { type: mongoose.Schema.Types.Mixed, default: null },
    },
    customerNote: { type: String, default: '' },
    submittedAt: { type: Date, default: null },
    reviewedAt: { type: Date, default: null },
    reviewedBy: { type: mongoose.Schema.Types.ObjectId, ref: 'User', default: null },
  },
  { timestamps: true }
);

assessmentResponseSchema.index({ assessmentId: 1, status: 1 });

export const AssessmentResponse = mongoose.model('AssessmentResponse', assessmentResponseSchema);
