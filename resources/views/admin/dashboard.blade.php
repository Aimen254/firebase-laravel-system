@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
        <h1>Welcome to your Dashboard</h1>
        <p>This is the dashboard where you can manage your data.</p>
    </div>
@endsection
