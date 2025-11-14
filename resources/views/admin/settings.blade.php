@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            @php
                $categories = [
                    'upload' => [
                        'title' => 'Pengaturan Upload',
                        'icon' => 'fa-cloud-upload-alt',
                        'color' => 'success',
                        'keys' => ['upload_max_file_size', 'upload_allowed_extensions', 'upload_image_quality']
                    ],
                    'display' => [
                        'title' => 'Pengaturan Tampilan',
                        'icon' => 'fa-desktop',
                        'color' => 'info',
                        'keys' => ['items_per_page', 'enable_public_upload', 'auto_approve_content']
                    ],
                    'gallery_user' => [
                        'title' => 'Pengaturan Galeri User',
                        'icon' => 'fa-users',
                        'color' => 'primary',
                        'keys' => ['gallery_require_login', 'gallery_auto_approve_comments']
                    ]
                ];
                
                $allSettings = $settingsGrouped->flatten()->keyBy('key');
            @endphp
            
            @foreach($categories as $catKey => $category)
                <div class="table-card mb-4">
                    <div class="table-card-header bg-{{ $category['color'] }} bg-gradient text-white">
                        <h5 class="mb-0">
                            <i class="fas {{ $category['icon'] }} me-2"></i>{{ $category['title'] }}
                        </h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            @foreach($category['keys'] as $key)
                                @if(isset($allSettings[$key]))
                                    @php $setting = $allSettings[$key]; @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="setting-item">
                                            <label for="{{ $setting->key }}" class="form-label fw-bold text-dark">
                                                {{ $setting->label }}
                                            </label>
                                            
                                            @if($setting->type === 'number')
                                                <div class="input-group">
                                                    <input type="number" 
                                                           class="form-control form-control-lg" 
                                                           id="{{ $setting->key }}" 
                                                           name="{{ $setting->key }}" 
                                                           value="{{ old($setting->key, $setting->value) }}"
                                                           min="0">
                                                    @if(str_contains($setting->key, 'size'))
                                                        <span class="input-group-text">KB</span>
                                                    @elseif(str_contains($setting->key, 'quality'))
                                                        <span class="input-group-text">%</span>
                                                    @endif
                                                </div>
                                            @elseif($setting->type === 'boolean')
                                                <div class="form-check form-switch" style="padding-left: 3rem;">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           role="switch"
                                                           id="{{ $setting->key }}" 
                                                           name="{{ $setting->key }}" 
                                                           value="1"
                                                           style="width: 3rem; height: 1.5rem; cursor: pointer;"
                                                           {{ old($setting->key, $setting->value) ? 'checked' : '' }}>
                                                    <label class="form-check-label ms-2" for="{{ $setting->key }}" style="cursor: pointer;">
                                                        <span class="badge bg-{{ old($setting->key, $setting->value) ? 'success' : 'secondary' }}">
                                                            {{ old($setting->key, $setting->value) ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </label>
                                                </div>
                                            @else
                                                <input type="text" 
                                                       class="form-control form-control-lg" 
                                                       id="{{ $setting->key }}" 
                                                       name="{{ $setting->key }}" 
                                                       value="{{ old($setting->key, $setting->value) }}">
                                            @endif
                                            
                                            @if($setting->description)
                                                <small class="text-muted d-block mt-2">
                                                    <i class="fas fa-info-circle me-1"></i>{{ $setting->description }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Simpan Pengaturan
                </button>
            </div>
        </div>
    
    <div class="col-lg-4">
        <div class="table-card">
            <div class="table-card-header">
                <h5><i class="fas fa-server me-2"></i>Informasi Sistem</h5>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <strong>Total Foto:</strong><br>
                    <span class="text-muted">{{ $systemInfo['total_photos'] }} foto</span>
                </div>
                <div class="mb-3">
                    <strong>Storage Digunakan:</strong><br>
                    <span class="text-muted">{{ $systemInfo['storage_used'] }}</span>
                </div>
                <div class="mb-3">
                    <strong>Laravel Version:</strong><br>
                    <span class="text-muted">{{ $systemInfo['laravel_version'] }}</span>
                </div>
                <div class="mb-3">
                    <strong>PHP Version:</strong><br>
                    <span class="text-muted">{{ $systemInfo['php_version'] }}</span>
                </div>
                <div class="mb-3">
                    <strong>Database:</strong><br>
                    <span class="text-muted">{{ $systemInfo['database_driver'] }}</span>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-warning w-100" onclick="clearCache()">
                        <i class="fas fa-broom me-1"></i>Clear Cache
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('styles')
<style>
.setting-item {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
    height: 100%;
}

.setting-item:hover {
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-color: #4e73df;
}

.form-control-lg {
    font-size: 1rem;
    padding: 0.75rem 1rem;
}

.input-group-text {
    background: #4e73df;
    color: white;
    border: none;
    font-weight: 600;
}

.form-check-input:checked {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

.table-card-header.bg-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
}

.table-card-header.bg-success {
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%) !important;
}

.table-card-header.bg-info {
    background: linear-gradient(135deg, #36b9cc 0%, #258391 100%) !important;
}

.btn-lg {
    padding: 12px 30px;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection

@section('scripts')
<script>
// Update badge status when toggle changes
document.querySelectorAll('.form-check-input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const badge = this.nextElementSibling.querySelector('.badge');
        if (this.checked) {
            badge.classList.remove('bg-secondary');
            badge.classList.add('bg-success');
            badge.textContent = 'Aktif';
        } else {
            badge.classList.remove('bg-success');
            badge.classList.add('bg-secondary');
            badge.textContent = 'Nonaktif';
        }
    });
});

function clearCache() {
    if (confirm('Yakin ingin membersihkan cache?')) {
        fetch('{{ route('admin.settings.clear-cache') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}
</script>
@endsection