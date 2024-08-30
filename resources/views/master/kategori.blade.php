@extends('layouts.app')
@section('content')
<!-- Page Title Start -->
<div class="flex justify-between items-center mb-6">
    <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Kategori</h4>
</div>
<!-- Page Title End -->
<div class="card">
    <div class="card-header">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <button class="btn bg-primary text-white mb-2" type="button" id="open" data-fc-target="default-modal"
                    onclick="$('#title').text('Tambah Kategori');$('#form-input')[0].reset()" data-fc-type="modal"
                    type="button">
                    Tambah Kategori
                </button>
                <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                    <div class="py-3 px-4">
                        <div class="relative max-w-xs">
                            <label for="table-with-pagination-search" class="sr-only">Search</label>
                            <input type="text" name="table-with-pagination-search" id="table-with-pagination-search"
                                class="form-input ps-11" placeholder="Search for items">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                </svg>
                            </div>


                        </div>

                    </div>
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700" id="tabel-head">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="data-table">

                            </tbody>
                        </table>
                    </div>
                    <div class="py-1 px-4">
                        <nav class="flex items-center space-x-2" id="pagination-nav">
                            <!-- Pagination buttons will be dynamically generated here -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="default-modal" class="w-full h-full mt-5 fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
    <div
        class="fc-modal-open:opacity-100 duration-500 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex flex-col bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
            <h3 class="font-medium text-gray-800 dark:text-white text-lg" id="title">
                Modal Title
            </h3>
            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                data-fc-dismiss type="button">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form id="form-input">
            <div class="px-4 py-8 overflow-y-auto">
                <input type="hidden" id="id" name="id">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama
                            Kategori</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>


            </div>
            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                <button
                    class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                    data-fc-dismiss type="button" id="close">Batal
                </button>
                <button class="btn bg-primary text-white" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    get();
    let data = null;
    function get() {
        requestData('get', '{{ route('categories.create') }}', null, function(error, response) {
        if (error) {
            console.log(error);
        } else {
                $('#data-table').empty();
                $.each(response.data, function (index, item) {
                    $('#data-table').append(`
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${item.name}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <button class="btn bg-primary text-white edit" data-id="${item.id}" data-fc-type="modal" type="button">
                                    Edit
                                </button>
                                <button class="btn bg-red-500 text-white deleted" data-id="${item.id}" type="button">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                });
                data = response.data;
                renderPagination(response);
            }
        });
    }

    $('#form-input').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        requestData('post', '{{ route('categories.store') }}', formData, function(error, response) {
            if (error) {
                console.log(error);
            } else {
                get();
                $('#close').click();
                Toast.fire({
                    icon: "success",
                    title: response.message
                });
            }
        });
    });

    $(document).on('click', '.edit', function() {
        $('#open').click();
        let id = $(this).data('id');
        let item = data.find(item => item.id == id);
        $('#name').val(item.name);
        $('#id').val(item.id);
        $('#title').text('Edit Kategori');
    });

    $(document).on('click', '.deleted', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                requestData('delete', '{{ route('categories.index') }}/' + id, null, function(error, response) {
                    if (error) {
                        console.log(error);
                    } else {
                        get();
                        Toast.fire({
                            icon: "success",
                            title: response.message
                        });
                    }
                });
            }
        });
    });
</script>
@endpush
