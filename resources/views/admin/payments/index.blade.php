@extends('layouts.admin')

@section('title', 'Kelola Pembayaran - Admin Bengkel Asyraf')
@section('page-title', 'Kelola Pembayaran')

@section('content')
    <div class="card stat-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Daftar Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Bank</th>
                            <th>Nama Pengirim</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments ?? [] as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $payment->customOrder->id ?? 0) }}" class="fw-bold text-primary">
                                        {{ $payment->customOrder->order_code ?? '-' }}
                                    </a>
                                </td>
                                <td>{{ $payment->customOrder->user->name ?? '-' }}</td>
                                <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                                <td>{{ $payment->bank_name }}</td>
                                <td>{{ $payment->account_name }}</td>
                                <td>
                                    @if($payment->proof_image_path)
                                        <a href="{{ asset('storage/' . $payment->proof_image_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-image me-1"></i>Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $paymentColors = ['pending' => 'warning', 'verified' => 'success', 'rejected' => 'danger'];
                                        $paymentLabels = ['pending' => 'Pending', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
                                    @endphp
                                    <span class="badge bg-{{ $paymentColors[$payment->status] ?? 'secondary' }}">
                                        {{ $paymentLabels[$payment->status] ?? $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($payment->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}" class="d-inline"
                                            onsubmit="return confirm('Verifikasi pembayaran ini?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="verify">
                                            <button type="submit" class="btn btn-sm btn-success" title="Verifikasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}" class="d-inline"
                                            onsubmit="let note = prompt('Alasan penolakan:'); if(note){ this.admin_notes.value = note; return true; } return false;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="admin_notes" value="">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada pembayaran ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($payments) && $payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
