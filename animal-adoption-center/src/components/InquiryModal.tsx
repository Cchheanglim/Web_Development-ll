import React, { useState } from 'react';
import { X, Heart, Mail, Phone, CheckCircle2 } from 'lucide-react';
import { Pet, ShelterProfile } from '../types';

interface InquiryModalProps {
  pet: Pet | null;
  shelter: ShelterProfile;
  isOpen: boolean;
  onClose: () => void;
  onSuccessToast: (msg: string) => void;
}

export const InquiryModal: React.FC<InquiryModalProps> = ({
  pet,
  shelter,
  isOpen,
  onClose,
  onSuccessToast,
}) => {
  const [fullName, setFullName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [message, setMessage] = useState('');
  const [submitted, setSubmitted] = useState(false);

  if (!isOpen || !pet) return null;

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitted(true);
    setTimeout(() => {
      onSuccessToast(`Inquiry for ${pet.name} received! The shelter will reach out soon.`);
      setSubmitted(false);
      setFullName('');
      setEmail('');
      setPhone('');
      setMessage('');
      onClose();
    }, 1200);
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/60 backdrop-blur-xs p-4 overflow-y-auto animate-fade-in">
      <div className="relative w-full max-w-lg rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-6">
        {/* Close Button */}
        <button
          id="close-inquiry-modal"
          onClick={onClose}
          className="absolute right-5 top-5 rounded-full p-1.5 text-stone-400 hover:bg-stone-100 hover:text-stone-700 transition-colors"
        >
          <X className="h-5 w-5" />
        </button>

        {/* Modal Header */}
        <div className="flex items-center gap-4">
          <img
            src={pet.image_path}
            alt={pet.name}
            className="h-16 w-16 rounded-2xl object-cover border border-stone-200 shadow-xs"
          />
          <div>
            <span className="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
              Adoption Inquiry
            </span>
            <h3 className="text-xl font-extrabold text-stone-900 mt-0.5">
              Meet & Adopt {pet.name}
            </h3>
            <p className="text-xs text-stone-500">
              {pet.breed || pet.type} &bull; {pet.age} &bull; {pet.gender}
            </p>
          </div>
        </div>

        {submitted ? (
          <div className="py-8 text-center space-y-3">
            <CheckCircle2 className="mx-auto h-12 w-12 text-emerald-500 animate-bounce" />
            <h4 className="text-lg font-bold text-stone-900">Inquiry Sent Successfully!</h4>
            <p className="text-xs text-stone-600 max-w-xs mx-auto">
              Thank you for opening your heart to {pet.name}. Our adoption coordinators will review your message within 24 hours.
            </p>
          </div>
        ) : (
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-stone-700">
                Your Full Name *
              </label>
              <input
                type="text"
                required
                value={fullName}
                onChange={(e) => setFullName(e.target.value)}
                placeholder="Jane Doe"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600"
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label className="block text-xs font-bold text-stone-700">
                  Email Address *
                </label>
                <input
                  type="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="jane@example.com"
                  className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-stone-700">
                  Phone Number *
                </label>
                <input
                  type="tel"
                  required
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  placeholder="(555) 000-0000"
                  className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Tell us about your home & family experience *
              </label>
              <textarea
                rows={3}
                required
                value={message}
                onChange={(e) => setMessage(e.target.value)}
                placeholder={`Tell us why you think ${pet.name} would be a wonderful addition to your household, yard details, or other pets...`}
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600 leading-relaxed"
              />
            </div>

            <button
              type="submit"
              className="w-full rounded-xl bg-amber-600 hover:bg-amber-700 py-3 text-xs font-bold text-white shadow-md transition-all flex items-center justify-center gap-2"
            >
              <Heart className="h-4 w-4" />
              <span>Submit Adoption Inquiry</span>
            </button>
          </form>
        )}

        {/* Direct Contact Fallback */}
        <div className="pt-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-stone-500">
          <span className="font-medium">Direct Shelter Contact:</span>
          <div className="flex items-center gap-4">
            <a
              href={`mailto:${shelter.email}?subject=Inquiry for ${pet.name}`}
              className="inline-flex items-center gap-1 text-amber-700 hover:underline font-semibold"
            >
              <Mail className="h-3.5 w-3.5" />
              <span>Email</span>
            </a>
            <a
              href={`tel:${shelter.phone}`}
              className="inline-flex items-center gap-1 text-amber-700 hover:underline font-semibold"
            >
              <Phone className="h-3.5 w-3.5" />
              <span>{shelter.phone}</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  );
};
