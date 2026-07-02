@if (session('success') || session('error') || $errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan selesai',
                    text: @json(session('success')),
                    confirmButtonColor: '#111827',
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Pesanan belum selesai',
                    text: @json(session('error')),
                    confirmButtonColor: '#111827',
                });
            @elseif ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Cek lagi formulirnya',
                    text: @json($errors->first()),
                    confirmButtonColor: '#111827',
                });
            @endif
        });
    </script>
@endif
