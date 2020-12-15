<div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>

<div id="sticky-wrapper" class="sticky-wrapper is-sticky" style="height: 68px;">
    <header class="site-navbar js-sticky-header site-navbar-target" role="banner" style="">

        <div class="container">
            <div class="row align-items-center">

                <div class="col-6 col-xl-2">
                    <h1 class="mb-0 site-logo"><a href="<?= url(''); ?>" class="h2 mb-0"><img style="width: 100%;" src="<?= url('themes/assets/img/navbar-logo.png') ?>"></a></h1>
                </div>

                <div class="col-12 col-md-10 d-none d-xl-block">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                            <li><a href="<?= url('') ?>" class="nav-link">Início</a></li>
                            <?php if(isset($_SESSION['user'])): ?>
                                <li class="has-children">
                                    <a class="nav-link">Denúncias <span class="icon-angle-down"></span></a>
                                    <ul class="dropdown">
                                        <li><a href="<?= url('listReports') ?>" class="nav-link">Listar</a></li>
                                        <li><a href="<?= url('reports') ?>" class="nav-link">Mapa</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?= url('profile') ?>" class="nav-link">Perfil</a></li>
                                <li><a href="<?= url('logout') ?>" class="nav-link">Sair</a></li>
                            <?php else: ?>
                                <li><a href="<?= url('reports') ?>" class="nav-link">Denúncias</a></li>
                                <li><a href="<?= url('login') ?>" class="nav-link">Sou um agente</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>

                <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle float-right"><span class="icon-menu h3"></span></a></div>
            </div>
        </div>
    </header>
</div>
