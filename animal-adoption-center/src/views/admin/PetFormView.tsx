import React, { useState } from 'react';
import { ArrowLeft, Upload, Sparkles, Check, Image as ImageIcon } from 'lucide-react';
import { Pet, PetType, PetGender, PetStatus } from '../../types';

interface PetFormViewProps {
  initialPet?: Pet | null;
  onSave: (petData: Omit<Pet, 'id' | 'created_at' | 'updated_at'>) => void;
  onCancel: () => void;
}

export const PetFormView: React.FC<PetFormViewProps> = ({
  initialPet,
  onSave,
  onCancel,
}) => {
  const isEditing = Boolean(initialPet);

  const [name, setName] = useState(initialPet?.name || '');
  const [type, setType] = useState<PetType>(initialPet?.type || 'Dog');
  const [breed, setBreed] = useState(initialPet?.breed || '');
  const [age, setAge] = useState(initialPet?.age || '');
  const [gender, setGender] = useState<PetGender>(initialPet?.gender || 'Male');
  const [status, setStatus] = useState<PetStatus>(initialPet?.status || 'Available');
  const [description, setDescription] = useState(initialPet?.description || '');
  const [imagePath, setImagePath] = useState(
    initialPet?.image_path ||
      'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=800&q=80'
  );
  const [errors, setErrors] = useState<Record<string, string>>({});

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      if (file.size > 2 * 1024 * 1024) {
        setErrors((prev) => ({
          ...prev,
          image: 'The image may not be greater than 2048 kilobytes (2MB).',
        }));
        return;
      }
      const reader = new FileReader();
      reader.onload = (event) => {
        if (event.target?.result) {
          setImagePath(event.target.result as string);
          setErrors((prev) => {
            const next = { ...prev };
            delete next.image;
            return next;
          });
        }
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newErrors: Record<string, string> = {};

    if (!name.trim()) newErrors.name = 'The pet name field is required.';
    if (!age.trim()) newErrors.age = 'The pet age field is required.';
    if (!description.trim()) newErrors.description = 'The pet description/bio is required.';

    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors);
      return;
    }

    onSave({
      name: name.trim(),
      type,
      breed: breed.trim(),
      age: age.trim(),
      gender,
      status,
      description: description.trim(),
      image_path: imagePath,
    });
  };

  return (
    <div className="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
            {isEditing ? `Edit ${initialPet?.name}` : 'Register New Animal'}
          </h1>
          <p className="text-xs text-stone-500 mt-1">
            {isEditing
              ? 'Update details, adoption status, and photo in the central directory.'
              : 'Add an incoming rescue to the public adoption showcase.'}
          </p>
        </div>

        <button
          onClick={onCancel}
          className="inline-flex items-center gap-1.5 rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition-colors"
        >
          <ArrowLeft className="h-3.5 w-3.5" />
          <span>Back</span>
        </button>
      </div>

      <div className="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200/80 shadow-xs">
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Image Preview & Upload */}
          <div className="space-y-3">
            <label className="block text-xs font-bold text-stone-700">
              Pet Photography * (Strict max 2MB &bull; JPEG, PNG, WebP)
            </label>

            <div className="flex flex-col sm:flex-row items-center gap-5 rounded-2xl bg-stone-50 p-4 border border-stone-200">
              <div className="relative h-28 w-28 rounded-2xl overflow-hidden bg-stone-200 border border-stone-300 flex-shrink-0">
                <img
                  src={imagePath}
                  alt="Preview"
                  className="h-full w-full object-cover"
                />
              </div>

              <div className="flex-1 space-y-2 w-full">
                <input
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  onChange={handleFileChange}
                  className="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100"
                />
                <p className="text-[11px] text-stone-500">
                  Select a photo from your computer, or enter an image URL below.
                </p>
                <input
                  type="text"
                  value={imagePath}
                  onChange={(e) => setImagePath(e.target.value)}
                  placeholder="https://..."
                  className="w-full rounded-xl border border-stone-300 px-3 py-1.5 text-xs text-stone-700 focus:border-amber-600 focus:outline-none"
                />
                {errors.image && (
                  <p className="text-xs text-rose-600 font-semibold">{errors.image}</p>
                )}
              </div>
            </div>
          </div>

          {/* Name & Species */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-bold text-stone-700">
                Pet Name *
              </label>
              <input
                id="pet-form-name"
                type="text"
                required
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="e.g. Buster"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
              {errors.name && <p className="mt-1 text-xs text-rose-600">{errors.name}</p>}
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Species / Type *
              </label>
              <select
                id="pet-form-type"
                value={type}
                onChange={(e) => setType(e.target.value as PetType)}
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs font-semibold focus:border-amber-600 focus:outline-none"
              >
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Bird">Bird</option>
                <option value="Rabbit">Rabbit</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>

          {/* Breed, Age, Gender */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label className="block text-xs font-bold text-stone-700">
                Breed / Mix
              </label>
              <input
                type="text"
                value={breed}
                onChange={(e) => setBreed(e.target.value)}
                placeholder="e.g. Golden Retriever Mix"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Age *
              </label>
              <input
                type="text"
                required
                value={age}
                onChange={(e) => setAge(e.target.value)}
                placeholder="e.g. 2 years, 6 months"
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
              />
              {errors.age && <p className="mt-1 text-xs text-rose-600">{errors.age}</p>}
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Gender *
              </label>
              <select
                value={gender}
                onChange={(e) => setGender(e.target.value as PetGender)}
                className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs font-semibold focus:border-amber-600 focus:outline-none"
              >
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Unknown">Unknown</option>
              </select>
            </div>
          </div>

          {/* Status */}
          <div>
            <label className="block text-xs font-bold text-stone-700">
              Adoption Status *
            </label>
            <select
              value={status}
              onChange={(e) => setStatus(e.target.value as PetStatus)}
              className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs font-bold focus:border-amber-600 focus:outline-none"
            >
              <option value="Available">🟢 Available for Adoption</option>
              <option value="Pending">🟡 Pending Application</option>
              <option value="Adopted">⚪ Adopted (Forever Home)</option>
            </select>
          </div>

          {/* Description / Bio */}
          <div>
            <label className="block text-xs font-bold text-stone-700">
              Pet Bio & Personality Story *
            </label>
            <textarea
              rows={4}
              required
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              placeholder="Describe temperament, history, compatibilities with children/pets, training status..."
              className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none leading-relaxed"
            />
            {errors.description && (
              <p className="mt-1 text-xs text-rose-600">{errors.description}</p>
            )}
          </div>

          {/* Bottom Action Buttons */}
          <div className="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
            <button
              type="button"
              onClick={onCancel}
              className="rounded-xl border border-stone-300 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-50"
            >
              Cancel
            </button>
            <button
              id="save-pet-btn"
              type="submit"
              className="rounded-xl bg-amber-600 hover:bg-amber-700 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-amber-600/20 transition-colors flex items-center gap-1.5"
            >
              <Check className="h-4 w-4" />
              <span>{isEditing ? 'Update Animal Profile' : 'Publish Pet Profile'}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
