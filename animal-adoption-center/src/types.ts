export type PetType = 'Dog' | 'Cat' | 'Bird' | 'Rabbit' | 'Other';
export type PetGender = 'Male' | 'Female' | 'Unknown';
export type PetStatus = 'Available' | 'Pending' | 'Adopted';

export interface Pet {
  id: number;
  name: string;
  type: PetType;
  breed: string;
  age: string;
  gender: PetGender;
  description: string;
  status: PetStatus;
  image_path: string;
  created_at: string;
  updated_at: string;
}

export interface ShelterProfile {
  id: number;
  shelter_name: string;
  tagline: string;
  bio: string;
  phone: string;
  email: string;
  address: string;
  opening_hours: string;
  banner_image_path: string;
  updated_at: string;
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: 'owner';
}

export interface FlashMessage {
  id: string;
  type: 'success' | 'error' | 'info';
  message: string;
}
