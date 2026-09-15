import React from 'react';
import { PawPrint, Shield, FileCode2, RotateCcw, ExternalLink, LogOut } from 'lucide-react';
import { User, ShelterProfile } from '../types';

interface HeaderProps {
  currentRoute: string;
  onNavigate: (route: string) => void;
  currentUser: User | null;
  onLogout: () => void;
  shelter: ShelterProfile;
  onOpenCodeInspector: () => void;
  onResetSeedData: () => void;
}

export const Header: React.FC<HeaderProps> = ({
  currentRoute,
  onNavigate,
  currentUser,
  onLogout,
  shelter,
  onOpenCodeInspector,
  onResetSeedData,
}) => {
  const isAdmin = currentRoute.startsWith('/admin') || currentRoute === '/dashboard';

  return (
    <header className="sticky top-0 z-40 border-b border-amber-900/10 bg-white/95 backdrop-blur-md transition-colors">
      {/* Top Utility Banner */}
      <div className="bg-amber-900/5 px-4 py-1.5 text-xs text-amber-900 flex items-center justify-between border-b border-amber-900/5">
        <div className="mx-auto max-w-7xl flex items-center justify-between w-full">
          <div className="flex items-center gap-2">
            <span className="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span className="font-medium text-stone-700 hidden sm:inline">PHP Laravel 11 Live Architecture &bull;</span>
            <span className="font-semibold text-amber-900">Shelter Open For Adoptions</span>
          </div>

          <div className="flex items-center gap-3">
            <button
              id="reset-seed-btn"
              onClick={onResetSeedData}
              title="Reset sample pets and shelter data to fresh database seeder state"
              className="inline-flex items-center gap-1 text-[11px] font-medium text-stone-500 hover:text-stone-800 transition-colors"
            >
              <RotateCcw className="h-3 w-3" />
              <span>Reset Seed Data</span>
            </button>
            <span className="text-stone-300">|</span>
            <button
              id="open-code-inspector-btn"
              onClick={onOpenCodeInspector}
              className="inline-flex items-center gap-1.5 rounded-md bg-stone-900 px-2 py-0.5 text-[11px] font-semibold text-white hover:bg-stone-800 transition-all shadow-xs"
            >
              <FileCode2 className="h-3 w-3 text-amber-400" />
              <span>Inspect Laravel 11 Code</span>
            </button>
          </div>
        </div>
      </div>

      {/* Main Navigation Bar */}
      <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        {/* Brand / Logo */}
        <button
          id="nav-logo-btn"
          onClick={() => onNavigate('/')}
          className="flex items-center gap-3 group text-left"
        >
          <span className="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
            <PawPrint className="h-6 w-6" />
          </span>
          <div>
            <span className="block text-lg font-extrabold text-stone-900 tracking-tight leading-tight group-hover:text-amber-700 transition-colors">
              {shelter.shelter_name}
            </span>
            <span className="block text-[11px] font-semibold text-amber-700 uppercase tracking-wider">
              Animal Adoption Center
            </span>
          </div>
        </button>

        {/* Navigation Links */}
        <nav className="flex items-center gap-2 sm:gap-6 text-sm font-medium">
          <button
            id="nav-browse-pets"
            onClick={() => onNavigate('/')}
            className={`px-3 py-1.5 rounded-xl transition-all ${
              currentRoute === '/'
                ? 'bg-amber-50 text-amber-800 font-bold'
                : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50'
            }`}
          >
            Browse Pets
          </button>

          <button
            id="nav-about"
            onClick={() => onNavigate('/about')}
            className={`px-3 py-1.5 rounded-xl transition-all ${
              currentRoute === '/about'
                ? 'bg-amber-50 text-amber-800 font-bold'
                : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50'
            }`}
          >
            About & Visiting
          </button>

          {currentUser ? (
            <div className="flex items-center gap-2 pl-2 border-l border-stone-200">
              <button
                id="nav-dashboard-link"
                onClick={() => onNavigate('/dashboard')}
                className={`inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs font-bold transition-all ${
                  isAdmin
                    ? 'bg-stone-900 text-white shadow-sm'
                    : 'bg-amber-100 text-amber-900 hover:bg-amber-200'
                }`}
              >
                <Shield className="h-3.5 w-3.5" />
                <span>Owner Dashboard</span>
              </button>

              <button
                id="header-logout-btn"
                onClick={onLogout}
                title="Log out of Owner session"
                className="rounded-xl p-1.5 text-stone-400 hover:bg-stone-100 hover:text-rose-600 transition-colors"
              >
                <LogOut className="h-4 w-4" />
              </button>
            </div>
          ) : (
            <button
              id="nav-owner-login-btn"
              onClick={() => onNavigate('/login')}
              className="inline-flex items-center gap-1.5 rounded-xl border border-stone-200 px-3 py-1.5 text-xs font-semibold text-stone-600 hover:border-amber-400 hover:text-amber-800 transition-all"
            >
              <Shield className="h-3.5 w-3.5 text-amber-600" />
              <span className="hidden sm:inline">Owner Login</span>
            </button>
          )}
        </nav>
      </div>

      {/* Admin Secondary Bar when in dashboard */}
      {isAdmin && currentUser && (
        <div className="bg-stone-900 text-stone-300 px-4 py-2 text-xs">
          <div className="mx-auto max-w-7xl flex flex-wrap items-center justify-between gap-3">
            <div className="flex items-center gap-4">
              <span className="font-semibold text-white flex items-center gap-1.5">
                <span className="h-2 w-2 rounded-full bg-emerald-400"></span>
                Owner Mode: {currentUser.name}
              </span>
              <div className="flex items-center gap-1">
                <button
                  id="admin-bar-dashboard"
                  onClick={() => onNavigate('/dashboard')}
                  className={`px-2.5 py-1 rounded-lg transition-colors ${
                    currentRoute === '/dashboard' ? 'bg-stone-800 text-white font-bold' : 'hover:text-white'
                  }`}
                >
                  Dashboard Metrics
                </button>
                <button
                  id="admin-bar-pets"
                  onClick={() => onNavigate('/admin/pets')}
                  className={`px-2.5 py-1 rounded-lg transition-colors ${
                    currentRoute.startsWith('/admin/pets') ? 'bg-stone-800 text-white font-bold' : 'hover:text-white'
                  }`}
                >
                  Manage Pets
                </button>
                <button
                  id="admin-bar-shelter"
                  onClick={() => onNavigate('/admin/shelter-profile')}
                  className={`px-2.5 py-1 rounded-lg transition-colors ${
                    currentRoute === '/admin/shelter-profile' ? 'bg-stone-800 text-white font-bold' : 'hover:text-white'
                  }`}
                >
                  Shelter Profile
                </button>
              </div>
            </div>

            <div className="flex items-center gap-3">
              <button
                onClick={() => onNavigate('/')}
                className="text-stone-400 hover:text-white inline-flex items-center gap-1"
              >
                <span>View Public Site</span>
                <ExternalLink className="h-3 w-3" />
              </button>
            </div>
          </div>
        </div>
      )}
    </header>
  );
};
