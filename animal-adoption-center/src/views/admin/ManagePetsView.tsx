import React, { useState, useMemo } from 'react';
import { Plus, Search, Filter, Edit2, Trash2, ArrowLeft, Eye, CheckCircle2 } from 'lucide-react';
import { Pet, PetType, PetStatus } from '../../types';

interface ManagePetsViewProps {
  pets: Pet[];
  onNavigate: (route: string) => void;
  onEditPet: (pet: Pet) => void;
  onDeletePrompt: (pet: Pet) => void;
  onViewPetPublic: (pet: Pet) => void;
}

export const ManagePetsView: React.FC<ManagePetsViewProps> = ({
  pets,
  onNavigate,
  onEditPet,
  onDeletePrompt,
  onViewPetPublic,
}) => {
  const [search, setSearch] = useState('');
  const [selectedType, setSelectedType] = useState('All');
  const [selectedStatus, setSelectedStatus] = useState('All');

  const filteredPets = useMemo(() => {
    return pets.filter((pet) => {
      const matchType = selectedType === 'All' || pet.type === selectedType;
      const matchStatus = selectedStatus === 'All' || pet.status === selectedStatus;
      const matchSearch =
        !search ||
        pet.name.toLowerCase().includes(search.toLowerCase()) ||
        pet.breed.toLowerCase().includes(search.toLowerCase());
      return matchType && matchStatus && matchSearch;
    });
  }, [pets, selectedType, selectedStatus, search]);

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
            Manage Animal Profiles
          </h1>
          <p className="text-xs text-stone-500 mt-1">
            Total of {pets.length} animal records in database &bull; Full CRUD controls
          </p>
        </div>

        <div className="flex items-center gap-3">
          <button
            onClick={() => onNavigate('/dashboard')}
            className="inline-flex items-center gap-1.5 rounded-xl border border-stone-300 bg-white px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50"
          >
            <ArrowLeft className="h-3.5 w-3.5" />
            <span>Dashboard</span>
          </button>

          <button
            id="create-new-pet-btn"
            onClick={() => onNavigate('/admin/pets/create')}
            className="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-amber-600/20"
          >
            <Plus className="h-4 w-4" />
            <span>Add New Pet</span>
          </button>
        </div>
      </div>

      {/* Filters and Search Bar */}
      <div className="rounded-3xl bg-white p-5 border border-stone-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div className="relative flex-1 max-w-md">
          <Search className="absolute left-3.5 top-2.5 h-4 w-4 text-stone-400" />
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by pet name or breed..."
            className="w-full rounded-xl border border-stone-300 bg-stone-50/50 py-2 pl-10 pr-4 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:bg-white focus:outline-none"
          />
        </div>

        <div className="flex flex-wrap items-center gap-3 text-xs">
          <div className="flex items-center gap-1.5">
            <span className="text-stone-500 font-medium">Species:</span>
            <select
              value={selectedType}
              onChange={(e) => setSelectedType(e.target.value)}
              className="rounded-xl border border-stone-300 bg-white px-2.5 py-1.5 text-xs text-stone-800 font-semibold focus:outline-none focus:border-amber-600"
            >
              <option value="All">All Types</option>
              <option value="Dog">Dog</option>
              <option value="Cat">Cat</option>
              <option value="Bird">Bird</option>
              <option value="Rabbit">Rabbit</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <div className="flex items-center gap-1.5">
            <span className="text-stone-500 font-medium">Status:</span>
            <select
              value={selectedStatus}
              onChange={(e) => setSelectedStatus(e.target.value)}
              className="rounded-xl border border-stone-300 bg-white px-2.5 py-1.5 text-xs text-stone-800 font-semibold focus:outline-none focus:border-amber-600"
            >
              <option value="All">All Statuses</option>
              <option value="Available">Available</option>
              <option value="Pending">Pending</option>
              <option value="Adopted">Adopted</option>
            </select>
          </div>
        </div>
      </div>

      {/* Main Records Table */}
      <div className="rounded-3xl bg-white border border-stone-200/80 shadow-xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="bg-stone-50/80 border-b border-stone-200 text-stone-500 font-bold uppercase tracking-wider text-[10px]">
                <th className="px-6 py-3.5">ID</th>
                <th className="px-6 py-3.5">Photo & Pet Details</th>
                <th className="px-6 py-3.5">Species</th>
                <th className="px-6 py-3.5">Age & Gender</th>
                <th className="px-6 py-3.5">Adoption Status</th>
                <th className="px-6 py-3.5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-100">
              {filteredPets.length > 0 ? (
                filteredPets.map((pet) => (
                  <tr key={pet.id} className="hover:bg-stone-50/60 transition-colors">
                    <td className="px-6 py-4 font-mono text-stone-400 text-[11px]">
                      #{pet.id}
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3.5">
                        <img
                          src={pet.image_path}
                          alt={pet.name}
                          className="h-12 w-12 rounded-2xl object-cover border border-stone-200 flex-shrink-0"
                        />
                        <div>
                          <span className="font-extrabold text-stone-900 text-sm block">
                            {pet.name}
                          </span>
                          <span className="text-xs text-amber-800 font-medium">
                            {pet.breed || 'Rescue Mix'}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 font-semibold text-stone-700">
                      {pet.type}
                    </td>
                    <td className="px-6 py-4 text-stone-600">
                      <div>{pet.age}</div>
                      <div className="text-[11px] text-stone-400">{pet.gender}</div>
                    </td>
                    <td className="px-6 py-4">
                      <span
                        className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold ${
                          pet.status === 'Available'
                            ? 'bg-emerald-100 text-emerald-800'
                            : pet.status === 'Pending'
                            ? 'bg-amber-100 text-amber-800'
                            : 'bg-stone-100 text-stone-600'
                        }`}
                      >
                        <span
                          className={`h-1.5 w-1.5 rounded-full ${
                            pet.status === 'Available'
                              ? 'bg-emerald-500'
                              : pet.status === 'Pending'
                              ? 'bg-amber-500'
                              : 'bg-stone-400'
                          }`}
                        ></span>
                        <span>{pet.status}</span>
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-1.5">
                        <button
                          onClick={() => onViewPetPublic(pet)}
                          title="View Public Page"
                          className="rounded-lg p-1.5 text-stone-400 hover:bg-stone-100 hover:text-stone-800 transition-colors"
                        >
                          <Eye className="h-4 w-4" />
                        </button>
                        <button
                          id={`edit-pet-${pet.id}-btn`}
                          onClick={() => onEditPet(pet)}
                          title="Edit Pet Record"
                          className="rounded-lg p-1.5 text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition-colors"
                        >
                          <Edit2 className="h-4 w-4" />
                        </button>
                        <button
                          id={`delete-pet-${pet.id}-btn`}
                          onClick={() => onDeletePrompt(pet)}
                          title="Delete Pet Record"
                          className="rounded-lg p-1.5 text-stone-400 hover:bg-rose-50 hover:text-rose-600 transition-colors"
                        >
                          <Trash2 className="h-4 w-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={6} className="px-6 py-12 text-center text-stone-500">
                    No animals matched your filters.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};
