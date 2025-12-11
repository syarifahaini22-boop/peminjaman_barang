@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

            </div>
            <button type="button" class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                <i class="bi bi-person-plus me-2"></i>Tambah Admin
            </button>
        </div>

        <!-- Session Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif



        <!-- Main Card -->
        <div class="card card-custom">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Daftar Administrator
                </h5>
                <div class="d-flex align-items-center">
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control form-control-custom" id="searchInput"
                            placeholder="Cari admin...">
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($admins->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-people display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted">Belum ada data admin</h5>
                        <p class="text-muted mb-4">Mulai dengan menambahkan admin baru</p>
                        <button type="button" class="btn btn-custom-primary" data-bs-toggle="modal"
                            data-bs-target="#addAdminModal">
                            <i class="bi bi-person-plus me-2"></i>Tambah Admin
                        </button>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-custom table-hover" id="adminTable">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Bergabung</th>


                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $index => $admin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light p-2 me-3">
                                                    <i class="bi bi-person-circle text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $admin->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            @if ($admin->role == 'admin')
                                                <span class="role-badge role-admin">Admin</span>
                                            @elseif($admin->role == 'staff')
                                                <span class="role-badge role-staff">Staff</span>
                                            @else
                                                <span class="role-badge role-supervisor">Supervisor</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('d M Y') }}</td>

                                        <td class="text-center">
                                            <div class="btn-action-group">
                                                <button class="btn-action btn-custom-warning btn-sm-custom"
                                                    data-bs-toggle="modal" data-bs-target="#editAdminModal"
                                                    data-id="{{ $admin->id }}" data-name="{{ $admin->name }}"
                                                    data-email="{{ $admin->email }}" data-role="{{ $admin->role }}"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>



                                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn-action btn-custom-danger btn-sm-custom delete-btn"
                                                        title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Tambah Admin -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdminModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Tambah Admin Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label form-label-custom">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom" id="name" name="name"
                                value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label form-label-custom">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom" id="email" name="email"
                                value="{{ old('email') }}" placeholder="contoh@labrsi.com" required>

                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label form-label-custom">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-custom" id="password"
                                    name="password" placeholder="Minimal 8 karakter" required minlength="8">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label form-label-custom">Role <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="role" name="role" required>
                                <option value="" disabled selected>Pilih role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator
                                </option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-custom-primary">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Admin -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Admin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form action akan di-set oleh JavaScript -->
                <form id="editAdminForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label form-label-custom">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom" id="edit_name" name="name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_email" class="form-label form-label-custom">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom" id="edit_email"
                                name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_role" class="form-label form-label-custom">Role <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="edit_role" name="role" required>
                                <option value="admin">Administrator</option>
                                <option value="staff">Staff</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_password" class="form-label form-label-custom">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-custom" id="edit_password"
                                    name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <button type="button" class="btn btn-outline-secondary" id="toggleEditPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Isi hanya jika ingin mengubah password</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-custom-primary">
                            <i class="bi bi-save me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Additional styles for admin management */
        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border: 1px solid rgba(52, 152, 219, 0.2);
        }

        .role-staff {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.2);
        }

        .role-supervisor {
            background-color: rgba(243, 156, 18, 0.1);
            color: #f39c12;
            border: 1px solid rgba(243, 156, 18, 0.2);
        }

        .status-verified {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .status-unverified {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
    </style>

    @push('scripts')
        <script>
            // Toggle password visibility
            document.getElementById('togglePassword')?.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                togglePasswordVisibility(passwordInput, this);
            });

            document.getElementById('toggleEditPassword')?.addEventListener('click', function() {
                const passwordInput = document.getElementById('edit_password');
                togglePasswordVisibility(passwordInput, this);
            });

            function togglePasswordVisibility(input, button) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                button.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            }

            // Search functionality
            document.getElementById('searchInput')?.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#adminTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // GANTI bagian ini di script:
            const editModal = document.getElementById('editAdminModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const email = button.getAttribute('data-email');
                    const role = button.getAttribute('data-role');

                    // PASTIKAN route admin.update sudah benar di web.php
                    const updateUrl = "{{ route('admin.update', ':id') }}".replace(':id', id);

                    // Update form action
                    const form = document.getElementById('editAdminForm');
                    form.action = updateUrl;

                    // Fill form data
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role').value = role;
                    document.getElementById('edit_password').value = '';
                });
            }

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    if (confirm('Apakah Anda yakin ingin menghapus admin ini?')) {
                        form.submit();
                    }
                });
            });

            // Auto close alerts after 5 seconds
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        </script>
    @endpush
@endsection
