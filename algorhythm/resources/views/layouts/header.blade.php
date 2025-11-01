<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; img-src 'self' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://stackpath.bootstrapcdn.com; script-src 'self' 'unsafe-inline' https://code.jquery.com https://stackpath.bootstrapcdn.com;">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <title>Header</title>

    <style>
        .app-header {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #333;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #0056b3;
        }

        .navbar-nav .nav-icon-hover {
            position: relative;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .nav-icon-hover:hover {
            transform: scale(1.1);
        }

        .profile-icon {
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .profile-icon:hover {
            transform: scale(1.1);
        }

        .profile-image {
            border: 2px solid #333;
            transition: border-color 0.3s ease;
        }

        .profile-image:hover {
            border-color: #0056b3;
        }

        .dropdown-menu-animate-up {
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <header class="app-header animate__animated animate__fadeInDown">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>

            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                    <li class="nav-item dropdown">
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop2">
                            <div class="message-body text-center p-2">
                                <p class="text-muted mb-0">Guest Mode</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
