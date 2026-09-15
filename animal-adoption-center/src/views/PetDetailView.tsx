import React from 'react';
import { ArrowLeft, Calendar, Heart, ShieldCheck, Mail, Phone, MapPin, Sparkles, CheckCircle2 } from 'lucide-react';
import { Pet, ShelterProfile } from '../types';

interface PetDetailViewProps {
  pet: Pet;
  shelter: ShelterProfile;
  onBack: () => void;
  onInquire: (pet: Pet) => void;
  onSelectOtherPet: (pet: Pet) => void;
  otherPets: Pet[];
}

export const PetDetailView: React.FC<PetDetailViewProps> = ({
  pet,
  shelter,
  onBack,
  onInquire,
  onSelectOtherPet,
  otherPets,
}) => {
  const getStatusBadge = () => {
    switch (pet.status) {
      case 'Available':
        return (
          <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-4 py-1.5 text-xs font-extrabold text-white shadow-md">
            <span className="h-2 w-2 rounded-full bg-white animate-pulse"></span>
            <span>Available for Adoption</span>
          </span>
        );
      case 'Pending':
        return (
          <span className="inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-4 py-1.5 text-xs font-extrabold text-white shadow-md">
            <span>Application Pending</span>
          </span>
        );
      case 'Adopted':
        return (
          <span className="inline-flex items-center gap-1.5 rounded-full bg-stone-600 px-4 py-1.5 text-xs font-extrabold text-white shadow-md">
            <span>Happily Adopted!</span>
          </span>
        );
      default:
        return null;
    }
  };

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
      {/* Breadcrumb / Back button */}
      <div className="flex items-center justify-between">
        <button
          id="back-to-browse-btn"
          onClick={onBack}
          className="inline-flex items-center gap-2 rounded-xl bg-white border border-stone-200 px-3.5 py-1.5 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition-colors shadow-xs"
        >
          <ArrowLeft className="h-3.5 w-3.5" />
          <span>Back to All Pets</span>
        </button>

        <span className="text-xs text-stone-400 font-mono">
          Pet ID: #{pet.id} &bull; Registered {new Date(pet.created_at).toLocaleDateString()}
        </span>
      </div>

      {/* Main Pet Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {/* Left Column: Image, Attributes, Description */}
        <div className="lg:col-span-7 space-y-6">
          <div className="relative overflow-hidden rounded-3xl bg-stone-100 shadow-md border border-stone-200 aspect-4/3">
            <img
              src={pet.image_path}
              alt={pet.name}
              className="h-full w-full object-cover"
              onError={(e) => {
                (e.target as HTMLImageElement).src =
                  'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=600&q=80';
              }}
            />
            <div className="absolute top-4 right-4 z-10">
              {getStatusBadge()}
            </div>
            <div className="absolute bottom-4 left-4 z-10">
              <span className="rounded-xl bg-black/60 backdrop-blur-md px-3 py-1.5 text-xs font-bold text-white shadow-xs">
                {pet.type} &bull; {pet.gender}
              </span>
            </div>
          </div>

          {/* Pet Key Attributes Matrix */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <div className="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
              <span className="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">
                Species
              </span>
              <p className="text-base font-extrabold text-stone-900 mt-0.5">{pet.type}</p>
            </div>
            <div className="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
              <span className="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">
                Age
              </span>
              <p className="text-base font-extrabold text-stone-900 mt-0.5">{pet.age}</p>
            </div>
            <div className="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
              <span className="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">
                Gender
              </span>
              <p className="text-base font-extrabold text-stone-900 mt-0.5">{pet.gender}</p>
            </div>
            <div className="rounded-2xl bg-white p-4 border border-stone-200/80 shadow-xs">
              <span className="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">
                Breed
              </span>
              <p className="text-base font-extrabold text-stone-900 mt-0.5 truncate">
                {pet.breed || 'Rescue Mix'}
              </p>
            </div>
          </div>

          {/* Story & Bio */}
          <div className="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-4">
            <h2 className="text-2xl font-extrabold text-stone-900">
              Meet {pet.name}
            </h2>
            <div className="text-sm text-stone-600 leading-relaxed whitespace-pre-line">
              {pet.description}
            </div>

            {/* Health & Guarantee Badges */}
            <div className="pt-6 border-t border-stone-100">
              <h4 className="text-xs font-bold uppercase tracking-wider text-stone-400 mb-3">
                Shelter Care & Medical Guarantee
              </h4>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs text-stone-700">
                <div className="flex items-center gap-2 rounded-xl bg-amber-50/60 p-2.5 border border-amber-100">
                  <CheckCircle2 className="h-4 w-4 text-emerald-600 flex-shrink-0" />
                  <span>Spayed / Neutered & Microchipped</span>
                </div>
                <div className="flex items-center gap-2 rounded-xl bg-amber-50/60 p-2.5 border border-amber-100">
                  <CheckCircle2 className="h-4 w-4 text-emerald-600 flex-shrink-0" />
                  <span>Core Vaccines Complete & Documented</span>
                </div>
                <div className="flex items-center gap-2 rounded-xl bg-amber-50/60 p-2.5 border border-amber-100">
                  <CheckCircle2 className="h-4 w-4 text-emerald-600 flex-shrink-0" />
                  <span>Comprehensive Physical & Dental Exam</span>
                </div>
                <div className="flex items-center gap-2 rounded-xl bg-amber-50/60 p-2.5 border border-amber-100">
                  <CheckCircle2 className="h-4 w-4 text-emerald-600 flex-shrink-0" />
                  <span>Behavioral Evaluation & Socialization</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Right Column: Inquire Action & Shelter Summary */}
        <div className="lg:col-span-5 space-y-6">
          <div className="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200 shadow-md space-y-6">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="text-2xl font-extrabold text-stone-900">
                  Adopt {pet.name}
                </h3>
                <p className="text-xs text-stone-500 mt-1">
                  Ready to welcome {pet.name} into your home?
                </p>
              </div>
              <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 text-2xl">
                🐾
              </span>
            </div>

            {pet.status === 'Available' ? (
              <div className="space-y-4">
                <p className="text-xs text-stone-600 leading-relaxed">
                  Submit an inquiry online or contact our center to arrange a personal meet-and-greet with our adoption specialists.
                </p>

                <button
                  id="adopt-inquire-main-btn"
                  onClick={() => onInquire(pet)}
                  className="w-full rounded-2xl bg-amber-600 hover:bg-amber-700 py-3.5 text-sm font-bold text-white shadow-md shadow-amber-600/20 transition-all flex items-center justify-center gap-2"
                >
                  <Heart className="h-4 w-4 fill-white" />
                  <span>Inquire / Schedule Meet & Greet</span>
                </button>

                {/* Quick Contact Links */}
                <div className="pt-4 border-t border-stone-100 space-y-2.5 text-xs">
                  <div className="flex items-center justify-between py-1">
                    <span className="text-stone-500">Adoption Coordinator:</span>
                    <a
                      href={`mailto:${shelter.email}?subject=Adoption Inquiry: ${pet.name}`}
                      className="font-semibold text-amber-700 hover:underline flex items-center gap-1"
                    >
                      <Mail className="h-3.5 w-3.5" />
                      <span>{shelter.email}</span>
                    </a>
                  </div>
                  <div className="flex items-center justify-between py-1">
                    <span className="text-stone-500">Direct Phone:</span>
                    <a
                      href={`tel:${shelter.phone}`}
                      className="font-semibold text-amber-700 hover:underline flex items-center gap-1"
                    >
                      <Phone className="h-3.5 w-3.5" />
                      <span>{shelter.phone}</span>
                    </a>
                  </div>
                </div>
              </div>
            ) : (
              <div className="rounded-2xl bg-stone-50 p-6 text-center border border-stone-200 space-y-2">
                <span className="text-2xl">❤️</span>
                <p className="text-sm font-bold text-stone-800">
                  {pet.name} is currently {pet.status}.
                </p>
                <p className="text-xs text-stone-500">
                  {pet.status === 'Pending'
                    ? 'An application has been approved and a meet-and-greet is scheduled.'
                    : 'This lucky animal has found their forever family!'}
                </p>
              </div>
            )}
          </div>

          {/* Shelter Visiting Location Card */}
          <div className="rounded-3xl bg-stone-50 p-6 border border-stone-200 space-y-3.5 text-xs text-stone-600">
            <h4 className="font-bold text-stone-900 text-sm flex items-center gap-1.5">
              <MapPin className="h-4 w-4 text-amber-600" />
              <span>Adoption Center Facility</span>
            </h4>
            <p className="font-medium text-stone-800">{shelter.shelter_name}</p>
            <p>{shelter.address}</p>
            <div className="pt-2 border-t border-stone-200">
              <strong className="text-stone-800">Visiting Hours: </strong>
              <span>{shelter.opening_hours}</span>
            </div>
          </div>

          {/* Similar Pets Available */}
          {otherPets.length > 0 && (
            <div className="rounded-3xl bg-white p-6 border border-stone-200 shadow-xs space-y-4">
              <h4 className="font-bold text-stone-900 text-sm">
                Other Animals Ready For Homes
              </h4>
              <div className="space-y-3">
                {otherPets.map((op) => (
                  <div
                    key={op.id}
                    onClick={() => onSelectOtherPet(op)}
                    className="flex items-center gap-3 p-2 rounded-2xl hover:bg-stone-50 cursor-pointer transition-colors border border-transparent hover:border-stone-200"
                  >
                    <img
                      src={op.image_path}
                      alt={op.name}
                      className="h-12 w-12 rounded-xl object-cover"
                    />
                    <div className="flex-1 min-w-0">
                      <p className="font-bold text-stone-900 text-xs truncate">{op.name}</p>
                      <p className="text-[11px] text-stone-500 truncate">
                        {op.type} &bull; {op.age} &bull; {op.gender}
                      </p>
                    </div>
                    <span className="text-xs font-semibold text-amber-700">&rarr;</span>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
