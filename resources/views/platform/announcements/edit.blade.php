@extends('layouts.admin-layout')

@section('title', 'Edit Announcement')

@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Edit Announcement</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div id="edit-announcement-container" data-url="{{ route('admin.announcements.update', $announcement['id']) }}">
            @csrf
            @include('platform.announcements.form', ['announcement' => $announcement])
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('edit-announcement-container');
    const updateButton = container.querySelector('button[type="submit"]');

    updateButton.addEventListener('click', function(e) {
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
            text: "Perubahan yang Anda buat akan disimpan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, perbarui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Updating...',
                    text: 'Harap tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(url, {
                    method: 'PUT',
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
