@extends('layouts.app')

@section('title', 'Manage Bans - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <!-- Back Button -->
    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <h1 class="mb-4"><i class="fas fa-ban"></i> Manage Banned IPs</h1>

    <div class="row">
        <!-- Add Ban Form -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ban IP Address</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.bans.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="ip" class="form-label">IP Address</label>
                            <input type="text" class="form-control @error('ip') is-invalid @enderror"
                                   id="ip" name="ip" placeholder="192.168.1.1" required>
                            @error('ip')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason (Optional)</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                      id="reason" name="reason" rows="3"
                                      placeholder="Reason for ban..."></textarea>
                            @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-ban"></i> Ban IP
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Banned IPs List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Banned IP Addresses ({{ $bans->total() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($bans as $ban)
                    <div class="d-flex justify-content-between align-items-start border-bottom py-3">
                        <div>
                            <h6 class="mb-1">
                                <code>{{ $ban->ip }}</code>
                            </h6>
                            @if($ban->reason)
                            <p class="text-muted mb-1"><strong>Reason:</strong> {{ $ban->reason }}</p>
                            @endif
                            <small class="text-muted">
                                Banned {{ $ban->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <form action="{{ route('admin.bans.destroy', $ban->ip) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to unban this IP?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check"></i> Unban
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No banned IP addresses.</p>
                    @endforelse

                    <!-- Pagination -->
                    @if($bans->hasPages())
                    <div class="mt-3">
                        {{ $bans->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
