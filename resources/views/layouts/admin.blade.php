<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" type="image/x-icon" href="{{asset('images/logo.jpg')}}">

  <title>{{ !empty($titleForLayout) ? $titleForLayout.' | ' : '' }}{{ config('app.name') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
  <!-- Custom css -->
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  {{--  Select 2--}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .select2-container .select2-selection--single {
      height: calc(2.25rem + 2px) !important;
      margin-right: .25rem!important;
    }
  </style>
  @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown user-menu">
        @php
          if(empty(Session::get('login')->image) || !file_exists('admin/uploads/admins/'.Session::get('login')->id.'/'.Session::get('login')->image)){
            $urlProfile = 'images/admin.png';
          } else {
            $urlProfile = 'uploads/admins/'.Session::get('login')->id.'/'.Session::get('login')->image;
          }
        @endphp
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="{{ asset($urlProfile) }}" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline">{{ Session::get('login')->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="{{ asset($urlProfile) }}" class="img-circle elevation-2" alt="User Image">
            <p>
              {{ Session::get('login')->name }}
              <small>Đăng nhập lần cuối: {{ Session::get('login')->lasttime }}</small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{ route('admin.administrator.meEdit') }}" class="btn btn-default btn-flat">Thông tin cá nhân</a>
            <a href="{{ route('admin.login.getLogout') }}" class="btn btn-default btn-flat float-right">Đăng xuất</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard.index') }}" class="brand-link">
      <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="Admin" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item{{ (strpos(url()->current(), '/dashboard') !== false) ? ' menu-open' : '' }}">
            <a href="{{ route('admin.dashboard.index') }}" class="nav-link{{ (strpos(url()->current(), '/dashboard') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Tổng quan
              </p>
            </a>
          </li>
          @permission(['admin.co-tmp.index', 'admin.co-tmp.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/co-tmp/') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/co-tmp/') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-list-ol" aria-hidden="true"></i>
              <p>
                Quản lý Chào Giá
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.co-tmp.index')
              <li class="nav-item">
                <a href="{{ route('admin.co-tmp.index') }}" class="nav-link{{ (strpos(url()->current(), '/co-tmp/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.co-tmp.create')
              <li class="nav-item">
                <a href="{{ route('admin.co-tmp.create') }}" class="nav-link{{ (strpos(url()->current(), '/co-tmp/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.co.index', 'admin.co.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/co/') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/co/') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-list-ol" aria-hidden="true"></i>
              <p>
                Quản lý CO
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.co.index')
              <li class="nav-item">
                <a href="{{ route('admin.co.index') }}" class="nav-link{{ (strpos(url()->current(), '/co/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.co.create')
              <li class="nav-item">
                <a href="{{ route('admin.co.create') }}" class="nav-link{{ (strpos(url()->current(), '/co/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.request.index', 'admin.request.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/request') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/request') !== false) ? ' active' : '' }}">
              <i class="nav-icon far fa-window-minimize" aria-hidden="true"></i>
              <p>
                Quản lý Phiếu Yêu Cầu
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.request.index')
              <li class="nav-item">
                <a href="{{ route('admin.request.index') }}" class="nav-link{{ (strpos(url()->current(), '/request/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.request.create')
              <li class="nav-item">
                <a href="{{ route('admin.request.create') }}" class="nav-link{{ (strpos(url()->current(), '/request/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.price-survey.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/price-survey') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/price-survey') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-edit" aria-hidden="true"></i>
              <p>
                Quản lý Khảo sát giá
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.price-survey.index')
              <li class="nav-item">
                <a href="{{ route('admin.price-survey.index') }}" class="nav-link{{ (strpos(url()->current(), '/price-survey/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.price-survey.create')
              <li class="nav-item">
                <a href="{{ route('admin.price-survey.create') }}" class="nav-link{{ (strpos(url()->current(), '/price-survey/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.payment.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/payment') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/payment') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-grip-lines" aria-hidden="true"></i>
              <p>
                Quản lý Phiếu Chi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.payment.index')
              <li class="nav-item">
                <a href="{{ route('admin.payment.index') }}" class="nav-link{{ (strpos(url()->current(), '/payment/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.receipt.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/receipt') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/receipt') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-bars" aria-hidden="true"></i>
              <p>
                Quản lý Phiếu Thu
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.receipt.index')
              <li class="nav-item">
                <a href="{{ route('admin.receipt.index') }}" class="nav-link{{ (strpos(url()->current(), '/receipt/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.manufacture.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/manufacture') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/manufacture') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-building" aria-hidden="true"></i>
              <p>
                Quản lý sản xuất
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.manufacture.index')
              <li class="nav-item">
                <a href="{{ route('admin.manufacture.index') }}"
                   class="nav-link{{ (strpos(url()->current(), '/manufacture/index') !== false) ? ' active' : '' }}"
                >
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.delivery.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/delivery') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/delivery') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-truck" aria-hidden="true"></i>
              <p>
                Quản lý giao nhận
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.price-survey.index')
              <li class="nav-item">
                <a href="{{ route('admin.delivery.index') }}"
                   class="nav-link{{ (strpos(url()->current(), '/delivery/index') !== false) ? ' active' : '' }}"
                >
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.warehouse-export-sell.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/warehouse-export-sell') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/warehouse-export-sell') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-file-export" aria-hidden="true"></i>
              <p>
                Phiếu xuất kho bán hàng
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.warehouse-export-sell.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-export-sell.index') }}"
                   class="nav-link{{ (strpos(url()->current(), '/warehouse-export-sell/index') !== false) ? ' active' : '' }}"
                >
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.warehouse-export.index'])
          <li class="nav-item{{ ((strpos(url()->current(), '/admin/warehouse-export') !== false) && (!(strpos(url()->current(), '/admin/warehouse-export-sell') !== false))) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ ((strpos(url()->current(), '/admin/warehouse-export') !== false) && (!(strpos(url()->current(), '/admin/warehouse-export-sell') !== false))) ? ' active' : '' }}">
              <i class="nav-icon fas fa-file-export" aria-hidden="true"></i>
              <p>
                Phiếu xuất kho
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.warehouse-export.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-export.index') }}"
                   class="nav-link{{ (strpos(url()->current(), '/warehouse-export/index') !== false) ? ' active' : '' }}"
                >
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.warehouse-export.create')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-export.create') }}"
                   class="nav-link{{ (strpos(url()->current(), '/warehouse-export/create') !== false) ? ' active' : '' }}"
                >
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.warehouse-receipt.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/admin/warehouse-receipt') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/admin/warehouse-receipt') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-file-import" aria-hidden="true"></i>
              <p>
                Phiếu nhập kho
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.warehouse-receipt.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-receipt.index') }}"
                   class="nav-link{{ (strpos(url()->current(), '/warehouse-receipt/index') !== false) ? ' active' : '' }}"
                >
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.warehouse-receipt.create')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-receipt.create') }}"
                   class="nav-link{{ (strpos(url()->current(), '/price-survey/create') !== false) ? ' active' : '' }}"
                >
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.warehouse-plate.index', 'admin.warehouse-spw.index', 'admin.warehouse-remain.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/warehouse-plate') !== false || strpos(url()->current(), '/warehouse-spw') !== false || strpos(url()->current(), '/warehouse-remain') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/warehouse-plate') !== false || strpos(url()->current(), '/warehouse-spw') !== false || strpos(url()->current(), '/warehouse-remain') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-pallet" aria-hidden="true"></i>
              <p>
                Kho
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.warehouse-group.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-group.index') }}" class="nav-link{{ (strpos(url()->current(), '/warehouse-group/index') !== false) ? ' active' : '' }}">
                  <p>Nhóm hàng hóa</p>
                </a>
              </li>
              @endpermission
              @permission('admin.warehouse-product-code.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-product-code.index') }}" class="nav-link{{ (strpos(url()->current(), '/warehouse-product-code/index') !== false) ? ' active' : '' }}">
                  <p>Mã hàng hóa</p>
                </a>
              </li>
              @endpermission
              @permission('admin.warehouse-plate.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-plate.index') }}" class="nav-link{{ (strpos(url()->current(), '/warehouse-plate/index') !== false) ? ' active' : '' }}">
                  <p>Kho Tấm</p>
                </a>
              </li>
              @endpermission
              @permission('admin.warehouse-spw.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-spw.index') }}" class="nav-link{{ (strpos(url()->current(), '/warehouse-spw/index') !== false) ? ' active' : '' }}">
                  <p>Kho SPW</p>
                </a>
              </li>
              @endpermission
              @permission('admin.warehouse-remain.index')
              <li class="nav-item">
                <a href="{{ route('admin.warehouse-remain.index') }}" class="nav-link{{ (strpos(url()->current(), '/warehouse-remain/index') !== false) ? ' active' : '' }}">
                  <p>Kho Còn Lại</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.administrator.index', 'admin.administrator.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/administrator') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/administrator') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-regular fa-user-secret"></i>
              <p>
                Quản trị viên
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.administrator.index')
              <li class="nav-item">
                <a href="{{ route('admin.administrator.index') }}" class="nav-link{{ (strpos(url()->current(), '/administrator/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.administrator.create')
              <li class="nav-item">
                <a href="{{ route('admin.administrator.create') }}" class="nav-link{{ (strpos(url()->current(), '/administrator/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.role.index', 'admin.role.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/role') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/role') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Phòng ban & quyền
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.role.index')
              <li class="nav-item">
                <a href="{{ route('admin.role.index') }}" class="nav-link{{ (strpos(url()->current(), '/role/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.role.create')
              <li class="nav-item">
                <a href="{{ route('admin.role.create') }}" class="nav-link{{ (strpos(url()->current(), '/role/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.bank.index', 'admin.bank.create'])
          <li class="nav-item{{ ((strpos(url()->current(), '/bank') !== false) && (strpos(url()->current(), '/bank-loans') === false)) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ ((strpos(url()->current(), '/bank') !== false) && (strpos(url()->current(), '/bank-loans') === false)) ? ' active' : '' }}">
              <i class="nav-icon fa fa-university" aria-hidden="true"></i>
              <p>
                Quản lý tài chính
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.bank.index')
              <li class="nav-item">
                <a href="{{ route('admin.bank.index') }}" class="nav-link{{ (strpos(url()->current(), '/bank/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.bank.create')
              <li class="nav-item">
                <a href="{{ route('admin.bank.create') }}" class="nav-link{{ (strpos(url()->current(), '/bank/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.bank-loans.index', 'admin.bank-loans.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/bank-loans') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/bank-loans') !== false) ? ' active' : '' }}">
              <i class="nav-icon fa fa-file-invoice-dollar" aria-hidden="true"></i>
              <p>
                Quản lý vay nợ
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.bank-loans.index')
              <li class="nav-item">
                <a href="{{ route('admin.bank-loans.index') }}" class="nav-link{{ (strpos(url()->current(), '/bank-loans/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.bank-loans.create')
              <li class="nav-item">
                <a href="{{ route('admin.bank-loans.create') }}" class="nav-link{{ (strpos(url()->current(), '/bank-loans/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.customer.index', 'admin.customer.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/customer') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/customer') !== false) ? ' active' : '' }}">
              <i class="nav-icon fa fa-users" aria-hidden="true"></i>
              <p>
                Khách hàng
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.customer.index')
              <li class="nav-item">
                <a href="{{ route('admin.customer.index') }}" class="nav-link{{ (strpos(url()->current(), '/customer/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.customer.create')
              <li class="nav-item">
                <a href="{{ route('admin.customer.create') }}" class="nav-link{{ (strpos(url()->current(), '/customer/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.documents.index', 'admin.documents.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/documents') !== false) ? ' menu-open' : '' }}">
            <a href="#" class="nav-link{{ (strpos(url()->current(), '/documents') !== false) ? ' active' : '' }}">
              <i class="nav-icon fa fa-file-archive" aria-hidden="true"></i>
              <p>
                Chứng từ
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @permission('admin.documents.index')
              <li class="nav-item">
                <a href="{{ route('admin.documents.index') }}" class="nav-link{{ (strpos(url()->current(), '/documents/index') !== false) ? ' active' : '' }}">
                  <p>Danh sách</p>
                </a>
              </li>
              @endpermission
              @permission('admin.documents.create')
              <li class="nav-item">
                <a href="{{ route('admin.documents.create') }}" class="nav-link{{ (strpos(url()->current(), '/documents/create') !== false) ? ' active' : '' }}">
                  <p>Thêm</p>
                </a>
              </li>
              @endpermission
            </ul>
          </li>
          @endpermission
          @permission(['admin.report.index'])
          <li class="nav-item{{ (strpos(url()->current(), '/report') !== false) ? ' menu-open' : '' }}">
            <a href="{{ route('admin.report.index', ['type' => 'date']) }}" class="nav-link{{ (strpos(url()->current(), '/config') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-chart-area"></i>
              <p>
                Thống kê
              </p>
            </a>
          </li>
          @endpermission
          @permission(['admin.logadmin.index', 'admin.logadmin.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/logadmin') !== false) ? ' menu-open' : '' }}">
            <a href="{{ route('admin.logadmin.index') }}" class="nav-link{{ (strpos(url()->current(), '/logadmin') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Log hệ thống
              </p>
            </a>
          </li>
          @endpermission
          @permission(['admin.config.index', 'admin.config.create'])
          <li class="nav-item{{ (strpos(url()->current(), '/config') !== false) ? ' menu-open' : '' }}">
            <a href="{{ route('admin.config.index') }}" class="nav-link{{ (strpos(url()->current(), '/config') !== false) ? ' active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Cấu hình chung
              </p>
            </a>
          </li>
          @endpermission
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
  <!-- /.content-wrapper -->

  @include('admins.includes.loading')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<!-- Variable common -->
<script type="text/javascript">
  var domain = "{{ asset('/') }}";
</script>
<!-- Js common -->
<script src="{{ asset('js/common.js') }}"></script>
<!-- Custom js -->

{{--Select 2--}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>
@yield('js')
</body>
</html>
