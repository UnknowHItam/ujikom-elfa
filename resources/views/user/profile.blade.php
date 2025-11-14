@extends('layouts.user')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-description', 'Kelola informasi profil Anda')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="fas fa-user me-2"></i>Informasi Profil</h5>
            </div>
            <div class="p-4 text-center">
                <div class="mb-3">
                    @if($user->profile_photo)
                        <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" 
                             class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #4e73df;">
                    @else
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; border: 4px solid #4e73df;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    @endif
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                @if($user->username)
                    <p class="text-muted mb-1"><small>{{ '@' . $user->username }}</small></p>
                @endif
                <span class="badge bg-success mt-2">User Active</span>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="fas fa-user-edit me-2"></i>Edit Profil</h5>
            </div>
            <div class="p-4">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Foto Profil</label>
                        @if($user->profile_photo)
                            <div class="mb-2">
                                <img src="{{ asset($user->profile_photo) }}" alt="Current Photo" 
                                     class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #e1e5eb;">
                                <p class="text-muted small mt-1 mb-0">Foto profil saat ini</p>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                               id="profile_photo" name="profile_photo" accept="image/*" onchange="previewPhoto(event)">
                        <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="photoPreview" class="mt-2" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="rounded-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #4e73df;">
                            <p class="text-success small mt-1"><i class="fas fa-check-circle me-1"></i>Preview foto baru</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Masukkan nama lengkap Anda" required>
                        <small class="text-muted">Nama yang akan ditampilkan di profil Anda</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                   id="username" name="username" 
                                   value="{{ old('username', $user->username) }}" 
                                   placeholder="Masukkan username" required>
                        </div>
                        <small class="text-muted">Username unik untuk identifikasi Anda</small>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-lock me-2"></i>Ganti Password (Opsional)</h6>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" 
                               placeholder="Masukkan password lama">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" name="new_password" 
                               placeholder="Masukkan password baru">
                        <small class="text-muted">Minimal 8 karakter</small>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewPhoto(event) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('photoPreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewDiv.style.display = 'none';
    }
}
</script>
@endsection


