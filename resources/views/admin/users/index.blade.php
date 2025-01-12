@extends('layouts.app') <!-- Use the existing app layout -->

@section('title', 'User List')

@section('content')
    @if(session('status'))
    <div class="alert alert-success mb-2">
        {{ session('status') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="container">
        <h1>Users List</h1>

        <!-- Users Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($users && count($users) > 0)
                @foreach($users as $user)
                <tr>
                    <td>{{ $user['email'] }}</td>
                    <td>
                       <a href="{{ route('users.edit', $user['uid']) }}" class="btn btn-warning btn-sm">Edit</a>
                       <button class="btn btn-danger btn-sm delete-btn" data-uid="{{ $user['uid'] }}" data-email="{{ $user['email'] }}">Delete</button>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5" class="text-center">No users found</td>
                </tr>
                @endif
            </tbody>

        </table>
    </div>
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the user: <strong id="user-email"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        const deleteForm = document.getElementById('delete-form');
        const userEmail = document.getElementById('user-email');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const uid = this.getAttribute('data-uid');
                const email = this.getAttribute('data-email');
                userEmail.textContent = email;
                deleteForm.action = `/users/${uid}`; 
                modal.show();
            });
        });
    </script>
@endpush