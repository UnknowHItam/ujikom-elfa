@extends('layouts.admin')

@section('title', 'Notifikasi Aktivitas User')
@section('page-title', 'Notifikasi Aktivitas User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-bell me-2"></i>Aktivitas User Terbaru</h5>
                <div>
                    <span class="badge bg-primary">{{ $activities->count() }} aktivitas</span>
                </div>
            </div>
            <div class="table-responsive">
                @if($activities->count() > 0)
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Foto</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($activity['user']->profile_photo)
                                        <img src="{{ asset($activity['user']->profile_photo) }}" 
                                             alt="Profile" class="rounded-circle me-3" width="40" height="40">
                                    @else
                                        <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $activity['user']->name }}</div>
                                        <small class="text-muted">
                                            @if($activity['user']->username)
                                                @<?php echo $activity['user']->username; ?>
                                            @else
                                                <span class="text-muted fst-italic">Tidak ada username</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-{{ $activity['color'] }} me-2">
                                        <i class="fas fa-{{ $activity['icon'] }}"></i>
                                    </div>
                                    <span>{{ $activity['message'] }}: {{ Str::limit($activity['comment'] ?? '', 50) }}</span>
                                </div>
                            </td>
                            <td>
                                @if($activity['gallery']->image_path)
                                    <div class="position-relative" style="width: 80px; height: 60px;">
                                        <img src="{{ asset('storage/' . $activity['gallery']->image_path) }}" 
                                             alt="Gallery" class="img-fluid rounded" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <small class="d-block mt-1 text-truncate" style="max-width: 100px;">{{ $activity['gallery']->title }}</small>
                            </td>
                            <td>
                                <small>{{ $activity['created_at']->diffForHumans() }}</small>
                                <div class="text-muted small">{{ $activity['created_at']->format('d M Y H:i') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada aktivitas user</h5>
                    <p class="text-muted">Belum ada user yang melakukan like atau komentar pada foto galeri.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection