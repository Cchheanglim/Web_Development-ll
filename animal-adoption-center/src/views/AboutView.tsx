import React, { useState } from 'react';
import { Home, Clock, Phone, Mail, MapPin, Heart, Shield, CheckCircle2, Sparkles, Send } from 'lucide-react';
import { ShelterProfile } from '../types';

interface AboutViewProps {
  shelter: ShelterProfile;
  onNavigate: (route: string) => void;
  onSuccessToast: (msg: string) => void;
}

export const AboutView: React.FC<AboutViewProps> = ({
  shelter,
  onNavigate,
  onSuccessToast,
}) => {
  const [formName, setFormName] = useState('');
  const [formEmail, setFormEmail] = useState('');
  const [formMessage, setFormMessage] = useState('');
  const [sent, setSent] = useState(false);

  const handleContactSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSent(true);
    setTimeout(() => {
      onSuccessToast('Message sent! Our shelter coordinator will get back to you shortly.');
      setSent(false);
      setFormName('');
      setFormEmail('');
      setFormMessage('');
    }, 1000);
  };

  return (
    <div className="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8 space-y-12">
      {/* Title & Mission Header */}
      <div className="text-center space-y-4">
        <span className="inline-flex rounded-full bg-amber-100 px-3.5 py-1 text-xs font-bold text-amber-900 border border-amber-200">
          Our Sanctuary & Mission
        </span>
        <h1 className="text-3xl sm:text-5xl font-extrabold text-stone-900 tracking-tight">
          {shelter.shelter_name}
        </h1>
        <p className="text-base sm:text-lg text-stone-600 max-w-2xl mx-auto">
          {shelter.tagline}
        </p>
      </div>

      {/* Banner Picture */}
      {shelter.banner_image_path && (
        <div className="relative overflow-hidden rounded-3xl aspect-21/9 shadow-lg border border-stone-200">
          <img
            src={shelter.banner_image_path}
            alt={shelter.shelter_name}
            className="h-full w-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-stone-950/70 via-transparent to-transparent flex items-end p-6 sm:p-8">
            <p className="text-white text-sm sm:text-base font-medium max-w-xl">
              Giving every rescue companion animal dignity, loving shelter, and a pathway to a forever family.
            </p>
          </div>
        </div>
      )}

      {/* Details Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Main Story */}
        <div className="lg:col-span-7 rounded-3xl bg-white p-8 border border-stone-200/80 shadow-xs space-y-6">
          <h2 className="text-2xl font-extrabold text-stone-900">
            Our Story & Humane Commitment
          </h2>
          <div className="text-sm text-stone-600 leading-relaxed whitespace-pre-line">
            {shelter.bio}
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-stone-100">
            <div className="rounded-2xl bg-amber-50/70 p-4 border border-amber-100 space-y-1">
              <h4 className="font-bold text-stone-900 text-sm flex items-center gap-1.5">
                <Heart className="h-4 w-4 text-amber-600" />
                <span>Cage-Free Environment</span>
              </h4>
              <p className="text-xs text-stone-600 leading-relaxed">
                Spacious living spaces with indoor-outdoor access, climbing structures for cats, and play yards for dogs.
              </p>
            </div>

            <div className="rounded-2xl bg-amber-50/70 p-4 border border-amber-100 space-y-1">
              <h4 className="font-bold text-stone-900 text-sm flex items-center gap-1.5">
                <Shield className="h-4 w-4 text-amber-600" />
                <span>100% Medical Care</span>
              </h4>
              <p className="text-xs text-stone-600 leading-relaxed">
                Full vaccination protocols, microchipping, sterilization, and dental care included in every adoption.
              </p>
            </div>
          </div>

          {/* General Inquiry Form */}
          <div className="pt-6 border-t border-stone-100 space-y-4">
            <h3 className="text-base font-bold text-stone-900">
              Send a Note to Our Staff
            </h3>
            <form onSubmit={handleContactSubmit} className="space-y-3">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input
                  type="text"
                  required
                  placeholder="Your Name"
                  value={formName}
                  onChange={(e) => setFormName(e.target.value)}
                  className="rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
                />
                <input
                  type="email"
                  required
                  placeholder="Your Email"
                  value={formEmail}
                  onChange={(e) => setFormEmail(e.target.value)}
                  className="rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
                />
              </div>
              <textarea
                rows={3}
                required
                placeholder="How can we help? (Adoptions, volunteering, foster programs, donations)..."
                value={formMessage}
                onChange={(e) => setFormMessage(e.target.value)}
                className="w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none leading-relaxed"
              />
              <button
                type="submit"
                className="inline-flex items-center gap-2 rounded-xl bg-stone-900 hover:bg-stone-800 px-5 py-2.5 text-xs font-bold text-white transition-all shadow-xs"
              >
                <Send className="h-3.5 w-3.5 text-amber-400" />
                <span>Send Message</span>
              </button>
            </form>
          </div>
        </div>

        {/* Shelter Contact & Hours Sidebar */}
        <div className="lg:col-span-5 space-y-6">
          <div className="rounded-3xl bg-white p-6 sm:p-7 border border-stone-200 shadow-xs space-y-5">
            <h3 className="font-bold text-stone-900 text-base">
              Shelter Information & Hours
            </h3>

            <div className="space-y-4 text-xs">
              <div className="flex items-start gap-3">
                <Clock className="h-4 w-4 text-amber-600 mt-0.5 flex-shrink-0" />
                <div>
                  <span className="text-stone-400 font-semibold block uppercase tracking-wider text-[10px]">
                    Visiting & Adoption Hours
                  </span>
                  <span className="text-stone-800 font-bold block mt-0.5">
                    {shelter.opening_hours}
                  </span>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <MapPin className="h-4 w-4 text-amber-600 mt-0.5 flex-shrink-0" />
                <div>
                  <span className="text-stone-400 font-semibold block uppercase tracking-wider text-[10px]">
                    Sanctuary Address
                  </span>
                  <span className="text-stone-800 font-bold block mt-0.5">
                    {shelter.address}
                  </span>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <Mail className="h-4 w-4 text-amber-600 mt-0.5 flex-shrink-0" />
                <div>
                  <span className="text-stone-400 font-semibold block uppercase tracking-wider text-[10px]">
                    Adoption Desk Email
                  </span>
                  <a
                    href={`mailto:${shelter.email}`}
                    className="text-amber-700 font-bold block mt-0.5 hover:underline"
                  >
                    {shelter.email}
                  </a>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <Phone className="h-4 w-4 text-amber-600 mt-0.5 flex-shrink-0" />
                <div>
                  <span className="text-stone-400 font-semibold block uppercase tracking-wider text-[10px]">
                    Direct Phone Line
                  </span>
                  <a
                    href={`tel:${shelter.phone}`}
                    className="text-amber-700 font-bold block mt-0.5 hover:underline"
                  >
                    {shelter.phone}
                  </a>
                </div>
              </div>
            </div>

            <div className="pt-4 border-t border-stone-100">
              <button
                id="browse-from-about-btn"
                onClick={() => onNavigate('/')}
                className="w-full rounded-2xl bg-amber-600 hover:bg-amber-700 py-3 text-xs font-bold text-white shadow-md transition-colors"
              >
                Browse All Pets Waiting For Homes &rarr;
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
