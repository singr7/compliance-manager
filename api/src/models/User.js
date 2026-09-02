import mongoose from 'mongoose';

const userSchema = new mongoose.Schema(
  {
    fullName: { type: String, required: true, trim: true },
    email: { type: String, required: true, trim: true, lowercase: true, unique: true },
    phoneNumber: { type: String, trim: true },
    passwordHash: { type: String, required: true },
    role: { type: String, enum: ['auditor', 'customer_user'], required: true },
    organisationId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'Organisation',
      default: null,
    },
    status: { type: String, enum: ['active', 'inactive'], default: 'active' },
    lastLoginAt: { type: Date, default: null },
  },
  { timestamps: true }
);

// customer_user must belong to an organisation; auditor must not.
userSchema.pre('validate', function enforceOrgScoping(next) {
  if (this.role === 'customer_user' && !this.organisationId) {
    return next(new Error('organisationId is required for role customer_user'));
  }
  if (this.role === 'auditor' && this.organisationId) {
    return next(new Error('organisationId must be null for role auditor'));
  }
  next();
});

export const User = mongoose.model('User', userSchema);
