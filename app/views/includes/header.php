{--
    @author Alexis Bogado
    @package graphic-framework
--}

<nav id="nav-menu" class="navbar navbar-expand-md navbar-dark bg-blue py-4{{ ((route()->name === 'index') ? ' fixed-top' : '') }}">
    <div class="container">
        <a class="navbar-brand p-0 m-0 font-weight-bold text-uppercase{{ (route()->name === 'index' ? ' d-none' : '') }}" href="{{ config('app.url') }}">
            Graphic Framework
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-menu-items" aria-controls="nav-menu-items" aria-expanded="false" aria-label="Toggle menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="nav-menu-items">
            <div class="navbar-nav text-center">
                <?php if (auth()->loggedIn()): ?>
                <a class="nav-item nav-link font-weight-bold font-size-14 text-uppercase" href="{{ route('logout')->path() }}">Logout</a>
                <?php else: ?>
                <a class="nav-item nav-link font-weight-bold font-size-14 text-uppercase{{ ((route()->name === 'index') ? ' active' : '') }}" data-section-id="main" href="{{ ((route()->name === 'index') ? '#' : (config('app.url') . '/#')) }}main">
                    <span>Home</span>
                </a>

                <a class="nav-item nav-link font-weight-bold font-size-14 text-uppercase" data-section-id="technologies" href="{{ ((route()->name === 'index') ? '#' : (config('app.url') . '/#')) }}technologies">
                    <span>Technologies</span>
                </a>

                <a class="nav-item nav-link font-weight-bold font-size-14 text-uppercase" role="button" id="login-button" data-toggle="modal" data-target="#register-modal">Register</a>
                <a class="nav-item nav-link font-weight-bold font-size-14 text-uppercase" role="button" id="login-button" data-toggle="modal" data-target="#login-modal">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>