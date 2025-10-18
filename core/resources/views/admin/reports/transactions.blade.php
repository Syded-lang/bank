@extends('admin.layouts.app')

@section('panel')
@php
    $request = request();
    $tableName = 'transaction_report';
    $tableConfiguration = tableConfiguration($tableName);

    $remarks = $remarks->pluck('remark_text')->toArray();

    $columns = collect([
        prepareTableColumn('trx', 'TRX No.'),
        prepareTableColumn('account_number', 'Account No.', link:'route("admin.users.detail", $item->user_id)'),
        prepareTableColumn('username', 'Username', link:'route("admin.users.detail", $item->user_id)'),
        prepareTableColumn('created_at', 'Transacted At', 'showDateTime($item->created_at)', filter: 'date'),
        prepareTableColumn('remark_text', 'Remark', filter:'select', filterOptions: $remarks),
        prepareTableColumn('transaction_type', 'Transaction Type', filter: 'select', filterOptions: ['Debited', 'Credited'], className: '$item->trx_type=="+"? "text--success fw-bold": "text--danger fw-bold"'),
        prepareTableColumn('amount', 'Amount', 'showAmount($item->amount)', filter: 'range'),
        prepareTableColumn('details', 'Details', '__($item->details)'),
    ]);

    if ($tableConfiguration) {
        $visibleColumns = $tableConfiguration->visible_columns;
    } else {
        $visibleColumns = $columns->pluck('id')->toArray();
    }

    $action = [
        'name' => 'Action',
        'style' => 'button',
        'show' => can('admin.report.transaction.update.date'),
        'buttons' => [
            [
                'name' => 'Edit Date',
                'show' => 'can("admin.report.transaction.update.date")',
                'class' => 'edit-date-btn btn-sm btn-primary',
                'icon' => 'la la-edit',
                'attributes' => [
                    'data-transaction-id' => '$item->id',
                    'data-current-date' => '$item->created_at',
                ],
            ],
        ],
    ];
@endphp

<x-viser_table.table :data="$transactions" :columns="$columns" :action="$action" :columnConfig="true" :tableName="$tableName" :visibleColumns="$visibleColumns" class="table-responsive--md table-responsive"/>

<!-- Edit Date Modal -->
<div class="modal fade" id="editDateModal" tabindex="-1" role="dialog" aria-labelledby="editDateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDateModalLabel">Edit Transaction Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDateForm" method="POST" action="{{ route('admin.report.transaction.update.date') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="transactionId" name="transaction_id">
                    <div class="form-group">
                        <label for="newDate">New Date & Time</label>
                        <input type="datetime-local" class="form-control" id="newDate" name="new_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Date</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation for dynamically loaded content
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-date-btn')) {
                e.preventDefault();
                const button = e.target.closest('.edit-date-btn');
                const transactionId = button.getAttribute('data-transaction-id');
                const currentDate = button.getAttribute('data-current-date');

                console.log('Opening modal for transaction:', transactionId, 'with date:', currentDate);

                // Set the transaction ID
                document.getElementById('transactionId').value = transactionId;

                // Format the current date for datetime-local input
                if (currentDate) {
                    const date = new Date(currentDate);
                    const formattedDate = date.toISOString().slice(0, 16);
                    document.getElementById('newDate').value = formattedDate;
                }

                // Show the modal
                const modalElement = document.getElementById('editDateModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    });
</script>
@endpush
