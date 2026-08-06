<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title','後台管理系統')</title>

    <style>
        body {
            min-height: 100vh;
            background: #f1f5f9;
        }

        .navbar {
            height: 60px;
        }

        .sidebar {
            width: 240px;
            min-height: calc(100vh - 60px);
            background: #0f172a;
            transition: .3s;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 0;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 20px;
            white-space: nowrap;
        }

        .sidebar .nav-link:hover {
            background: #1e293b;
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .dashboard-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 24px;
        }
    </style>
    @yield('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/lightbox.min.css">
    <script src="/js/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.13.2/dist/axios.min.js"></script>
    <script src="/js/lightbox.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>


    <nav class="navbar navbar-dark bg-primary px-3">
        <button class="btn btn-primary" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="d-flex align-items-center">
            <i class="bi bi-speedometer2 fs-3 me-2 text-light"></i>
            <a href="/admin/home" class="navbar-brand mb-0  fw-bold">
                後台管理系統
            </a>
        </div>

        <form method="POST" action="/logout">
            <input type="hidden" name="id">
            @csrf
            <button type="button" class="btn btn-light btn-sm">
                <i class="bi bi-box-arrow-right"></i>
                登出
            </button>
        </form>
    </nav>



    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar collapsed" id="sidebar">
            <ul class="nav flex-column mt-3">
                <li>
                    <a class="nav-link" href="/admin/home">
                        <i class="bi bi-house me-2"></i>
                        首頁
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="/admin/member/list">
                        <i class="bi bi-people me-2"></i>
                        會員管理
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="/admin/views/list">
                        <i class="bi bi-camera2 me-2"></i>
                        景點管理
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        $(document).ready(function() {
            $("#all").on("change", function() {
                $('input[name="id[]"]').prop('checked', $(this).prop("checked"));
            });
        });
    </script>

</body>

</html>