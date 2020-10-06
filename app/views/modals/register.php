{--
    @author Alexis Bogado
    @package graphic-framework
--}

<div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="register-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 align-items-center pb-0">
                <h5 class="modal-title font-weight-bold text-uppercase" id="register-modal-title">Register</h5>

                <button type="button" class="close py-0" data-dismiss="modal" aria-label="Close">
                    <h1 class="mb-0 font-weight-bold" aria-hidden="true">&times;</h1>
                </button>
            </div>

            <div class="modal-body">
                <hr class="my-0">

                <form id="register-form" method="post">
                    <div class="row">
                        <div class="col-12 mt-3">
                            <label for="register-fullname">Full name</label>
                            <input type="text" class="form-control" id="register-fullname" placeholder="Type your full name here">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="register-email">Email</label>
                            <input type="email" class="form-control" id="register-email" placeholder="Type your email here">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="register-password">Password</label>
                            <input type="password" class="form-control" id="register-password" placeholder="Type your password here">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <button type="submit" id="submit-button" class="btn btn-primary btn-block bg-blue" data-text="Sign up">Sign up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>