import React, { useState, useMemo } from 'react';
import { Search, Sparkles, Filter, HeartHandshake, Home, Clock, Phone, MapPin, CheckCircle2 } from 'lucide-react';
import { Pet, PetType, PetStatus, ShelterProfile } from '../types';
import { PetCard } from '../components/PetCard';

interface HomeViewProps {
  shelter: ShelterProfile;
  pets: Pet[];
  onViewPet: (pet: Pet) => void;
  onInquire: (pet: Pet) => void;
  onNavigate: (route: string) => void;
}

export const HomeView: React.FC<HomeViewProps> = ({
  shelter,
  pets,
  onViewPet,
  onInquire,
  onNavigate,
}) => {
  const [selectedType, setSelectedType] = useState<string>('All');
  const [selectedStatus, setSelectedStatus] = useState<string>('All');
  const [searchQuery, setSearchQuery] = useState<string>('');

  const petTypes: (string | PetType)[] = ['All', 'Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
  const statuses: (string | PetStatus)[] = ['All', 'Available', 'Pending', 'Adopted'];

  const filteredPets = useMemo(() => {
    return pets.filter((pet) => {
      const matchType = selectedType === 'All' || pet.type === selectedType;
      const matchStatus = selectedStatus === 'All' || pet.status === selectedStatus;
      const matchSearch =
        !searchQuery ||
        pet.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        pet.breed.toLowerCase().includes(searchQuery.toLowerCase()) ||
        pet.description.toLowerCase().includes(searchQuery.toLowerCase());
      return matchType && matchStatus && matchSearch;
    });
  }, [pets, selectedType, selectedStatus, searchQuery]);

  const availableCount = pets.filter((p) => p.status === 'Available').length;

  return (
    <div className="space-y-12 pb-16">
      {/* Hero & Shelter Bio Summary Banner */}
      <section className="relative overflow-hidden bg-gradient-to-b from-amber-100/60 via-amber-50/30 to-transparent py-12 md:py-18 border-b border-amber-900/5">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div className="lg:col-span-7 space-y-5">
              <div className="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3.5 py-1 text-xs font-bold text-amber-900 border border-amber-200">
                <span className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{shelter.tagline}</span>
              </div>

              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-stone-900 leading-[1.15]">
                Find your new <span className="text-amber-700 underline decoration-amber-300 decoration-wavy">best friend</span> today.
              </h1>

              <p className="text-base sm:text-lg text-stone-600 max-w-2xl leading-relaxed">
                {shelter.bio}
              </p>

              <div className="flex flex-wrap items-center gap-4 pt-2">
                <a
                  href="#browse-section"
                  className="rounded-2xl bg-stone-900 px-6 py-3.5 text-xs font-bold text-white shadow-md hover:bg-stone-800 transition-all flex items-center gap-2"
                >
                  <HeartHandshake className="h-4 w-4 text-amber-400" />
                  <span>Browse Available Pets ({availableCount})</span>
                </a>
                <button
                  onClick={() => onNavigate('/about')}
                  className="rounded-2xl border border-stone-300 bg-white px-6 py-3.5 text-xs font-bold text-stone-700 hover:bg-stone-50 transition-all"
                >
                  Visiting Hours & Story &rarr;
                </button>
              </div>
            </div>

            {/* Shelter Quick Highlights Card */}
            <div className="lg:col-span-5">
              <div className="rounded-3xl bg-white p-6 sm:p-7 shadow-xl shadow-stone-200/60 border border-stone-200/80 space-y-5">
                <div className="flex items-center gap-4 border-b border-stone-100 pb-4">
                  <div className="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-700 border border-amber-100 shadow-xs">
                    <Home className="h-6 w-6" />
                  </div>
                  <div>
                    <h3 className="font-extrabold text-stone-900 text-base leading-tight">
                      {shelter.shelter_name}
                    </h3>
                    <p className="text-xs text-stone-500 mt-0.5">
                      Welcoming visitors & adoption appointments
                    </p>
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-3 text-xs">
                  <div className="rounded-2xl bg-stone-50 p-3.5 border border-stone-100">
                    <span className="flex items-center gap-1 text-stone-400 font-semibold">
                      <Clock className="h-3.5 w-3.5 text-amber-600" />
                      Visiting Hours
                    </span>
                    <span className="block font-bold text-stone-800 mt-1">
                      {shelter.opening_hours.split('|')[0] || shelter.opening_hours}
                    </span>
                  </div>

                  <div className="rounded-2xl bg-stone-50 p-3.5 border border-stone-100">
                    <span className="flex items-center gap-1 text-stone-400 font-semibold">
                      <Phone className="h-3.5 w-3.5 text-amber-600" />
                      Inquiries
                    </span>
                    <span className="block font-bold text-stone-800 mt-1">
                      {shelter.phone}
                    </span>
                  </div>
                </div>

                <div className="rounded-2xl bg-emerald-50 border border-emerald-100 p-3.5 text-xs text-emerald-800 flex items-start gap-2.5">
                  <CheckCircle2 className="h-4 w-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span>
                    All animals receive comprehensive vet checkups, vaccinations, spay/neuter, and microchips before adoption.
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Pet Showcase Section */}
      <section id="browse-section" className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">
        {/* Controls Header: Search, Species Tabs, Status Filter */}
        <div className="space-y-6">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h2 className="text-2xl sm:text-3xl font-extrabold tracking-tight text-stone-900">
                Meet Our Companions
              </h2>
              <p className="text-xs sm:text-sm text-stone-500 mt-1">
                Filter by species, adoption status, or search by name and personality traits.
              </p>
            </div>

            {/* Search Bar */}
            <div className="relative w-full sm:w-72">
              <Search className="absolute left-3.5 top-2.5 h-4 w-4 text-stone-400" />
              <input
                type="text"
                id="search-pets-input"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Search name, breed, bio..."
                className="w-full rounded-2xl border border-stone-300 bg-white py-2 pl-10 pr-8 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600 shadow-xs"
              />
              {searchQuery && (
                <button
                  onClick={() => setSearchQuery('')}
                  className="absolute right-3 top-2.5 text-xs text-stone-400 hover:text-stone-700"
                >
                  &times;
                </button>
              )}
            </div>
          </div>

          {/* Species Tabs */}
          <div className="flex flex-wrap items-center gap-2 border-b border-stone-200 pb-3">
            {petTypes.map((type) => (
              <button
                key={type}
                id={`filter-type-${type}`}
                onClick={() => setSelectedType(type)}
                className={`rounded-2xl px-4 py-2 text-xs font-bold transition-all ${
                  selectedType === type
                    ? 'bg-amber-600 text-white shadow-sm shadow-amber-600/20'
                    : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'
                }`}
              >
                {type === 'All' && '🐾 All Species'}
                {type === 'Dog' && '🐕 Dogs'}
                {type === 'Cat' && '🐈 Cats'}
                {type === 'Bird' && '🦜 Birds'}
                {type === 'Rabbit' && '🐇 Rabbits'}
                {type === 'Other' && '🐾 Others'}
              </button>
            ))}
          </div>

          {/* Status Filters */}
          <div className="flex flex-wrap items-center gap-2 text-xs font-medium text-stone-500">
            <span className="font-semibold text-stone-700 flex items-center gap-1 mr-1">
              <Filter className="h-3 w-3" />
              Status:
            </span>
            {statuses.map((status) => (
              <button
                key={status}
                id={`filter-status-${status}`}
                onClick={() => setSelectedStatus(status)}
                className={`rounded-xl px-3 py-1 text-xs font-semibold transition-colors ${
                  selectedStatus === status
                    ? 'bg-stone-900 text-white'
                    : 'bg-stone-100 hover:bg-stone-200 text-stone-600'
                }`}
              >
                {status}
              </button>
            ))}
            {(selectedType !== 'All' || selectedStatus !== 'All' || searchQuery) && (
              <button
                onClick={() => {
                  setSelectedType('All');
                  setSelectedStatus('All');
                  setSearchQuery('');
                }}
                className="text-xs text-amber-700 hover:underline font-semibold ml-2"
              >
                Reset All Filters
              </button>
            )}
          </div>
        </div>

        {/* Pet Grid */}
        {filteredPets.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {filteredPets.map((pet) => (
              <PetCard
                key={pet.id}
                pet={pet}
                onViewPet={onViewPet}
                onInquire={onInquire}
              />
            ))}
          </div>
        ) : (
          <div className="rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center space-y-4">
            <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 text-2xl">
              🐾
            </div>
            <h3 className="text-lg font-bold text-stone-900">
              No companion animals matched your search
            </h3>
            <p className="text-xs text-stone-500 max-w-sm mx-auto">
              We couldn't find any pets matching "{searchQuery || selectedType}". Try adjusting your filters or clearing search terms.
            </p>
            <div>
              <button
                onClick={() => {
                  setSelectedType('All');
                  setSelectedStatus('All');
                  setSearchQuery('');
                }}
                className="rounded-xl bg-stone-900 px-4 py-2 text-xs font-bold text-white hover:bg-stone-800"
              >
                Reset Filters
              </button>
            </div>
          </div>
        )}
      </section>
    </div>
  );
};
