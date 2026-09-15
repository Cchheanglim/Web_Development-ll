import React, { useState } from 'react';
import { ArrowLeft, Check, Home, Clock, Phone, Mail, MapPin, Image as ImageIcon } from 'lucide-react';
import { ShelterProfile } from '../../types';

interface ShelterProfileViewProps {
  shelter: ShelterProfile;
  onSave: (updated: ShelterProfile) => void;
  onCancel: () => void;
}

export const ShelterProfileView: React.FC<ShelterProfileViewProps> = ({
  shelter,
  onSave,
  onCancel,
}) => {
  const [shelterName, setShelterName] = useState(shelter.shelter_name);
  const [tagline, setTagline] = useState(shelter.tagline);
  const [bio, setBio] = useState(shelter.bio);
  const [phone, setPhone] = useState(shelter.phone);
  const [email, setEmail] = useState(shelter.email);
  const [address, setAddress] = useState(shelter.address);
  const [openingHours, setOpeningHours] = useState(shelter.opening_hours);
  const [bannerImagePath, setBannerImagePath] = useState(shelter.banner_image_path);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSave({
      ...shelter,
      shelter_name: shelterName.trim(),
      tagline: tagline.trim(),
      bio: bio.trim(),
      phone: phone.trim(),
      email: email.trim(),
      address: address.trim(),
      opening_hours: openingHours.trim(),
      banner_image_path: bannerImagePath.trim(),
      updated_at: new Date().toISOString(),
    });
  };

  const handleBannerFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        if (event.target?.result) {
          setBannerImagePath(event.target.result as string);
        }
      };
      reader.readAsDataURL(file);
    }
  };

  return (
    <div className="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
            Shelter Profile & Settings
          </h1>
          <p className="text-xs text-stone-500 mt-1">
            Update sanctuary identity, visiting hours, and public inquiry contact details.
          </p>
        </div>

        <button
          onClick={onCancel}
          className="inline-flex items-center gap-1.5 rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50"
        >
          <ArrowLeft className="h-3.5 w-3.5" />
          <span>Dashboard</span>
        </button>
      </div>

      <div className="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200/80 shadow-xs">
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Identity Section */}
          <div className="space-y-4">
            <h3 className="text-sm font-bold text-stone-900 border-b border-stone-100 pb-2">
              Organization Identity
            </h3>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Shelter / Sanctuary Name *
              </label>
              <input
                type="text"
                required
                value={shelterName}
                onChange={(e) => setShelterName(e.target.value)}
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Public Tagline / Slogan
              </label>
              <input
                type="text"
                value={tagline}
                onChange={(e) => setTagline(e.target.value)}
                placeholder="Connecting Loving Families with Pets in Need"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Shelter Bio & About Story
              </label>
              <textarea
                rows={4}
                value={bio}
                onChange={(e) => setBio(e.target.value)}
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none leading-relaxed"
              />
            </div>
          </div>

          {/* Contact & Visiting Hours */}
          <div className="space-y-4 pt-4 border-t border-stone-100">
            <h3 className="text-sm font-bold text-stone-900 border-b border-stone-100 pb-2">
              Public Contact & Visiting Hours
            </h3>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-stone-700">
                  Direct Phone Number
                </label>
                <input
                  type="text"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  placeholder="(555) 234-5678"
                  className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-stone-700">
                  Adoption Desk Email
                </label>
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="adoptions@havenpaws.org"
                  className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Sanctuary Physical Address
              </label>
              <input
                type="text"
                value={address}
                onChange={(e) => setAddress(e.target.value)}
                placeholder="742 Evergreen Terrace, Springfield, OR"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Opening & Visiting Hours
              </label>
              <input
                type="text"
                value={openingHours}
                onChange={(e) => setOpeningHours(e.target.value)}
                placeholder="Tuesday – Sunday: 10:00 AM – 5:30 PM (Closed Mondays)"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
            </div>
          </div>

          {/* Banner Image */}
          <div className="space-y-3 pt-4 border-t border-stone-100">
            <h3 className="text-sm font-bold text-stone-900 border-b border-stone-100 pb-2">
              Sanctuary Cover Banner
            </h3>

            {bannerImagePath && (
              <div className="h-32 w-full rounded-2xl overflow-hidden border border-stone-200">
                <img
                  src={bannerImagePath}
                  alt="Banner"
                  className="h-full w-full object-cover"
                />
              </div>
            )}

            <div className="space-y-2">
              <input
                type="file"
                accept="image/*"
                onChange={handleBannerFileChange}
                className="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100"
              />
              <input
                type="text"
                value={bannerImagePath}
                onChange={(e) => setBannerImagePath(e.target.value)}
                placeholder="Or paste an image URL here..."
                className="w-full rounded-xl border border-stone-300 px-3 py-1.5 text-xs text-stone-700 focus:border-amber-600 focus:outline-none"
              />
            </div>
          </div>

          {/* Submit */}
          <div className="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
            <button
              type="button"
              onClick={onCancel}
              className="rounded-xl border border-stone-300 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-50"
            >
              Cancel
            </button>
            <button
              id="save-shelter-settings-btn"
              type="submit"
              className="rounded-xl bg-amber-600 hover:bg-amber-700 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-amber-600/20 transition-colors flex items-center gap-1.5"
            >
              <Check className="h-4 w-4" />
              <span>Save Shelter Settings</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
