import React, { useState, useEffect } from 'react';
import { Pet, ShelterProfile, User, FlashMessage } from './types';
import {
  getStoredPets,
  saveStoredPets,
  getStoredShelter,
  saveStoredShelter,
  getStoredUser,
  setStoredUser,
  resetToSeedData,
} from './storage';
import { Header } from './components/Header';
import { Footer } from './components/Footer';
import { FlashNotification } from './components/FlashNotification';
import { InquiryModal } from './components/InquiryModal';
import { DeleteConfirmModal } from './components/DeleteConfirmModal';
import { LaravelCodeInspector } from './components/LaravelCodeInspector';

import { HomeView } from './views/HomeView';
import { PetDetailView } from './views/PetDetailView';
import { AboutView } from './views/AboutView';
import { LoginView } from './views/LoginView';
import { DashboardView } from './views/admin/DashboardView';
import { ManagePetsView } from './views/admin/ManagePetsView';
import { PetFormView } from './views/admin/PetFormView';
import { ShelterProfileView } from './views/admin/ShelterProfileView';

export default function App() {
  const [pets, setPets] = useState<Pet[]>(getStoredPets());
  const [shelter, setShelter] = useState<ShelterProfile>(getStoredShelter());
  const [currentUser, setCurrentUser] = useState<User | null>(getStoredUser());
  const [currentRoute, setCurrentRoute] = useState<string>('/');

  // Selected pet for public detail view or modals
  const [activePet, setActivePet] = useState<Pet | null>(null);
  const [inquiryPet, setInquiryPet] = useState<Pet | null>(null);
  const [petToDelete, setPetToDelete] = useState<Pet | null>(null);
  const [petToEdit, setPetToEdit] = useState<Pet | null>(null);

  // Inspector & Notification
  const [codeInspectorOpen, setCodeInspectorOpen] = useState(false);
  const [flash, setFlash] = useState<FlashMessage | null>(null);

  const showFlash = (message: string, type: 'success' | 'error' | 'info' = 'success') => {
    const newFlash: FlashMessage = { id: String(Date.now()), type, message };
    setFlash(newFlash);
    setTimeout(() => {
      setFlash((current) => (current?.id === newFlash.id ? null : current));
    }, 4500);
  };

  const navigate = (route: string) => {
    setCurrentRoute(route);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // Auth operations
  const handleLoginSuccess = (user: User) => {
    setCurrentUser(user);
    setStoredUser(user);
    showFlash(`Signed in successfully as ${user.name}`);
    navigate('/dashboard');
  };

  const handleLogout = () => {
    setCurrentUser(null);
    setStoredUser(null);
    showFlash('Signed out of Owner session.', 'info');
    navigate('/');
  };

  // Pet CRUD operations
  const handleCreatePet = (petData: Omit<Pet, 'id' | 'created_at' | 'updated_at'>) => {
    const nextId = pets.length > 0 ? Math.max(...pets.map((p) => p.id)) + 1 : 1;
    const newPet: Pet = {
      ...petData,
      id: nextId,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    };

    const updated = [newPet, ...pets];
    setPets(updated);
    saveStoredPets(updated);
    showFlash(`Pet profile created successfully for ${newPet.name}!`);
    navigate('/admin/pets');
  };

  const handleUpdatePet = (petData: Omit<Pet, 'id' | 'created_at' | 'updated_at'>) => {
    if (!petToEdit) return;
    const updated = pets.map((p) =>
      p.id === petToEdit.id
        ? {
            ...p,
            ...petData,
            updated_at: new Date().toISOString(),
          }
        : p
    );
    setPets(updated);
    saveStoredPets(updated);
    setPetToEdit(null);
    showFlash(`Pet profile updated successfully for ${petData.name}!`);
    navigate('/admin/pets');
  };

  const handleDeletePet = (pet: Pet) => {
    const updated = pets.filter((p) => p.id !== pet.id);
    setPets(updated);
    saveStoredPets(updated);
    setPetToDelete(null);
    showFlash(`Pet profile deleted successfully for ${pet.name}!`);
  };

  const handleQuickStatusChange = (pet: Pet, newStatus: Pet['status']) => {
    const updated = pets.map((p) =>
      p.id === pet.id
        ? {
            ...p,
            status: newStatus,
            updated_at: new Date().toISOString(),
          }
        : p
    );
    setPets(updated);
    saveStoredPets(updated);
    showFlash(`Status for ${pet.name} updated to "${newStatus}"!`);
  };

  // Shelter Profile update
  const handleUpdateShelter = (updatedShelter: ShelterProfile) => {
    setShelter(updatedShelter);
    saveStoredShelter(updatedShelter);
    showFlash('Shelter profile updated successfully!');
    navigate('/dashboard');
  };

  // Reset to Seeders
  const handleResetSeedData = () => {
    const { pets: seedPets, shelter: seedShelter } = resetToSeedData();
    setPets(seedPets);
    setShelter(seedShelter);
    showFlash('Sanctuary database reset to initial seeded data!');
    navigate('/');
  };

  return (
    <div className="min-h-screen flex flex-col bg-[#faf8f5] text-stone-900 font-sans selection:bg-amber-500 selection:text-white">
      {/* Toast Flash Notification */}
      <FlashNotification flash={flash} onDismiss={() => setFlash(null)} />

      {/* Main Header */}
      <Header
        currentRoute={currentRoute}
        onNavigate={navigate}
        currentUser={currentUser}
        onLogout={handleLogout}
        shelter={shelter}
        onOpenCodeInspector={() => setCodeInspectorOpen(true)}
        onResetSeedData={handleResetSeedData}
      />

      {/* Main Content Router */}
      <main className="flex-1">
        {/* Public Routes */}
        {currentRoute === '/' && (
          <HomeView
            shelter={shelter}
            pets={pets}
            onViewPet={(pet) => {
              setActivePet(pet);
              navigate(`/pets/${pet.id}`);
            }}
            onInquire={(pet) => setInquiryPet(pet)}
            onNavigate={navigate}
          />
        )}

        {currentRoute.startsWith('/pets/') && activePet && (
          <PetDetailView
            pet={activePet}
            shelter={shelter}
            onBack={() => navigate('/')}
            onInquire={(pet) => setInquiryPet(pet)}
            onSelectOtherPet={(pet) => {
              setActivePet(pet);
              navigate(`/pets/${pet.id}`);
            }}
            otherPets={pets.filter((p) => p.id !== activePet.id).slice(0, 3)}
          />
        )}

        {currentRoute === '/about' && (
          <AboutView
            shelter={shelter}
            onNavigate={navigate}
            onSuccessToast={(msg) => showFlash(msg, 'success')}
          />
        )}

        {/* Auth Route */}
        {currentRoute === '/login' && (
          <LoginView
            onLoginSuccess={handleLoginSuccess}
            onNavigate={navigate}
          />
        )}

        {/* Protected Owner Admin Routes */}
        {currentUser && currentRoute === '/dashboard' && (
          <DashboardView
            user={currentUser}
            pets={pets}
            shelter={shelter}
            onNavigate={navigate}
            onEditPet={(pet) => {
              setPetToEdit(pet);
              navigate(`/admin/pets/${pet.id}/edit`);
            }}
            onDeletePrompt={(pet) => setPetToDelete(pet)}
            onStatusChange={handleQuickStatusChange}
          />
        )}

        {currentUser && currentRoute === '/admin/pets' && (
          <ManagePetsView
            pets={pets}
            onNavigate={navigate}
            onEditPet={(pet) => {
              setPetToEdit(pet);
              navigate(`/admin/pets/${pet.id}/edit`);
            }}
            onDeletePrompt={(pet) => setPetToDelete(pet)}
            onViewPetPublic={(pet) => {
              setActivePet(pet);
              navigate(`/pets/${pet.id}`);
            }}
          />
        )}

        {currentUser && currentRoute === '/admin/pets/create' && (
          <PetFormView
            onSave={handleCreatePet}
            onCancel={() => navigate('/admin/pets')}
          />
        )}

        {currentUser && currentRoute.includes('/edit') && petToEdit && (
          <PetFormView
            initialPet={petToEdit}
            onSave={handleUpdatePet}
            onCancel={() => navigate('/admin/pets')}
          />
        )}

        {currentUser && currentRoute === '/admin/shelter-profile' && (
          <ShelterProfileView
            shelter={shelter}
            onSave={handleUpdateShelter}
            onCancel={() => navigate('/dashboard')}
          />
        )}

        {/* Fallback to login if navigating to admin route while unauthenticated */}
        {!currentUser && (currentRoute.startsWith('/admin') || currentRoute === '/dashboard') && (
          <LoginView
            onLoginSuccess={handleLoginSuccess}
            onNavigate={navigate}
          />
        )}
      </main>

      {/* Footer */}
      <Footer
        shelter={shelter}
        currentUser={currentUser}
        onNavigate={navigate}
        onLogout={handleLogout}
      />

      {/* Modals */}
      <InquiryModal
        pet={inquiryPet}
        shelter={shelter}
        isOpen={Boolean(inquiryPet)}
        onClose={() => setInquiryPet(null)}
        onSuccessToast={(msg) => showFlash(msg, 'success')}
      />

      <DeleteConfirmModal
        pet={petToDelete}
        isOpen={Boolean(petToDelete)}
        onClose={() => setPetToDelete(null)}
        onConfirm={handleDeletePet}
      />

      <LaravelCodeInspector
        isOpen={codeInspectorOpen}
        onClose={() => setCodeInspectorOpen(false)}
      />
    </div>
  );
}
