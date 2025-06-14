@extends('layouts.admin-layout')
@section('title', 'Add New Announcement')
@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Add New Announcement</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div id="create-announcement-container" data-url="{{ route('admin.announcements.store') }}">
            @csrf
            @include('platform.announcements.form')
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('create-announcement-container');
    const saveButton = container.querySelector('button[type="submit"]');

    saveButton.addEventListener('click', function(e) {
        e.preventDefault();

        const url = container.dataset.url; 
        const csrfToken = container.querySelector('input[name="_token"]').value;

        const plainFormData = {
            title: container.querySelector('[name="title"]').value,
            detail: container.querySelector('[name="detail"]').value,
            status: container.querySelector('[name="status"]').value,
            display_on_web: container.querySelector('[name="display_on_web"]').checked ? 1 : 0,
            send_email: container.querySelector('[name="send_email"]').checked ? 1 : 0,
        };

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Pengumuman baru akan dibuat dan disimpan.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Harap tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(plainFormData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message, 
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('admin.announcements.index') }}";
                    });
                })
                .catch(error => {
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (error && error.message) {
                        errorMessage = error.message;
                    } else if (error && error.errors) {
                         errorMessage = Object.values(error.errors).flat().join(' ');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                    });
                });
            }
        });
    });
});
</script>
@endpush
