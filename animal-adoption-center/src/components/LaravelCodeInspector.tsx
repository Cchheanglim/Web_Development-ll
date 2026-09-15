import React, { useState } from 'react';
import { X, Copy, Check, FileCode, FolderTree, ExternalLink, Download } from 'lucide-react';

interface CodeFile {
  path: string;
  category: 'Routing' | 'Controllers' | 'Models' | 'Requests' | 'Migrations & Seeders' | 'Blade Views' | 'Config';
  language: string;
  content: string;
}

const LARAVEL_FILES: CodeFile[] = [
  {
    path: 'routes/web.php',
    category: 'Routing',
    language: 'php',
    content: `<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PetController as AdminPetController;
use App\Http\Controllers\Admin\ShelterProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Owner Dashboard Routes (middleware: ['auth'])
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pets', AdminPetController::class);
    Route::get('/shelter-profile', [ShelterProfileController::class, 'edit'])->name('shelter-profile.edit');
    Route::put('/shelter-profile', [ShelterProfileController::class, 'update'])->name('shelter-profile.update');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth');`,
  },
  {
    path: 'app/Http/Controllers/Admin/PetController.php',
    category: 'Controllers',
    language: 'php',
    content: `<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = Pet::query();

        if ($request->filled('type') && $request->type !== 'All') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }

        $pets = $query->latest()->paginate(10)->withQueryString();
        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        $types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
        $genders = ['Male', 'Female', 'Unknown'];
        $statuses = ['Available', 'Pending', 'Adopted'];
        return view('admin.pets.create', compact('types', 'genders', 'statuses'));
    }

    public function store(StorePetRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('pets', 'public');
        }

        Pet::create($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet profile created successfully!');
    }

    public function edit(Pet $pet)
    {
        $types = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other'];
        $genders = ['Male', 'Female', 'Unknown'];
        $statuses = ['Available', 'Pending', 'Adopted'];
        return view('admin.pets.edit', compact('pet', 'types', 'genders', 'statuses'));
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($pet->image_path && Storage::disk('public')->exists($pet->image_path)) {
                Storage::disk('public')->delete($pet->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('pets', 'public');
        }

        $pet->update($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet profile updated successfully!');
    }

    public function destroy(Pet $pet)
    {
        if ($pet->image_path && Storage::disk('public')->exists($pet->image_path)) {
            Storage::disk('public')->delete($pet->image_path);
        }
        $pet->delete();

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet profile deleted successfully!');
    }
}`,
  },
  {
    path: 'app/Http/Requests/StorePetRequest.php',
    category: 'Requests',
    language: 'php',
    content: `<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Dog,Cat,Bird,Rabbit,Other'],
            'breed' => ['nullable', 'string', 'max:255'],
            'age' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:Male,Female,Unknown'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:Available,Pending,Adopted'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ];
    }
}`,
  },
  {
    path: 'database/migrations/2024_01_01_000003_create_pets_table.php',
    category: 'Migrations & Seeders',
    language: 'php',
    content: `<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other']);
            $table->string('breed')->nullable();
            $table->string('age');
            $table->enum('gender', ['Male', 'Female', 'Unknown']);
            $table->text('description');
            $table->enum('status', ['Available', 'Pending', 'Adopted'])->default('Available');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};`,
  },
  {
    path: 'database/seeders/DatabaseSeeder.php',
    category: 'Migrations & Seeders',
    language: 'php',
    content: `<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\ShelterProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Single default Owner account
        User::updateOrCreate(
            ['email' => 'admin@adoptioncenter.com'],
            [
                'name' => 'Shelter Director',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Initial shelter_profiles record
        ShelterProfile::updateOrCreate(
            ['id' => 1],
            [
                'shelter_name' => 'Haven Paws Animal Adoption Center',
                'tagline' => 'Where Every Pet Finds A Forever Family',
                'bio' => 'Founded in 2018, Haven Paws is a non-profit cage-free rescue sanctuary...',
                'phone' => '(555) 234-5678',
                'email' => 'adoptions@havenpaws.org',
                'address' => '742 Evergreen Terrace, Springfield, OR 97477',
                'opening_hours' => 'Tuesday – Sunday: 10:00 AM – 5:30 PM (Closed Mondays)',
                'banner_image_path' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1400&q=80',
            ]
        );

        // 3. Seed 6-8 sample pets across various statuses and types
        // ... (See full file in /laravel/database/seeders/DatabaseSeeder.php)
    }
}`,
  },
  {
    path: 'app/Models/Pet.php',
    category: 'Models',
    language: 'php',
    content: `<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'breed',
        'age',
        'gender',
        'description',
        'status',
        'image_path',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }
        if ($this->image_path && (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://'))) {
            return $this->image_path;
        }
        return 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=600&q=80';
    }
}`,
  },
  {
    path: 'resources/views/admin/dashboard.blade.php',
    category: 'Blade Views',
    language: 'html',
    content: `@extends('layouts.admin')

@section('title', 'Owner Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-stone-900">Welcome, {{ Auth::user()->name }} 👋</h1>
        <a href="{{ route('admin.pets.create') }}" class="rounded-xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white">
            + Add New Pet
        </a>
    </div>

    <!-- 4 Key Metrics: Total, Available, Pending, Adopted -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
        <div class="rounded-2xl bg-white p-6 border border-stone-200">
            <span class="text-xs font-bold text-stone-400">Total Pets</span>
            <p class="text-3xl font-bold text-stone-900 mt-2">{{ $totalPets }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 border border-emerald-200">
            <span class="text-xs font-bold text-emerald-600">Available</span>
            <p class="text-3xl font-bold text-emerald-700 mt-2">{{ $availablePets }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 border border-amber-200">
            <span class="text-xs font-bold text-amber-700">Pending</span>
            <p class="text-3xl font-bold text-amber-700 mt-2">{{ $pendingPets }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 border border-stone-200">
            <span class="text-xs font-bold text-stone-500">Adopted</span>
            <p class="text-3xl font-bold text-stone-700 mt-2">{{ $adoptedPets }}</p>
        </div>
    </div>
</div>
@endsection`,
  },
  {
    path: 'composer.json',
    category: 'Config',
    language: 'json',
    content: `{
    "name": "laravel/animal-adoption-center",
    "type": "project",
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0"
    }
}`,
  },
];

