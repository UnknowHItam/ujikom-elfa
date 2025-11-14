@extends('layouts.user')

@section('title', 'Galeri Sekolah')

@section('styles')
<style>
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 20px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }
        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 60px 0 40px;
            margin-bottom: 0;
            box-shadow: 0 4px 20px rgba(78, 115, 223, 0.3);
        }

        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 30px 0;
        }
        .loading {
            display: none;
        }
        .modal-body img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="page-title">
                    <i class="fas fa-images me-3"></i>Galeri Sekolah
                </h1>
                <p class="page-subtitle">Koleksi foto kegiatan dan momen berharga di SMK NEGERI 4 BOGOR</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

    @guest
        <div class="alert alert-info alert-dismissible fade show m-0" role="alert" style="border-radius: 0; border: none;">
            <div class="container">
                <i class="fas fa-info-circle me-2"></i>
                Anda masuk sebagai <strong>pengunjung</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endguest
    
    @auth
    @endauth

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <select class="form-select" id="categoryFilter">
                                <option value="all">Semua Kategori</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari foto...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="container my-5">
        <div class="loading text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        <div id="galleryContainer" class="row g-4">
            <!-- Gallery items will be loaded here -->
        </div>

        <!-- Pagination -->
        <nav aria-label="Gallery pagination" class="mt-5">
            <ul class="pagination justify-content-center" id="pagination">
                <!-- Pagination will be loaded here -->
            </ul>
        </nav>
    </div>

    <!-- Add Photo Modal -->
    <div class="modal fade" id="addPhotoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Foto Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addPhotoForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Photo Detail Modal -->
    <div class="modal fade" id="photoDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <img id="photoImage" src="" alt="" class="mb-3 w-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary" id="photoCategory"></span>
                                <small class="text-muted" id="photoDate"></small>
                            </div>
                            <p id="photoDescription"></p>
                            
                            <!-- Like/Dislike/Download Buttons - Selalu tampil -->
                            <div class="d-flex gap-2 mb-3">
                                <button class="btn btn-outline-success" id="likeBtn" onclick="toggleLike('like')">
                                    <i class="fas fa-thumbs-up"></i> <span id="likeCount">0</span>
                                </button>
                                <button class="btn btn-outline-danger" id="dislikeBtn" onclick="toggleLike('dislike')">
                                    <i class="fas fa-thumbs-down"></i> <span id="dislikeCount">0</span>
                                </button>
                                <button class="btn btn-outline-primary ms-auto" onclick="showDownloadModal()">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                            
                            @guest
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <a href="{{ route('login') }}" class="alert-link">Login</a> untuk memberikan like/dislike dan download foto
                            </div>
                            @endguest
                        </div>
                        
                        <div class="col-md-5">
                            <h6 class="mb-3">Komentar (<span id="commentsCount">0</span>)</h6>
                            
                            <!-- Comment Form - Hanya untuk user login -->
                            @auth
                            <form id="commentForm" class="mb-3">
                                <div class="mb-2">
                                    <textarea class="form-control form-control-sm" id="commentText" rows="3" placeholder="Tulis komentar..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
                            </form>
                            @else
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-lock me-2"></i>
                                <a href="{{ route('login') }}" class="alert-link">Login</a> untuk menambahkan komentar
                            </div>
                            @endauth
                            
                            <!-- Comments List - Selalu tampil untuk semua user -->
                            <div id="commentsList" style="max-height: 400px; overflow-y: auto;">
                                <!-- Comments will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
        let currentPage = 1;
        let currentCategory = 'all';
        let currentSearch = '';
        let currentSort = 'latest';

        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch('/api/galleries/categories');
                const data = await response.json();
                
                if (data.success) {
                    const categoryFilter = document.getElementById('categoryFilter');
                    const categorySelect = document.getElementById('category');
                    
                    Object.entries(data.data).forEach(([key, value]) => {
                        const option1 = new Option(value, key);
                        const option2 = new Option(value, key);
                        categoryFilter.add(option1);
                        categorySelect.add(option2);
                    });
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Load gallery
        async function loadGallery(page = 1) {
            const loading = document.querySelector('.loading');
            const container = document.getElementById('galleryContainer');
            
            loading.style.display = 'block';
            container.innerHTML = '';

            try {
                const params = new URLSearchParams({
                    page: page,
                    category: currentCategory,
                    search: currentSearch
                });

                const response = await fetch(`/api/galleries?${params}`);
                const data = await response.json();

                if (data.success) {
                    displayGallery(data.data.data);
                    displayPagination(data.data);
                } else {
                    container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Tidak ada foto ditemukan.</p></div>';
                }
            } catch (error) {
                console.error('Error loading gallery:', error);
                container.innerHTML = '<div class="col-12 text-center"><p class="text-danger">Error loading gallery.</p></div>';
            } finally {
                loading.style.display = 'none';
            }
        }

        // Display gallery items
        function displayGallery(galleries) {
            const container = document.getElementById('galleryContainer');
            
            if (galleries.length === 0) {
                container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Tidak ada foto ditemukan.</p></div>';
                return;
            }

            galleries.forEach(gallery => {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-lg-3';
                
                col.innerHTML = `
                    <div class="gallery-item" onclick="showPhotoDetail(${gallery.id})">
                        <img src="${gallery.image_url}" alt="${gallery.title}" loading="lazy">
                        <div class="category-badge">${getCategoryName(gallery.category)}</div>
                        <div class="gallery-overlay">
                            <h6 class="mb-1">${gallery.title}</h6>
                            <small>${gallery.description || 'Tidak ada deskripsi'}</small>
                        </div>
                    </div>
                `;
                
                container.appendChild(col);
            });
        }

        // Display pagination
        function displayPagination(paginationData) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const { current_page, last_page, prev_page_url, next_page_url } = paginationData;

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${!prev_page_url ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="loadGallery(${current_page - 1})">Previous</a>`;
            pagination.appendChild(prevLi);

            // Page numbers
            for (let i = 1; i <= last_page; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === current_page ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#" onclick="loadGallery(${i})">${i}</a>`;
                pagination.appendChild(li);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${!next_page_url ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="loadGallery(${current_page + 1})">Next</a>`;
            pagination.appendChild(nextLi);
        }

        // Show photo detail
        let currentPhotoId = null;
        
        async function showPhotoDetail(id) {
            currentPhotoId = id;
            
            try {
                const response = await fetch(`/api/galleries/${id}`);
                const data = await response.json();

                if (data.success) {
                    const gallery = data.data;
                    document.getElementById('photoTitle').textContent = gallery.title;
                    document.getElementById('photoImage').src = gallery.image_url;
                    document.getElementById('photoImage').alt = gallery.title;
                    document.getElementById('photoDescription').textContent = gallery.description || 'Tidak ada deskripsi';
                    document.getElementById('photoCategory').textContent = getCategoryName(gallery.category);
                    document.getElementById('photoDate').textContent = new Date(gallery.created_at).toLocaleDateString('id-ID');
                    
                    // Load like/dislike status
                    loadLikeStatus(id);
                    
                    // Load comments
                    loadComments(id);
                    
                    new bootstrap.Modal(document.getElementById('photoDetailModal')).show();
                }
            } catch (error) {
                console.error('Error loading photo detail:', error);
            }
        }

        // Load like/dislike status
        async function loadLikeStatus(galleryId) {
            try {
                const response = await fetch(`/api/galleries/${galleryId}/like-status`);
                const data = await response.json();

                if (data.success) {
                    document.getElementById('likeCount').textContent = data.likes_count;
                    document.getElementById('dislikeCount').textContent = data.dislikes_count;
                    
                    // Update button states
                    const likeBtn = document.getElementById('likeBtn');
                    const dislikeBtn = document.getElementById('dislikeBtn');
                    
                    likeBtn.classList.remove('btn-success');
                    dislikeBtn.classList.remove('btn-danger');
                    likeBtn.classList.add('btn-outline-success');
                    dislikeBtn.classList.add('btn-outline-danger');
                    
                    if (data.user_type === 'like') {
                        likeBtn.classList.remove('btn-outline-success');
                        likeBtn.classList.add('btn-success');
                    } else if (data.user_type === 'dislike') {
                        dislikeBtn.classList.remove('btn-outline-danger');
                        dislikeBtn.classList.add('btn-danger');
                    }
                }
            } catch (error) {
                console.error('Error loading like status:', error);
            }
        }

        // Toggle like/dislike
        async function toggleLike(type) {
            if (!currentPhotoId) return;
            
            @guest
            alert('Anda harus login terlebih dahulu untuk memberikan like/dislike');
            window.location.href = '{{ route("login") }}';
            return;
            @endguest
            
            try {
                const response = await fetch(`/api/galleries/${currentPhotoId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ type: type })
                });

                const data = await response.json();

                if (data.success) {
                    loadLikeStatus(currentPhotoId);
                } else {
                    alert(data.message || 'Gagal memberikan like/dislike');
                    if (response.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    }
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Load comments
        async function loadComments(galleryId) {
            try {
                const response = await fetch(`/api/galleries/${galleryId}/comments`);
                const data = await response.json();

                if (data.success) {
                    const commentsList = document.getElementById('commentsList');
                    const commentsCount = document.getElementById('commentsCount');
                    
                    commentsList.innerHTML = '';
                    commentsCount.textContent = data.data.length;
                    
                    if (data.data.length === 0) {
                        commentsList.innerHTML = '<p class="text-muted small">Belum ada komentar</p>';
                        return;
                    }
                    
                    data.data.forEach(comment => {
                        const commentDiv = document.createElement('div');
                        commentDiv.className = 'border-bottom pb-2 mb-2';
                        commentDiv.dataset.commentId = comment.id;
                        
                        // Foto profil user
                        let userPhoto = '';
                        if (comment.user && comment.user.profile_photo) {
                            userPhoto = `<img src="{{ asset('') }}${comment.user.profile_photo}" alt="${comment.user.name}" 
                                             class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">`;
                        } else {
                            userPhoto = `<div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                        </div>`;
                        }
                        
                        // Cek apakah komentar milik user yang sedang login
                        const isOwnComment = {{ auth()->check() ? 'true' : 'false' }} && comment.user_id === {{ auth()->id() ?? 0 }};
                        
                        // Tombol aksi untuk komentar milik user
                        let actionButtons = '';
                        if (isOwnComment) {
                            actionButtons = `
                                <div class="mt-1">
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editComment(${comment.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                        
                        commentDiv.innerHTML = `
                            <div class="d-flex align-items-start">
                                ${userPhoto}
                                <div class="flex-grow-1">
                                    <strong class="d-block">${comment.user ? comment.user.name : 'Anonymous'}</strong>
                                    <small class="text-muted">${new Date(comment.created_at).toLocaleDateString('id-ID')}</small>
                                    <p class="mb-0 mt-1 comment-text">${comment.comment}</p>
                                    ${actionButtons}
                                </div>
                            </div>
                        `;
                        commentsList.appendChild(commentDiv);
                    });
                }
            } catch (error) {
                console.error('Error loading comments:', error);
            }
        }

        // Submit comment
        @auth
        document.getElementById('commentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentPhotoId) return;
            
            const comment = document.getElementById('commentText').value;
            
            try {
                const response = await fetch(`/api/galleries/${currentPhotoId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        comment: comment
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.reset();
                    loadComments(currentPhotoId);
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Komentar berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    document.getElementById('commentForm').insertAdjacentElement('beforebegin', alertDiv);
                    
                    setTimeout(() => alertDiv.remove(), 3000);
                } else {
                    alert(data.message || 'Gagal mengirim komentar');
                    if (response.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    }
                }
            } catch (error) {
                console.error('Error submitting comment:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
        @endauth

        // Edit comment
        async function editComment(commentId) {
            if (!currentPhotoId) return;
            
            // Temukan elemen komentar
            const commentDiv = document.querySelector(`[data-comment-id="${commentId}"]`);
            const commentText = commentDiv.querySelector('.comment-text');
            
            // Simpan teks asli
            const originalText = commentText.textContent;
            
            // Buat form edit
            const editForm = document.createElement('form');
            editForm.className = 'mt-2';
            editForm.innerHTML = `
                <div class="mb-2">
                    <textarea class="form-control form-control-sm" rows="3">${originalText}</textarea>
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit(${commentId})">Batal</button>
                </div>
            `;
            
            // Ganti teks dengan form edit
            commentText.replaceWith(editForm);
            
            // Handle submit form
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const newComment = this.querySelector('textarea').value;
                
                try {
                    const response = await fetch(`/api/galleries/${currentPhotoId}/comments/${commentId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            comment: newComment
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Ganti form dengan teks baru
                        const newText = document.createElement('p');
                        newText.className = 'mb-0 mt-1 comment-text';
                        newText.textContent = newComment;
                        editForm.replaceWith(newText);
                        
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-2';
                        alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Komentar berhasil diupdate!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                        commentDiv.querySelector('.flex-grow-1').appendChild(alertDiv);
                        setTimeout(() => alertDiv.remove(), 3000);
                    } else {
                        alert(data.message || 'Gagal mengupdate komentar');
                        
                        // Kembalikan ke teks asli
                        const newText = document.createElement('p');
                        newText.className = 'mb-0 mt-1 comment-text';
                        newText.textContent = originalText;
                        editForm.replaceWith(newText);
                    }
                } catch (error) {
                    console.error('Error updating comment:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    
                    // Kembalikan ke teks asli
                    const newText = document.createElement('p');
                    newText.className = 'mb-0 mt-1 comment-text';
                    newText.textContent = originalText;
                    editForm.replaceWith(newText);
                }
            });
        }

        // Cancel edit comment
        function cancelEdit(commentId) {
            const commentDiv = document.querySelector(`[data-comment-id="${commentId}"]`);
            const editForm = commentDiv.querySelector('form');
            
            // Kembalikan ke teks asli
            const originalText = editForm.querySelector('textarea').value;
            const newText = document.createElement('p');
            newText.className = 'mb-0 mt-1 comment-text';
            newText.textContent = originalText;
            editForm.replaceWith(newText);
        }

        // Delete comment
        async function deleteComment(commentId) {
            if (!currentPhotoId) return;
            
            if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/galleries/${currentPhotoId}/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    // Hapus komentar dari DOM
                    const commentDiv = document.querySelector(`[data-comment-id="${commentId}"]`);
                    commentDiv.remove();
                    
                    // Update jumlah komentar
                    const commentsCount = document.getElementById('commentsCount');
                    commentsCount.textContent = parseInt(commentsCount.textContent) - 1;
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Komentar berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    document.getElementById('commentForm').insertAdjacentElement('beforebegin', alertDiv);
                    setTimeout(() => alertDiv.remove(), 3000);
                } else {
                    alert(data.message || 'Gagal menghapus komentar');
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Get category name
        function getCategoryName(category) {
            const categories = {
                'academic': 'Akademik',
                'extracurricular': 'Ekstrakurikuler',
                'event': 'Acara',
                'common': 'Umum'
            };
            return categories[category] || category;
        }

        // Add photo
        document.getElementById('addPhotoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/api/galleries', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('addPhotoModal')).hide();
                    this.reset();
                    loadGallery(currentPage);
                    alert('Foto berhasil ditambahkan!');
                } else {
                    alert('Error: ' + (data.message || 'Gagal menambahkan foto'));
                }
            } catch (error) {
                console.error('Error adding photo:', error);
                alert('Error: Gagal menambahkan foto');
            }
        });

        // Event listeners
        document.getElementById('categoryFilter').addEventListener('change', function() {
            currentCategory = this.value;
            loadGallery(1);
        });

        document.getElementById('searchBtn').addEventListener('click', function() {
            currentSearch = document.getElementById('searchInput').value;
            loadGallery(1);
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentSearch = this.value;
                loadGallery(1);
            }
        });

        // Download function - simplified without CAPTCHA
        async function showDownloadModal() {
            if (!currentPhotoId) return;
            
            @guest
            // Jika user belum login, redirect ke halaman login
            alert('Anda harus login terlebih dahulu untuk mendownload foto');
            window.location.href = '{{ route("login") }}';
            return;
            @endguest
            
            // Langsung download tanpa CAPTCHA
            await processDownload();
        }

        async function processDownload() {
            if (!currentPhotoId) return;
            
            try {
                const response = await fetch(`/api/galleries/${currentPhotoId}/download`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    // Download file
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'photo_' + currentPhotoId + '.jpg';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    alert('Download berhasil!');
                } else {
                    const data = await response.json();
                    alert(data.message || 'Gagal mendownload foto');
                }
            } catch (error) {
                console.error('Error downloading:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadGallery();
        });
    </script>
@endsection
