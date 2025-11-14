@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <p class="text-muted mb-0">Selamat datang di dashboard admin</p>
        </div>
        <!-- Tombol Tambah Foto dihapus -->
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card primary h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-primary-light me-3">
                        <i class="fas fa-images text-primary"></i>
                    </div>
                    <div>
                        <div class="stat-text">Total Foto</div>
                        <div class="stat-number">{{ $totalPhotos }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card success h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-success-light me-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <div class="stat-text">Foto Aktif</div>
                        <div class="stat-number">{{ $activePhotos }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card warning h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-warning-light me-3">
                        <i class="fas fa-eye-slash text-warning"></i>
                    </div>
                    <div>
                        <div class="stat-text">Foto Nonaktif</div>
                        <div class="stat-number">{{ $inactivePhotos }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card info h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-info-light me-3">
                        <i class="fas fa-calendar text-info"></i>
                    </div>
                    <div>
                        <div class="stat-text">Foto Hari Ini</div>
                        <div class="stat-number">{{ $todayPhotos }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <!-- Recent Photos -->
            <div class="table-card">
                <div class="table-card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <h5><i class="fas fa-clock me-2"></i>Foto Terbaru</h5>
                    <a href="{{ route('admin.galleries.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPhotos as $photo)
                            <tr>
                                <td>
                                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>
                                    <strong>{{ Str::limit($photo->title, 20) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $categoryNames[$photo->category] ?? $photo->category }}</span>
                                </td>
                                <td>
                                    @if($photo->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $photo->created_at->format('d M Y') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-images fa-2x mb-2"></i><br>
                                    Belum ada foto
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <!-- Categories Chart -->
            <div class="table-card mb-4">
                <div class="table-card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Distribusi Kategori</h5>
                </div>
                <div class="p-3">
                    <canvas id="galleryPieChart" height="250"></canvas>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="fas fa-chart-bar me-2"></i>Statistik Mingguan</h5>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Minggu Ini</span>
                        <strong>{{ $thisWeekPhotos }} foto</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Bulan Ini</span>
                        <strong>{{ $thisMonthPhotos }} foto</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total</span>
                        <strong>{{ $totalPhotos }} foto</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus foto ini?</p>
                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deletePhoto(id) {
    document.getElementById('deleteForm').action = `/admin/galleries/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    const ctx = document.getElementById('galleryPieChart');
    if (ctx) {
        // Pastikan variabel photosByCategory terdefinisi
        const categories = @json(isset($photosByCategory) ? $photosByCategory : []);
        
        // Hanya buat chart jika ada data
        if (categories && categories.length > 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: categories.map(item => item.name || ''),
                    datasets: [{
                        data: categories.map(item => item.count || 0),
                        backgroundColor: categories.map(item => item.color || '#cccccc'),
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%',
                borderRadius: 5
            }
            });
        } else {
            // Jika tidak ada data, sembunyikan atau tampilkan pesan
            ctx.closest('.card').innerHTML = `
                <div class="card-body text-center py-4">
                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data kategori galeri yang tersedia</p>
                </div>`;
        }
    }
});
</script>
@endsection