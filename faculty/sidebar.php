<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="dropdown">
    <a href="./" class="brand-link d-flex flex-column justify-content-center align-items-center">
      <div class="logo"></div>
      <h4 class="text-center p-0 m-0"><b>Admin Panel</b></h4>

    </a>

  </div>
  <div class="sidebar">
    <nav class="sidenav">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
        data-accordion="false">
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=result" class="nav-link nav-result">
            <i class="nav-icon fas fa-th-list"></i>
            <p>
              Evaluation Result
            </p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<style>
  .logo {
    background-image: url('./assets/img/logo.png');
    background-size: cover;
    background-position: center;
    width: 100px;
    height: 100px;
    border-radius: 50%;
  }
  .sidenav {
    margin-top: 100px;
    transition: margin-top 0.3s ease;
  }
  .sidenav.closed {
    margin-top: 0;
  }
</style>
<script>
  $(document).ready(function () {
    $('[data-widget=pushmenu]').on('click', function () {
      $('.sidenav').toggleClass('closed')
    });
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
    if (s != '')
      page = page + '_' + s;
    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active')
      if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }
      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open')
      }

    }

  })
</script>