@extends('layouts.login') <!-- Use the new layout -->

@section('title', 'Login')

@section('content')
    <div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div class="card" style="width: 400px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Login</h3>

                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Sign in to start your session</p>
                        <form id="login-form">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid gap-2 mt-3">
                            <button type="button" id="login-button" class="btn btn-primary btn-block">Login</button>
                            </div>
                            <div id="loading-spinner" style="display: none; margin-top: 10px;">
                                <span>Loading...</span>
                            </div>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-success mb-2">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@push('css')
<style>
        body {
            background-color: #f4f6f9;
        }
    </style>
@endpush
@push('js')
<script type="module">
  import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js';
  import { getAuth, signInWithEmailAndPassword, setPersistence, browserLocalPersistence } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-auth.js';

  const firebaseConfig = {
    apiKey: "AIzaSyA3hmJDqoNttINkUCmBt3Q2FIkhdprcBVY",
    authDomain: "petcareapp-f62db.firebaseapp.com",
    projectId: "petcareapp-f62db",
    storageBucket: "petcareapp-f62db.appspot.com",
    messagingSenderId: "426139204889",
    appId: "1:426139204889:web:bc984744508f8164a11988",
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);

  setPersistence(auth, browserLocalPersistence)
    .catch((error) => console.error('Error setting persistence:', error));

  function handleLogin(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const loginButton = document.getElementById('login-button');
    const spinner = document.getElementById('loading-spinner');

    loginButton.disabled = true;
    spinner.style.display = 'block';

    // Get CSRF token from the meta tag in the HTML head
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    signInWithEmailAndPassword(auth, email, password)
      .then((userCredential) => userCredential.user.getIdToken(true)) // Get the ID token
      .then((idToken) => {
        return fetch('{{ route('login.post') }}', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${idToken}`,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken, // Include the CSRF token here
          },
          body: JSON.stringify({ email, password }), // Optionally, include email & password, or leave it out
        });
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Login successful');
          window.location.href = data.redirect_url; // Redirect to dashboard
        } else {
          alert('Login failed: ' + data.error);
        }
      })
      .catch((error) => {
        console.error('Error during login:', error);
        alert('Error: ' + (error.message || 'An unknown error occurred'));
      })
      .finally(() => {
        loginButton.disabled = false;
        spinner.style.display = 'none';
      });
  }

  document.getElementById('login-button').addEventListener('click', handleLogin);
</script>
@endpush

