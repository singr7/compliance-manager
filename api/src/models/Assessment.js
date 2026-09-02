import mongoose from 'mongoose';

const assessmentSchema = new mongoose.Schema(
  {
    templateId: { type: mongoose.Schema.Types.ObjectId, ref: 'ChecklistTemplate', required: true },
    organisationId: { type: mongoose.Schema.Types.ObjectId, ref: 'Organisation', required: true },
    assignedAuditorId: { type: mongoose.Schema.Types.ObjectId, ref: 'User', default: null },
    status: {
      type: String,
      enum: ['draft', 'active', 'under_review', 'completed'],
      default: 'draft',
    },
    dueDate: { type: Date, default: null },
    completedAt: { type: Date, default: null },
    createdBy: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  },
  { timestamps: true }
);

export const Assessment = mongoose.model('Assessment', assessmentSchema);
