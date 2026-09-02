import mongoose from 'mongoose';

const RESPONSE_TYPES = ['yes_no_na', 'short_text', 'long_text', 'file_required'];

const questionSchema = new mongoose.Schema(
  {
    text: { type: String, required: true, trim: true },
    controlRef: { type: String, trim: true, default: '' },
    guidance: { type: String, trim: true, default: '' },
    expectedEvidence: { type: String, trim: true, default: '' },
    required: { type: Boolean, default: true },
    responseType: { type: String, enum: RESPONSE_TYPES, required: true },
    enabled: { type: Boolean, default: true },
    order: { type: Number, required: true },
  },
  { _id: true, timestamps: false }
);

const sectionSchema = new mongoose.Schema(
  {
    title: { type: String, required: true, trim: true },
    order: { type: Number, required: true },
    questions: { type: [questionSchema], default: [] },
  },
  { _id: true, timestamps: false }
);

const checklistTemplateSchema = new mongoose.Schema(
  {
    name: { type: String, required: true, trim: true },
    category: { type: String, trim: true, default: '' },
    status: { type: String, enum: ['draft', 'active'], default: 'draft' },
    sections: { type: [sectionSchema], default: [] },
    createdBy: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  },
  { timestamps: true }
);

export const RESPONSE_TYPE_VALUES = RESPONSE_TYPES;
export const ChecklistTemplate = mongoose.model('ChecklistTemplate', checklistTemplateSchema);
