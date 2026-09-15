import React from 'react';
import { AlertTriangle, Trash2, X } from 'lucide-react';
import { Pet } from '../types';

interface DeleteConfirmModalProps {
  pet: Pet | null;
  isOpen: boolean;
  onClose: () => void;
  onConfirm: (pet: Pet) => void;
}

export const DeleteConfirmModal: React.FC<DeleteConfirmModalProps> = ({
  pet,
  isOpen,
  onClose,
  onConfirm,
}) => {
  if (!isOpen || !pet) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/60 backdrop-blur-xs p-4 animate-fade-in">
      <div className="relative w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-5">
        <button
          id="close-delete-modal-btn"
          onClick={onClose}
          className="absolute right-5 top-5 rounded-full p-1.5 text-stone-400 hover:bg-stone-100 hover:text-stone-700 transition-colors"
        >
          <X className="h-5 w-5" />
        </button>

        <div className="flex items-center gap-3.5">
          <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
            <AlertTriangle className="h-6 w-6" />
          </div>
          <div>
            <h3 className="text-lg font-bold text-stone-900">Confirm Pet Deletion</h3>
            <p className="text-xs text-stone-500">Database deletion confirmation</p>
          </div>
        </div>

        <div className="rounded-2xl bg-stone-50 p-4 border border-stone-200 flex items-center gap-3">
          <img
            src={pet.image_path}
            alt={pet.name}
            className="h-14 w-14 rounded-xl object-cover border border-stone-200"
          />
          <div>
            <p className="font-bold text-stone-900 text-sm">{pet.name}</p>
            <p className="text-xs text-stone-500">
              {pet.type} &bull; {pet.breed || 'Rescue Mix'} &bull; #{pet.id}
            </p>
          </div>
        </div>

        <p className="text-xs text-stone-600 leading-relaxed">
          Are you sure you want to permanently delete <strong>{pet.name}</strong> from the adoption center records? This action removes the pet profile and obsolete picture from storage.
        </p>

        <div className="pt-2 flex items-center justify-end gap-3 border-t border-stone-100">
          <button
            id="cancel-delete-btn"
            onClick={onClose}
            className="rounded-xl border border-stone-300 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition-colors"
          >
            Cancel
          </button>
          <button
            id="confirm-delete-btn"
            onClick={() => onConfirm(pet)}
            className="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-bold text-white shadow-sm transition-colors"
          >
            <Trash2 className="h-3.5 w-3.5" />
            <span>Yes, Delete Record</span>
          </button>
        </div>
      </div>
    </div>
  );
};
