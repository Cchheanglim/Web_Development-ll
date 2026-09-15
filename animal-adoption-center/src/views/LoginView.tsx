import React, { useState } from 'react';
import { Lock, Key, Shield, ArrowLeft, CheckCircle2, AlertCircle } from 'lucide-react';
import { User } from '../types';

interface LoginViewProps {
  onLoginSuccess: (user: User) => void;
  onNavigate: (route: string) => void;
}

export const LoginView: React.FC<LoginViewProps> = ({
  onLoginSuccess,
  onNavigate,
}) => {
  const [email, setEmail] = useState('admin@adoptioncenter.com');
  const [password, setPassword] = useState('password123');
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    // Verify seeded credentials
    if (email.trim().toLowerCase() === 'admin@adoptioncenter.com' && password === 'password123') {
      const ownerUser: User = {
        id: 1,
        name: 'Shelter Director',
        email: 'admin@adoptioncenter.com',
        role: 'owner',
      };
      onLoginSuccess(ownerUser);
    } else {
      setError('These credentials do not match our records. Default: admin@adoptioncenter.com / password123');
    }
  };

  const handleFillDemo = () => {
    setEmail('admin@adoptioncenter.com');
    setPassword('password123');
    setError(null);
  };

  return (
    <div className="flex min-h-[75vh] items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
      <div className="w-full max-w-md space-y-7">
        {/* Logo and Heading */}
        <div className="text-center space-y-2">
          <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-stone-900 text-white shadow-xl shadow-stone-900/10">
            <Lock className="h-6 w-6 text-amber-400" />
          </div>
          <h2 className="text-2xl sm:text-3xl font-extrabold tracking-tight text-stone-900">
            Owner & Staff Sign In
          </h2>
          <p className="text-xs text-stone-500 max-w-xs mx-auto">
            Protected area for managing pet adoption records, statuses, and shelter settings.
          </p>
        </div>

        {/* Seeder Notice Helper Box */}
        <div className="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900 space-y-2 shadow-xs">
          <div className="flex items-center justify-between">
            <span className="font-bold flex items-center gap-1.5 text-amber-950">
              <Key className="h-3.5 w-3.5 text-amber-700" />
              Seeded Owner Account
            </span>
            <button
              id="fill-demo-credentials-btn"
              type="button"
              onClick={handleFillDemo}
              className="text-[11px] font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 px-2 py-0.5 rounded-md transition-colors"
            >
              Fill Credentials
            </button>
          </div>

          <div className="font-mono text-[11px] bg-white/80 p-2.5 rounded-xl border border-amber-200/80 space-y-1">
            <div>Email: <span className="font-bold text-stone-900">admin@adoptioncenter.com</span></div>
            <div>Password: <span className="font-bold text-stone-900">password123</span></div>
          </div>

          <p className="text-[11px] text-amber-800 italic">
            * Laravel Seeder note: Default Owner seeded with prompt to update password upon initial login.
          </p>
        </div>

        {/* Error Notification */}
        {error && (
          <div className="rounded-2xl bg-rose-50 border border-rose-200 p-3 text-xs text-rose-800 flex items-center gap-2">
            <AlertCircle className="h-4 w-4 text-rose-600 flex-shrink-0" />
            <span>{error}</span>
          </div>
        )}

        {/* Login Form */}
        <div className="rounded-3xl bg-white p-7 sm:p-8 shadow-xl shadow-stone-200/50 border border-stone-200/80">
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-stone-700">
                Email Address
              </label>
              <input
                id="owner-login-email"
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="admin@adoptioncenter.com"
                className="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600"
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-stone-700">
                Password
              </label>
              <input
                id="owner-login-password"
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="mt-1.5 w-full rounded-xl border border-stone-300 px-3.5 py-2.5 text-xs text-stone-900 placeholder:text-stone-400 focus:border-amber-600 focus:outline-none focus:ring-1 focus:ring-amber-600"
              />
            </div>

            <div className="flex items-center justify-between text-xs text-stone-500 pt-1">
              <label className="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  defaultChecked
                  className="rounded border-stone-300 text-amber-600 focus:ring-amber-500"
                />
                <span>Remember session</span>
              </label>
              <span className="text-[11px] text-stone-400">CSRF Protected</span>
            </div>

            <button
              id="submit-login-btn"
              type="submit"
              className="w-full rounded-xl bg-stone-900 hover:bg-stone-800 py-3 text-xs font-bold text-white shadow-md transition-all flex items-center justify-center gap-2"
            >
              <Shield className="h-4 w-4 text-amber-400" />
              <span>Sign In as Owner</span>
            </button>
          </form>
        </div>

        <div className="text-center">
          <button
            onClick={() => onNavigate('/')}
            className="inline-flex items-center gap-1.5 text-xs text-stone-500 hover:text-stone-800 transition-colors"
          >
            <ArrowLeft className="h-3.5 w-3.5" />
            <span>Return to Public Website</span>
          </button>
        </div>
      </div>
    </div>
  );
};
