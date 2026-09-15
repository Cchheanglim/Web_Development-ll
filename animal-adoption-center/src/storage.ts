import { Pet, ShelterProfile, User } from './types';
import { INITIAL_PETS, INITIAL_SHELTER } from './initialData';

const PETS_KEY = 'laravel_aac_pets';
const SHELTER_KEY = 'laravel_aac_shelter';
const USER_KEY = 'laravel_aac_user';

export function getStoredPets(): Pet[] {
  try {
    const raw = localStorage.getItem(PETS_KEY);
    if (raw) {
      return JSON.parse(raw);
    }
  } catch (e) {
    console.error('Failed to load pets from storage', e);
  }
  return INITIAL_PETS;
}

export function saveStoredPets(pets: Pet[]): void {
  try {
    localStorage.setItem(PETS_KEY, JSON.stringify(pets));
  } catch (e) {
    console.error('Failed to save pets to storage', e);
  }
}

export function getStoredShelter(): ShelterProfile {
  try {
    const raw = localStorage.getItem(SHELTER_KEY);
    if (raw) {
      return JSON.parse(raw);
    }
  } catch (e) {
    console.error('Failed to load shelter from storage', e);
  }
  return INITIAL_SHELTER;
}

export function saveStoredShelter(shelter: ShelterProfile): void {
  try {
    localStorage.setItem(SHELTER_KEY, JSON.stringify(shelter));
  } catch (e) {
    console.error('Failed to save shelter to storage', e);
  }
}

export function getStoredUser(): User | null {
  try {
    const raw = localStorage.getItem(USER_KEY);
    if (raw) {
      return JSON.parse(raw);
    }
  } catch (e) {
    console.error('Failed to load user from storage', e);
  }
  return null;
}

export function setStoredUser(user: User | null): void {
  try {
    if (user) {
      localStorage.setItem(USER_KEY, JSON.stringify(user));
    } else {
      localStorage.removeItem(USER_KEY);
    }
  } catch (e) {
    console.error('Failed to set user in storage', e);
  }
}

export function resetToSeedData(): { pets: Pet[]; shelter: ShelterProfile } {
  localStorage.setItem(PETS_KEY, JSON.stringify(INITIAL_PETS));
  localStorage.setItem(SHELTER_KEY, JSON.stringify(INITIAL_SHELTER));
  return { pets: INITIAL_PETS, shelter: INITIAL_SHELTER };
}
