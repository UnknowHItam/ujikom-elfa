@extends('layouts.admin')

@section('title', 'Edit Halaman Kontak')
@section('page-title', 'Edit Halaman Kontak')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-edit me-2"></i>Form Edit Halaman Kontak</h5>
                <a href="{{ route('contact') }}" target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-eye me-1"></i>Lihat Halaman
                </a>
            </div>
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.contact.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Header Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-heading me-2"></i>Header Halaman</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $contact->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subtitle') is-invalid @enderror" 
                                           id="subtitle" name="subtitle" value="{{ old('subtitle', $contact->subtitle) }}" required>
                                    @error('subtitle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Informasi Kontak</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" required>{{ old('address', $contact->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telepon Utama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $contact->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone_alt" class="form-label">Telepon Alternatif</label>
                                    <input type="text" class="form-control @error('phone_alt') is-invalid @enderror" 
                                           id="phone_alt" name="phone_alt" value="{{ old('phone_alt', $contact->phone_alt) }}">
                                    @error('phone_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Utama <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $contact->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email_alt" class="form-label">Email Alternatif</label>
                                    <input type="email" class="form-control @error('email_alt') is-invalid @enderror" 
                                           id="email_alt" name="email_alt" value="{{ old('email_alt', $contact->email_alt) }}">
                                    @error('email_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Office Hours Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Jam Operasional</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="office_hours_weekday" class="form-label">Senin - Jumat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('office_hours_weekday') is-invalid @enderror" 
                                       id="office_hours_weekday" name="office_hours_weekday" 
                                       value="{{ old('office_hours_weekday', $contact->office_hours_weekday) }}" 
                                       placeholder="07:00 - 16:00 WIB" required>
                                @error('office_hours_weekday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="office_hours_saturday" class="form-label">Sabtu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('office_hours_saturday') is-invalid @enderror" 
                                       id="office_hours_saturday" name="office_hours_saturday" 
                                       value="{{ old('office_hours_saturday', $contact->office_hours_saturday) }}" 
                                       placeholder="07:00 - 12:00 WIB" required>
                                @error('office_hours_saturday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="office_hours_sunday" class="form-label">Minggu & Libur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('office_hours_sunday') is-invalid @enderror" 
                                       id="office_hours_sunday" name="office_hours_sunday" 
                                       value="{{ old('office_hours_sunday', $contact->office_hours_sunday) }}" 
                                       placeholder="Tutup" required>
                                @error('office_hours_sunday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Catatan</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" 
                                          id="note" name="note" rows="2">{{ old('note', $contact->note) }}</textarea>
                                <small class="text-muted">Catatan tambahan untuk pengunjung</small>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection