@extends('layouts.app')

@section('title', 'Manage Admin Users - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users-cog"></i> Admin Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Admin User
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adminUsers as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->username }}</strong>
                                @if($user->id === Auth::guard('admin')->id())
                                    <span class="badge bg-info">You</span>
                                @endif
                            </td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>
                                @if($user->isOwner())
                                    <span class="badge bg-danger">Owner</span>
                                @else
                                    <span class="badge bg-secondary">Admin</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if(!$user->isOwner() && $user->id !== Auth::guard('admin')->id())
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-key"></i> Password
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this admin user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No admin users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle"></i> <strong>Note:</strong> Only the owner account can manage admin users.
        The owner account cannot be deleted.
    </div>
</div>
@endsection
