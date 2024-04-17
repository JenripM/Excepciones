<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>A U D I T O R I A I N F O R M A T I C A</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
   

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <a href="{{route('master')}}">
            <img src="/adminlte/dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
          </a>
        </div>
        <div class="info">
          <a href="{{route('user.perfil',Auth::user()->id)}}" class="d-block">
            @if(Auth::check())
                {{ Auth::user()->name }}
            @endif
          </a>
        </div>
      </div> 
      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            @if(Auth::user()->name)
                <a href="{{route('datosConexion')}}" class="nav-link">
                  <p>
                    Conexión
                   
                  </p>
                </a>
            @endif
          </li>
          
          {{-- <li class="nav-item" >
            <a href="{{route('tablas')}}" class="nav-link">
              <p>
                Tablas
               
              </p>
            </a>
          </li> --}}
        
          @if(Session::get('rol')->tablas == 1)
            <li class="nav-item">
              <a href="{{route('tablasMostrar')}}" class="nav-link">
                <p>
                  Tablas
                </p>
              </a>
            </li>
          @endif
          
         
          @if(Session::get('rol')->vista_sql == 1)
            <li class="nav-item">
              <a href="{{route('sqlDinamico')}}" class="nav-link">
                <p>
                  Instruccion SQL
                </p>
              </a>
            </li>
          @endif
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Excepciones
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              @if(Session::get('rol')->excepciones_s == 1)
                  <li class="nav-item">
                    <a href="{{route('secuencialTablas')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Excepciones en registros secuenciales
                      </p>
                    </a>
                  </li>
              @endif

  
              @if(Session::get('rol')->excepciones_c == 1)
                  <li class="nav-item">
                    <a href="{{route('integridadCampoTablas')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Excepciones en integridad de campos
                      </p>
                    </a>
                  </li>
              @endif

              
              @if(Session::get('rol')->excepciones_i == 1)
                <li class="nav-item">
                  <a href="{{route('cabeceraTablas')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Excepciones en integridad de tablas
                    </p>
                  </a>
                </li>
              @endif
             
           
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Reportes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              @if(Session::get('rol')->reportes_s == 1)
                <li class="nav-item">
                  <a href="{{route('secuencialidadREPORTE')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Excepciones en registros secuenciales
                    </p>
                  </a>
                </li>
              @endif

              
              @if(Session::get('rol')->reportes_c == 1)
                <li class="nav-item">
                  <a href="{{route('camposREPORTE')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Excepciones en integridad de campos
                    </p>
                  </a>
                </li>
              @endif

              
              @if(Session::get('rol')->reportes_i == 1)
                  <li class="nav-item">
                    <a href="{{ route('cabeceraREPORTE') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Excepciones en integridad de tablas</p>
                    </a>
                </li>  
              @endif
            
              
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Privilegios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              

              @if(Session::get('rol')->roles == 1)
                <li class="nav-item">
                  <a href="{{route('rol.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Roles
                    </p>
                  </a>
                </li>
              @endif

              
              @if(Session::get('rol')->usuarios == 1)
                <li class="nav-item">
                  <a href="{{route('user.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Usuarios
                    </p>
                  </a>
                </li>
              @endif

          
            
            </ul>
          </li>
        </ul>
  <li class="nav-item">
    <a href="{{route('logout')}}" class="nav-link">
      
      <p>Cerrar Sesión
      </p>
    </a>
  </li>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
         
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      @yield('contenido')
      @if (!trim($__env->yieldContent('contenido')))
      <div class="container">
        <div class="row">
            @if(Auth::check())
                <h1>Bienvenido, {{ Auth::user()->name }}!</h1>
                <!-- Otros datos del usuario -->
            @else
                <h1>No hay ningún usuario autenticado.</h1>
            @endif
        </div>
      </div>
    @endif
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.1.0
    </div>
    <strong>Copyright &copy; 2024 <a href="#">Auditoria</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adminlte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/adminlte/dist/js/demo.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
@yield('script')
</body>
</html>
