import React from 'react';
import { PawPrint, Lock, Heart } from 'lucide-react';
import { ShelterProfile, User } from '../types';

interface FooterProps {
  shelter: ShelterProfile;
  currentUser: User | null;
  onNavigate: (route: string) => void;
  onLogout: () => void;
}

export const Footer: React.FC<FooterProps> = ({
  shelter,
  currentUser,
  onNavigate,
  onLogout,
}) => {
  return (
    <footer className="mt-auto border-t border-stone-200 bg-stone-900 text-stone-300">
      <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 space-y-8">
        <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-8 border-b border-stone-800">
          <div className="flex items-center gap-3">
            <span className="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-500 text-white text-lg shadow-md shadow-amber-500/20">
              <PawPrint className="h-5 w-5" />
            </span>
            <div>
              <p className="font-extrabold text-white text-base leading-tight">
                {shelter.shelter_name}
              </p>
              <p className="text-xs text-stone-400 mt-0.5">
                {shelter.tagline}
              </p>
            </div>
          </div>

          {/* Footer Navigation Links */}
          <div className="flex flex-wrap items-center gap-6 text-sm text-stone-400">
            <button
              id="footer-browse-link"
              onClick={() => onNavigate('/')}
              className="hover:text-amber-400 transition-colors"
            >
              Browse Animals
            </button>
            <button
              id="footer-about-link"
              onClick={() => onNavigate('/about')}
              className="hover:text-amber-400 transition-colors"
            >
              Shelter Story & Hours
            </button>

            {/* Discrete Owner Login Link */}
            {currentUser ? (
              <div className="flex items-center gap-2 border-l border-stone-800 pl-6">
                <button
                  id="footer-dashboard-btn"
                  onClick={() => onNavigate('/dashboard')}
                  className="text-xs text-amber-400 hover:text-amber-300 transition-colors"
                >
                  Dashboard ({currentUser.name})
                </button>
                <span className="text-stone-700">&bull;</span>
                <button
                  id="footer-logout-btn"
                  onClick={onLogout}
                  className="text-xs text-stone-500 hover:text-rose-400 transition-colors"
                >
                  Sign Out
                </button>
              </div>
            ) : (
              <button
                id="footer-owner-login-btn"
                onClick={() => onNavigate('/login')}
                className="text-xs text-stone-500 hover:text-stone-300 transition-colors inline-flex items-center gap-1.5 border-l border-stone-800 pl-6"
                title="Restricted Staff & Owner Portal"
              >
                <Lock className="h-3 w-3" />
                <span>Owner Portal</span>
              </button>
            )}
          </div>
        </div>

        <div className="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
          <p>
            &copy; {new Date().getFullYear()} {shelter.shelter_name}. Built with PHP Laravel 11 & Tailwind CSS architecture.
          </p>
          <div className="flex items-center gap-1 text-stone-400">
            <span>Made with</span>
            <Heart className="h-3.5 w-3.5 text-rose-500 fill-rose-500" />
            <span>for rescue animals everywhere.</span>
          </div>
        </div>
      </div>
    </footer>
  );
};
