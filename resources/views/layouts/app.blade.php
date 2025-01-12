<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        #sidebar-wrapper {
            width: 250px;
            background-color: #343a40; /* Dark background for sidebar */
            padding-top: 20px;
            position: fixed; /* Keep the sidebar fixed */
            height: 100vh;
            z-index: 100;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 10px;
            font-size: 24px;
            font-weight: bold;
            background-color: #212529; /* Darker header */
            color: white;
            text-align: center;
        }

        #sidebar-wrapper .list-group-item {
            background-color: #343a40; /* Match the sidebar */
            border: none;
            color: #bbb;
            font-size: 18px;
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: #007bff;
            color: white;
        }

        #sidebar-wrapper .list-group-item.active {
            background-color: #007bff;
            color: white;
        }

        #sidebar-wrapper .list-group-item i {
            margin-right: 10px;
        }

        #page-content-wrapper {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
            background-color: #f4f6f9;
            height: 100vh;
            overflow-y: auto;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .dropdown-menu a {
            color: #333;
        }

        .dropdown-menu {
            background-color: #343a40;
            border: none;
        }

        .dropdown-menu a:hover {
            background-color: #007bff;
            color: white;
        }

        #menu-toggle {
            background-color: #007bff;
            color: white;
        }

        #menu-toggle:hover {
            background-color: #0056b3;
        }

        /* Profile dropdown styles */
        .profile-dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .profile-dropdown .profile-icon {
            font-size: 30px; /* Larger size for visibility */
            color: #333;
            cursor: pointer;
        }

        .profile-dropdown .profile-menu {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            background-color: #343a40;
            color: white;
            border-radius: 5px;
            width: 150px;
            padding: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Adds shadow for better visibility */
        }

        .profile-dropdown .profile-menu a {
            color: white;
            text-decoration: none;
        }

        .profile-dropdown .profile-menu a:hover {
            background-color: #007bff;
            color: white;
        }

        .profile-dropdown.show .profile-menu {
            display: block; /* Show the menu when the 'show' class is added */
        }

        /* Align profile icon in the top-right corner */
        .navbar .ml-auto {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%; /* Ensure navbar items fill full width */
        }
    </style>

    @stack('css')
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Dashboard</div>
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-dark"> 
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action bg-dark"> 
                    <i class="fas fa-users"></i> User List
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="ml-auto"> <!-- Ensures alignment to the right -->
                        <div class="profile-dropdown">
                            <!-- Profile icon -->
                            <i class="fas fa-user-circle profile-icon" id="profile-icon"></i>
                            
                            <!-- Profile dropdown menu -->
                            <div class="profile-menu" id="profile-menu">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Toggle profile menu visibility
        document.getElementById("profile-icon").addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent the click event from propagating to document
            document.querySelector(".profile-dropdown").classList.toggle("show");
        });

        // Close the profile menu when clicking anywhere outside of it
        document.addEventListener("click", function (event) {
            if (!event.target.closest(".profile-dropdown")) {
                document.querySelector(".profile-dropdown").classList.remove("show");
            }
        });
    </script>

    @stack('js')
</body>

</html>
