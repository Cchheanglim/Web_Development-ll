import React from 'react';
import { Sparkles, Calendar, HeartHandshake } from 'lucide-react';
import { Pet } from '../types';

interface PetCardProps {
  pet: Pet;
  onViewPet: (pet: Pet) => void;
  onInquire: (pet: Pet) => void;
}

export const PetCard: React.FC<PetCardProps> = ({
  pet,
  onViewPet,
  onInquire,
}) => {
  const getStatusBadge = () => {
    switch (pet.status) {
      case 'Available':
        return (
          <span className="inline-flex items-center gap-1 rounded-full bg-emerald-500/95 backdrop-blur-xs px-3 py-1 text-xs font-bold text-white shadow-xs">
            <span className="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
            <span>Available</span>
          </span>
        );
      case 'Pending':
        return (
          <span className="inline-flex items-center gap-1 rounded-full bg-amber-500/95 backdrop-blur-xs px-3 py-1 text-xs font-bold text-white shadow-xs">
            <span className="h-1.5 w-1.5 rounded-full bg-white"></span>
            <span>Pending</span>
          </span>
        );
      case 'Adopted':
        return (
          <span className="inline-flex items-center gap-1 rounded-full bg-stone-500/95 backdrop-blur-xs px-3 py-1 text-xs font-bold text-white shadow-xs">
            <span>Adopted</span>
          </span>
        );
      default:
        return null;
    }
  };

  return (
    <div className="group flex flex-col overflow-hidden rounded-3xl bg-white border border-stone-200/90 shadow-xs hover:shadow-xl hover:border-amber-200 transition-all duration-300">
      {/* Pet Photo Container */}
      <div className="relative aspect-4/3 w-full overflow-hidden bg-stone-100">
        <img
          src={pet.image_path}
          alt={pet.name}
          className="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
          onError={(e) => {
            // Fallback image if broken
            (e.target as HTMLImageElement).src =
              'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=600&q=80';
          }}
        />

        {/* Top Badges */}
        <div className="absolute top-3 right-3 z-10">
          {getStatusBadge()}
        </div>

        <div className="absolute bottom-3 left-3 z-10 flex items-center gap-1.5">
          <span className="rounded-lg bg-stone-950/70 backdrop-blur-md px-2.5 py-1 text-[11px] font-semibold text-white shadow-xs">
            {pet.type}
          </span>
          <span className="rounded-lg bg-stone-950/70 backdrop-blur-md px-2.5 py-1 text-[11px] font-medium text-stone-200 shadow-xs">
            {pet.gender}
          </span>
        </div>
      </div>

      {/* Card Information */}
      <div className="flex flex-1 flex-col justify-between p-5 space-y-4">
        <div>
          <div className="flex items-start justify-between gap-2">
            <h3
              onClick={() => onViewPet(pet)}
              className="text-xl font-extrabold text-stone-900 group-hover:text-amber-700 transition-colors cursor-pointer"
            >
              {pet.name}
            </h3>
            <span className="inline-flex items-center gap-1 text-xs font-semibold text-stone-500 bg-stone-100 px-2.5 py-0.5 rounded-full mt-1">
              <Calendar className="h-3 w-3 text-stone-400" />
              {pet.age}
            </span>
          </div>

          <p className="text-xs font-semibold text-amber-800 mt-1">
            {pet.breed || 'Rescue Mix'}
          </p>

          <p className="text-xs text-stone-600 line-clamp-2 mt-2 leading-relaxed">
            {pet.description}
          </p>
        </div>

        {/* Card Bottom Buttons */}
        <div className="flex items-center gap-2 pt-2 border-t border-stone-100">
          <button
            id={`view-pet-${pet.id}`}
            onClick={() => onViewPet(pet)}
            className="flex-1 rounded-xl bg-stone-100 hover:bg-stone-200 py-2.5 text-center text-xs font-bold text-stone-700 transition-colors"
          >
            View Details
          </button>

          {pet.status === 'Available' ? (
            <button
              id={`inquire-pet-${pet.id}`}
              onClick={() => onInquire(pet)}
              className="flex-1 rounded-xl bg-amber-600 hover:bg-amber-700 py-2.5 text-center text-xs font-bold text-white shadow-xs transition-colors flex items-center justify-center gap-1"
            >
              <HeartHandshake className="h-3.5 w-3.5" />
              <span>Adopt {pet.name}</span>
            </button>
          ) : (
            <button
              disabled
              className="flex-1 rounded-xl bg-stone-100 py-2.5 text-center text-xs font-medium text-stone-400 cursor-not-allowed"
            >
              {pet.status}
            </button>
          )}
        </div>
      </div>
    </div>
  );
};
