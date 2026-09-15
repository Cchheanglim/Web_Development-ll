import React, { useState } from 'react';
import { Plus, Users, Heart, AlertCircle, Sparkles, Check, Edit2, Trash2, ArrowRight, ShieldAlert, KeyRound, ExternalLink } from 'lucide-react';
import { Pet, ShelterProfile, User } from '../../types';

interface DashboardViewProps {
  user: User;
  pets: Pet[];
  shelter: ShelterProfile;
  onNavigate: (route: string) => void;
  onEditPet: (pet: Pet) => void;
  onDeletePrompt: (pet: Pet) => void;
  onStatusChange: (pet: Pet, newStatus: Pet['status']) => void;
}

export const DashboardView: React.FC<DashboardViewProps> = ({
  user,
  pets,
  shelter,
  onNavigate,
  onEditPet,
  onDeletePrompt,
  onStatusChange,
}) => {
  const [dismissPasswordNotice, setDismissPasswordNotice] = useState(false);
  const [showPasswordModal, setShowPasswordModal] = useState(false);
  const [newPassword, setNewPassword] = useState('');
  const [passwordSuccess, setPasswordSuccess] = useState(false);

  const totalPets = pets.length;
  const availablePets = pets.filter((p) => p.status === 'Available').length;
  const pendingPets = pets.filter((p) => p.status === 'Pending').length;
  const adoptedPets = pets.filter((p) => p.status === 'Adopted').length;

  const recentPets = [...pets].slice(0, 6);

  const handlePasswordSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setPasswordSuccess(true);
    setTimeout(() => {
      setPasswordSuccess(false);
      setShowPasswordModal(false);
      setDismissPasswordNotice(true);
    }, 1200);
  };

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
      {/* Top Banner / Welcome */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
              Welcome, {user.name} 👋
            </h1>
            <span className="rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-0.5 border border-emerald-200">
              Owner Active
            </span>
          </div>
          <p className="text-xs text-stone-500 mt-1">
            Managing <span className="font-semibold text-stone-700">{shelter.shelter_name}</span> &bull; Laravel 11 Session
          </p>
        </div>

        <div className="flex items-center gap-2.5">
          <button
            id="admin-quick-add-pet-btn"
            onClick={() => onNavigate('/admin/pets/create')}
            className="inline-flex items-center gap-1.5 rounded-2xl bg-amber-600 hover:bg-amber-700 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-amber-600/20 transition-all"
          >
            <Plus className="h-4 w-4" />
            <span>Add New Pet</span>
          </button>
          <button
            onClick={() => onNavigate('/admin/shelter-profile')}
            className="rounded-2xl border border-stone-300 bg-white hover:bg-stone-50 px-4 py-2.5 text-xs font-bold text-stone-700 shadow-xs transition-colors"
          >
            Shelter Settings
          </button>
        </div>
      </div>

      {/* Initial Login Password Notice (from DatabaseSeeder specification) */}
      {!dismissPasswordNotice && (
        <div className="rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
          <div className="flex items-start gap-3">
            <div className="p-2 rounded-xl bg-amber-100 text-amber-800 mt-0.5">
              <ShieldAlert className="h-5 w-5" />
            </div>
            <div>
              <h4 className="text-xs font-bold text-amber-950 uppercase tracking-wider">
                First Login Recommendation
              </h4>
              <p className="text-xs text-amber-800 mt-0.5 leading-relaxed">
                You are currently signed in using the default seeded credential (<code className="font-mono bg-white/70 px-1 py-0.5 rounded text-amber-900">admin@adoptioncenter.com</code>). It is highly recommended to set a custom administrator password.
              </p>
            </div>
          </div>

          <div className="flex items-center gap-2 w-full sm:w-auto">
            <button
              onClick={() => setShowPasswordModal(true)}
              className="flex-1 sm:flex-none rounded-xl bg-amber-800 hover:bg-amber-900 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition-colors whitespace-nowrap"
            >
              Update Password
            </button>
            <button
              onClick={() => setDismissPasswordNotice(true)}
              className="rounded-xl border border-amber-300 px-3 py-2 text-xs font-semibold text-amber-900 hover:bg-amber-100 transition-colors"
            >
              Dismiss
            </button>
          </div>
        </div>
      )}

      {/* 4 Key Metrics Dashboard Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div className="rounded-3xl bg-white p-6 border border-stone-200/80 shadow-xs space-y-2">
          <span className="text-[11px] font-bold uppercase tracking-wider text-stone-400">
            Total Animals
          </span>
          <p className="text-4xl font-black text-stone-900 tracking-tight">{totalPets}</p>
          <span className="text-xs text-stone-500 block">Registered in sanctuary database</span>
        </div>

        <div className="rounded-3xl bg-white p-6 border border-emerald-200/80 shadow-xs space-y-2">
          <span className="text-[11px] font-bold uppercase tracking-wider text-emerald-600">
            Available For Adoption
          </span>
          <p className="text-4xl font-black text-emerald-600 tracking-tight">{availablePets}</p>
          <span className="text-xs text-emerald-700/80 block">Actively visible to visitors</span>
        </div>

        <div className="rounded-3xl bg-white p-6 border border-amber-200/80 shadow-xs space-y-2">
          <span className="text-[11px] font-bold uppercase tracking-wider text-amber-600">
            Applications Pending
          </span>
          <p className="text-4xl font-black text-amber-600 tracking-tight">{pendingPets}</p>
          <span className="text-xs text-amber-700/80 block">Under review with coordinators</span>
        </div>

        <div className="rounded-3xl bg-white p-6 border border-stone-200/80 shadow-xs space-y-2">
          <span className="text-[11px] font-bold uppercase tracking-wider text-stone-500">
            Happily Adopted
          </span>
          <p className="text-4xl font-black text-stone-700 tracking-tight">{adoptedPets}</p>
          <span className="text-xs text-stone-500 block">Forever home success stories</span>
        </div>
      </div>

      {/* Recent Pets & Fast Status Management */}
      <div className="rounded-3xl bg-white p-6 sm:p-8 border border-stone-200/80 shadow-xs space-y-6">
        <div className="flex items-center justify-between border-b border-stone-100 pb-4">
          <div>
            <h3 className="text-lg font-extrabold text-stone-900">
              Recent Animal Records & Quick Status
            </h3>
            <p className="text-xs text-stone-500 mt-0.5">
              Quickly update availability or edit individual records.
            </p>
          </div>

          <button
            onClick={() => onNavigate('/admin/pets')}
            className="text-xs font-bold text-amber-700 hover:text-amber-800 inline-flex items-center gap-1"
          >
            <span>View All Records ({totalPets})</span>
            <ArrowRight className="h-3.5 w-3.5" />
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="border-b border-stone-200 text-stone-400 font-bold uppercase tracking-wider text-[10px]">
                <th className="pb-3">Animal</th>
                <th className="pb-3">Species</th>
                <th className="pb-3">Age / Gender</th>
                <th className="pb-3">Status</th>
                <th className="pb-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-100">
              {recentPets.map((pet) => (
                <tr key={pet.id} className="hover:bg-stone-50/80 transition-colors">
                  <td className="py-3 pr-4">
                    <div className="flex items-center gap-3">
                      <img
                        src={pet.image_path}
                        alt={pet.name}
                        className="h-10 w-10 rounded-xl object-cover border border-stone-200"
                      />
                      <div>
                        <span className="font-bold text-stone-900 block">{pet.name}</span>
                        <span className="text-[11px] text-stone-400">{pet.breed || 'Rescue Mix'}</span>
                      </div>
                    </div>
                  </td>
                  <td className="py-3 pr-4 font-medium text-stone-700">{pet.type}</td>
                  <td className="py-3 pr-4 text-stone-600">
                    {pet.age} &bull; {pet.gender}
                  </td>
                  <td className="py-3 pr-4">
                    <select
                      value={pet.status}
                      onChange={(e) => onStatusChange(pet, e.target.value as Pet['status'])}
                      className={`rounded-lg px-2 py-1 text-xs font-bold border ${
                        pet.status === 'Available'
                          ? 'border-emerald-300 bg-emerald-50 text-emerald-800'
                          : pet.status === 'Pending'
                          ? 'border-amber-300 bg-amber-50 text-amber-800'
                          : 'border-stone-300 bg-stone-100 text-stone-700'
                      }`}
                    >
                      <option value="Available">Available</option>
                      <option value="Pending">Pending</option>
                      <option value="Adopted">Adopted</option>
                    </select>
                  </td>
                  <td className="py-3 text-right space-x-2">
                    <button
                      onClick={() => onEditPet(pet)}
                      className="rounded-lg p-1.5 text-stone-500 hover:bg-stone-200 hover:text-stone-900 transition-colors"
                      title="Edit Pet Profile"
                    >
                      <Edit2 className="h-4 w-4" />
                    </button>
                    <button
                      onClick={() => onDeletePrompt(pet)}
                      className="rounded-lg p-1.5 text-stone-400 hover:bg-rose-50 hover:text-rose-600 transition-colors"
                      title="Delete Record"
                    >
                      <Trash2 className="h-4 w-4" />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Password Update Modal */}
      {showPasswordModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/60 backdrop-blur-xs p-4 animate-fade-in">
          <div className="w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-4">
            <div className="flex items-center gap-3">
              <div className="p-2 rounded-2xl bg-amber-50 text-amber-700 border border-amber-100">
                <KeyRound className="h-5 w-5" />
              </div>
              <div>
                <h3 className="font-bold text-stone-900 text-base">Update Owner Password</h3>
                <p className="text-xs text-stone-500">Security requirement for seeded accounts</p>
              </div>
            </div>

            {passwordSuccess ? (
              <div className="py-6 text-center space-y-2">
                <Check className="h-10 w-10 text-emerald-500 mx-auto" />
                <p className="text-xs font-bold text-stone-800">Password successfully updated!</p>
              </div>
            ) : (
              <form onSubmit={handlePasswordSubmit} className="space-y-4">
                <div>
                  <label className="block text-xs font-bold text-stone-700">New Password *</label>
                  <input
                    type="password"
                    required
                    minLength={8}
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                    placeholder="Enter at least 8 characters"
                    className="mt-1 w-full rounded-xl border border-stone-300 px-3.5 py-2 text-xs focus:border-amber-600 focus:outline-none"
                  />
                </div>

                <div className="pt-2 flex items-center justify-end gap-2">
                  <button
                    type="button"
                    onClick={() => setShowPasswordModal(false)}
                    className="rounded-xl border border-stone-300 px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    className="rounded-xl bg-amber-600 hover:bg-amber-700 px-4 py-2 text-xs font-bold text-white shadow-xs"
                  >
                    Save New Password
                  </button>
                </div>
              </form>
            )}
          </div>
        </div>
      )}
    </div>
  );
};
