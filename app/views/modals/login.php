{--
    @author Alexis Bogado
    @package graphic-framework
--}

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 align-items-center pb-0">
                <h5 class="modal-title font-weight-bold text-uppercase" id="login-modal-title">Login</h5>

                <button type="button" class="close py-0" data-dismiss="modal" aria-label="Close">
                    <h1 class="mb-0 font-weight-bold" aria-hidden="true">&times;</h1>
                </button>
            </div>

            <div class="modal-body">
                <hr class="my-0">

                <form id="login-form" method="post">
                    <div class="row">
                        <div class="col-12 mt-3">
                            <label for="login-email">Email</label>
                            <input type="email" class="form-control" id="login-email" placeholder="Type your email here">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="login-password">Password</label>
                            <input type="password" class="form-control" id="login-password" placeholder="Type your password here">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <button type="submit" id="submit-button" class="btn btn-primary btn-block bg-blue" data-text="Sign in">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>