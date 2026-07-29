@extends('patient.layouts.main')
@section('content')
    <title>AuraHMS - Patient Bills & Payments</title>
    <style>
        .invoice-preview-sheet {
            background: #fff;
            color: #0f172a;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
        }
        [data-theme="dark"] .invoice-preview-sheet {
            background: #ffffff;
            color: #0f172a;
        }
        .invoice-preview-sheet th {
            color: #475569 !important;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            background: none !important;
        }
        .invoice-preview-sheet td {
            color: #334155 !important;
            border-bottom: 1px solid #f1f5f9;
        }
        .invoice-preview-sheet .text-muted {
            color: #64748b !important;
        }
        .invoice-preview-sheet .divider-line {
            height: 1px;
            background-color: #e2e8f0;
            margin: 1.5rem 0;
        }
    </style>
    <!-- CONTENT BODY -->
    <div class="content-body">
        
        <!-- BREADCRUMB -->
        <nav>
            <ul class="breadcrumb-custom">
                <li class="breadcrumb-item-custom"><a href="/patient/dashboard">Home</a></li>
                <li class="breadcrumb-item-custom">Bills & Payments</li>
            </ul>
        </nav>

        <!-- SKELETON LOADER -->
        <div class="skeleton-wrapper row g-4 mb-4">
            <div class="col-md-5"><div class="glass-card skeleton" style="height: 350px;"></div></div>
            <div class="col-md-7"><div class="glass-card skeleton" style="height: 500px;"></div></div>
        </div>

        <!-- REAL CONTENT WRAPPER -->
        <div class="real-content-wrapper d-none">
            
            <div class="row g-4">
                <!-- LEFT PANEL: MY INVOICES LOG -->
                <div class="col-xl-5">
                    <div class="glass-card h-100">
                        <h5 class="fw-bold mb-4">My Invoice Ledger</h5>

                        <div class="d-flex flex-column gap-2">

                            @forelse($invoices as $invoice)

                                <div class="p-3 border border-light border-opacity-10 rounded-4 glass-sub-card d-flex justify-content-between align-items-center">

                                    <div>
                                        <span class="fw-bold d-block">
                                            #{{ $invoice->invoice_number }}
                                        </span>

                                        <small class="text-muted d-block">
                                            {{ $setting->hospital_name }}
                                        </small>

                                        <small class="text-muted d-block mt-1">
                                            Issued:
                                            {{ $invoice->billing_date->format('d M Y') }}
                                        </small>

                                        <small class="text-muted d-block">
                                            Due:
                                            {{ $invoice->due_date->format('d M Y') }}
                                        </small>
                                    </div>

                                    <div class="text-end">

                                        <span class="fw-bold d-block">
                                            ${{ number_format($invoice->total_amount,2) }}
                                        </span>

                                        @if($invoice->status == 'paid')
                                            <span class="custom-badge badge-success mt-1">
                                                Paid
                                            </span>

                                        @elseif($invoice->status == 'unpaid')
                                            <span class="custom-badge badge-danger mt-1">
                                                Unpaid
                                            </span>

                                        @elseif($invoice->status == 'pending')
                                            <span class="custom-badge badge-warning mt-1">
                                                Pending
                                            </span>

                                        @else
                                            <span class="custom-badge badge-secondary mt-1">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="text-center py-5">

                                    <i class="bi bi-receipt fs-1 text-muted"></i>

                                    <h6 class="mt-3 mb-2">
                                        No Invoice Found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        You don't have any billing records yet.
                                    </p>

                                </div>

                            @endforelse

                        </div>
                    </div>
                </div>

                <!-- RIGHT PANEL: PROFESSIONAL LIVE PREVIEW -->
                <div class="col-xl-7">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Active Invoice Statement</h6>

                        @if($activeInvoice)
                            <button class="btn btn-premium btn-sm" onclick="window.print()">
                                <i class="bi bi-printer"></i> Print Statement
                            </button>
                        @endif
                    </div>

                    @if($activeInvoice)

                    <!-- PRINTABLE INVOICE SHEET -->
                    <div class="invoice-preview-sheet">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold mb-1" style="color: var(--primary);">
                                    {{ $setting->hospital_name }}
                                </h4>
                                <p class="text-muted mb-0 small">
                                    {{ $setting->address }}<br>
                                    {{ $setting->phone }}
                                    @if(!empty($setting->mail_username))
                                        • {{ $setting->mail_username }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-bold mb-1">RECEIPT</h3>
                                <span class="fw-semibold text-muted">
                                    #{{ $activeInvoice->invoice_number }}
                                </span>
                            </div>
                        </div>

                        <div class="divider-line"></div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <span class="text-muted d-block mb-1 text-uppercase fw-semibold">
                                    Patient Details:
                                </span>
                                <h6 class="fw-bold mb-1">
                                    {{ $activeInvoice->patient->user->name }}
                                </h6>
                                <p class="text-muted mb-0 small">
                                    Patient ID :
                                    #PT-{{ $activeInvoice->patient->user->id }}<br>
                                    Email :
                                    {{ $activeInvoice->patient->user->email }}<br>
                                    Contact :
                                    {{ $activeInvoice->patient->number }}
                                </p>
                            </div>

                            <div class="col-md-6 text-md-end">
                                <span class="text-muted d-block mb-1 text-uppercase fw-semibold">
                                    Billing Info:
                                </span>
                                <p class="text-muted mb-0 small">
                                    Billing Date :
                                    {{ $activeInvoice->billing_date->format('d M Y') }}<br>
                                    Due Date :
                                    {{ $activeInvoice->due_date->format('d M Y') }}<br>
                                    Payment Method :
                                    {{ $activeInvoice->payment_method ?? 'N/A' }}<br>
                                    Status :
                                    {{ ucfirst($activeInvoice->status) }}
                                </p>
                            </div>
                        </div>
                        <table class="table table-borderless mb-4">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Rate</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($activeInvoice->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td class="text-end">
                                    ${{ number_format($item->rate,2) }}
                                </td>
                                <td class="text-center">
                                    {{ $item->qty }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($item->total,2) }}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="divider-line"></div>
                        <div class="d-flex justify-content-end">
                            <table style="width:320px">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">
                                        ${{ number_format($activeInvoice->subtotal,2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-end">
                                        -${{ number_format($activeInvoice->discount,2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>GST</td>
                                    <td class="text-end">
                                        +${{ number_format($activeInvoice->gst,2) }}
                                    </td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">
                                        ${{ number_format($activeInvoice->total_amount,2) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="glass-card text-center py-5">
                        <i class="bi bi-receipt fs-1 text-muted"></i>
                        <h5 class="mt-3">No Invoice Found</h5>
                        <p class="text-muted mb-0">
                            You don't have any invoices yet.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection