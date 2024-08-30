@extends('layouts.app')
@section('content')
<!-- Page Title Start -->
<div class="flex justify-between items-center mb-6">
    <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Order</h4>
</div>
<!-- Page Title End -->
<div class="card">
    <div class="card-header">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                    <div class="py-3 px-4 flex">
                        <div class="relative max-w-xs">
                            <label for="table-with-pagination-search" class="sr-only">Search</label>
                            <input type="date" class="form-input" id="date" onchange="change()" value="{{ now() }}">
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700" id="tabel-head">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase" id="day">
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase" id="total">
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

@endsection

@push('js')
<!-- datepicker js -->
<script src="{{ asset('assets') }}/libs/flatpickr/flatpickr.min.js"></script>

<!-- Modern colorpicker bundle js -->
<script src="{{ asset('assets') }}/libs/@simonwep/pickr/pickr.min.js"></script>

<!-- init js -->
<script src="{{ asset('assets') }}/js/pages/form-flatpickr.js"></script>
<script>
    get();
    let data = null;
    function get() {
        $('#day').text('')
        $('#total').text('')
        $('#data-table').html('');
        requestData('get', '{{ route('orders.create') }}?date_start='+$("#date").val(), null, function(error, response) {
        if (error) {
            console.log(error);
        } else {
                $('#data-table').html('');
                $('#day').text(response.data.sales[0]['date'])
                $('#total').text(formatCurrency(response.data.sales[0]['daily_amount']))
                $.each(response.data.sales[0].items, function (index, item) {
                    $('#data-table').append(`
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${item.status == 2? "Lunas" : ""}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${item.outlet_name}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${item.settle_by}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${item.payment_mode}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${formatCurrency(item.amount)}</div>
                            </td>
                        </tr>
                    `);
                });
                data = response.data;
                // renderPagination(response);
            }
        });
    }


    $(document).on('click', '.edit', function() {
        $('#open').click();
        let id = $(this).data('id');
        let item = data.find(item => item.id == id);
        $('#name').val(item.name);
        $('#id').val(item.id);
        $('#title').text('Edit Order');
    });

    function change() {
        get();
    }

   function formatCurrency(amount) {
    var formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
    });
    return formatter.format(amount);
    }
</script>
@endpush