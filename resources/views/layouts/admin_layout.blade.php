<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Админ-панель - @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/live-blog/public/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="/live-blog/public/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/live-blog/public/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/live-blog/public/admin/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/live-blog/public/admin/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/live-blog/public/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/live-blog/public/admin/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="/live-blog/public/admin/dist/css/colorbox.css">
  <!-- jQuery -->
  <script src="/live-blog/public/admin/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="/live-blog/public/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/live-blog/public/admin/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('homeAdmin')}}" class="brand-link">
      <img src="/live-blog/public/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Админ-панель</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/live-blog/public/admin/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('homeAdmin') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Главная
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-newspaper"></i>
              <p>
                Блог
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('post.index') }}" class="nav-link">
                  <p>Все статьи</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('post.create')}}" class="nav-link">
                  <p>Добавить статью</p>
                </a>
              </li>
            </ul>
          </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-newspaper"></i>
                <p>
                    Список контактов
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            @php
                $contacts = \App\Models\Contact::where('writer_id', \Illuminate\Support\Facades\Auth::id())->orderBy('created_at', 'DESC')->get();
            @endphp
            <ul class="nav nav-treeview">
                @foreach($contacts as $contact)
                    <li class="nav-item">
                        <a href="{{ route('contacts', $contact->user_id) }}" class="nav-link">
                            <p>{{$contact->user_name}}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">
                <i class="nav-icon far fa-newspaper"></i>
                <p>
                    Вернуться в блог
                </p>
            </a>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/live-blog/public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/live-blog/public/admin/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/live-blog/public/admin/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/live-blog/public/admin/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/live-blog/public/admin/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/live-blog/public/admin/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/live-blog/public/admin/plugins/moment/moment.min.js"></script>
<script src="/live-blog/public/admin/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/live-blog/public/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/live-blog/public/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/live-blog/public/admin/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/live-blog/public/admin/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/live-blog/public/admin/dist/js/pages/dashboard.js"></script>
<script src="/live-blog/public/admin/dist/js/jquery.colorbox-min.js"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/live-blog/public/packages/barryvdh/elfinder/js/standalonepopup.js"></script>
<script src="/live-blog/public/admin/admin.js"></script>
</body>
</html>
