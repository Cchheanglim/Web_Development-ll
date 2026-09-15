import React from 'react';
import { CheckCircle2, AlertCircle, Info, X } from 'lucide-react';
import { FlashMessage } from '../types';

interface FlashNotificationProps {
  flash: FlashMessage | null;
  onDismiss: () => void;
}

export const FlashNotification: React.FC<FlashNotificationProps> = ({
  flash,
  onDismiss,
}) => {
  if (!flash) return null;

  const getStyle = () => {
    switch (flash.type) {
      case 'success':
        return 'bg-emerald-600 text-white shadow-emerald-600/20';
      case 'error':
        return 'bg-rose-600 text-white shadow-rose-600/20';
      case 'info':
      default:
        return 'bg-stone-900 text-white shadow-stone-900/20';
    }
  };

  const getIcon = () => {
    switch (flash.type) {
      case 'success':
        return <CheckCircle2 className="h-5 w-5 text-emerald-200 flex-shrink-0" />;
      case 'error':
        return <AlertCircle className="h-5 w-5 text-rose-200 flex-shrink-0" />;
      case 'info':
      default:
        return <Info className="h-5 w-5 text-stone-300 flex-shrink-0" />;
    }
  };

  return (
    <div className="fixed top-20 right-4 z-50 max-w-md animate-fade-in">
      <div
        className={`flex items-center justify-between gap-3 rounded-2xl px-4 py-3.5 shadow-xl text-xs font-semibold ${getStyle()}`}
      >
        <div className="flex items-center gap-2.5">
          {getIcon()}
          <span>{flash.message}</span>
        </div>
        <button
          onClick={onDismiss}
          className="rounded-lg p-1 text-white/80 hover:text-white hover:bg-white/10 transition-colors"
        >
          <X className="h-4 w-4" />
        </button>
      </div>
    </div>
  );
};