interface LaravelCodeInspectorProps {
  isOpen: boolean;
  onClose: () => void;
}

export const LaravelCodeInspector: React.FC<LaravelCodeInspectorProps> = ({
  isOpen,
  onClose,
}) => {
  const [selectedFile, setSelectedFile] = useState<CodeFile>(LARAVEL_FILES[0]);
  const [copied, setCopied] = useState(false);

  if (!isOpen) return null;

  const handleCopy = () => {
    navigator.clipboard.writeText(selectedFile.content);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-stone-950/70 backdrop-blur-xs p-3 sm:p-6 animate-fade-in">
      <div className="relative flex flex-col h-[88vh] w-full max-w-6xl rounded-3xl bg-stone-900 border border-stone-800 shadow-2xl overflow-hidden text-stone-300">
        {/* Modal Top Bar */}
        <div className="flex items-center justify-between px-6 py-4 border-b border-stone-800 bg-stone-950">
          <div className="flex items-center gap-3">
            <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-red-600 to-amber-600 text-white font-bold text-sm shadow-md">
              L11
            </span>
            <div>
              <h3 className="font-extrabold text-white text-base leading-tight">
                Laravel 11 Codebase Inspector
              </h3>
              <p className="text-xs text-stone-400">
                Complete project files written to <code className="text-amber-400 font-mono">/laravel</code> directory
              </p>
            </div>
          </div>

          <div className="flex items-center gap-3">
            <button
              onClick={handleCopy}
              className="inline-flex items-center gap-1.5 rounded-xl bg-stone-800 hover:bg-stone-700 px-3.5 py-1.5 text-xs font-semibold text-white transition-colors"
            >
              {copied ? (
                <>
                  <Check className="h-3.5 w-3.5 text-emerald-400" />
                  <span className="text-emerald-400">Copied!</span>
                </>
              ) : (
                <>
                  <Copy className="h-3.5 w-3.5" />
                  <span>Copy Code</span>
                </>
              )}
            </button>

            <button
              onClick={onClose}
              className="rounded-xl p-1.5 text-stone-400 hover:bg-stone-800 hover:text-white transition-colors"
            >
              <X className="h-5 w-5" />
            </button>
          </div>
        </div>

        {/* Main Workspace */}
        <div className="flex flex-1 overflow-hidden">
          {/* Sidebar: File Tree */}
          <div className="w-64 sm:w-72 border-r border-stone-800 bg-stone-950/60 p-4 overflow-y-auto space-y-4">
            <div className="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-stone-500 px-2">
              <FolderTree className="h-3.5 w-3.5" />
              <span>Project Structure</span>
            </div>

            <div className="space-y-1">
              {LARAVEL_FILES.map((file) => (
                <button
                  key={file.path}
                  onClick={() => setSelectedFile(file)}
                  className={`w-full text-left px-3 py-2 rounded-xl text-xs font-medium transition-all flex items-center justify-between ${
                    selectedFile.path === file.path
                      ? 'bg-amber-500/20 text-amber-300 font-bold border border-amber-500/30'
                      : 'text-stone-400 hover:bg-stone-800/60 hover:text-stone-200'
                  }`}
                >
                  <div className="truncate flex items-center gap-2">
                    <FileCode className="h-3.5 w-3.5 flex-shrink-0 text-stone-500" />
                    <span className="truncate">{file.path}</span>
                  </div>
                </button>
              ))}
            </div>

            <div className="pt-4 border-t border-stone-800 text-[11px] text-stone-500 space-y-2 px-2">
              <p className="font-semibold text-stone-400">Export Information:</p>
              <p>
                All files exist directly in the container filesystem under <code className="text-amber-400">/laravel</code> ready for <code className="text-stone-300">php artisan</code> usage.
              </p>
            </div>
          </div>

          {/* Code Display */}
          <div className="flex-1 flex flex-col bg-[#141517] overflow-hidden">
            <div className="px-6 py-2.5 border-b border-stone-800 flex items-center justify-between text-xs bg-stone-900/60 text-stone-400">
              <span className="font-mono text-amber-400">{selectedFile.path}</span>
              <span className="rounded bg-stone-800 px-2 py-0.5 text-[10px] text-stone-300 uppercase font-semibold">
                {selectedFile.category}
              </span>
            </div>

            <pre className="flex-1 p-6 font-mono text-xs overflow-auto leading-relaxed text-stone-300 selection:bg-amber-600/30">
              <code>{selectedFile.content}</code>
            </pre>
          </div>
        </div>
      </div>
    </div>
  );
};
