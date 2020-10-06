{--
    @author Alexis Bogado
    @package graphic-framework
--}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Wire+One|Raleway:300,500,600,700" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ config('app.url') }}/assets/css/main.css" type="text/css">
    @get('styles')
</head>
<body class="overflow-hidden">
    <div id="preloader" class="h-100 w-100 bg-white fixed-top d-flex align-items-center justify-content-center">
        <i class="fas fa-circle-notch fa-spin text-blue"></i>
    </div>

    @add('includes.header')

    @get('contents')
    
    @add('includes.footer')
    
    @get('modals')
    <?php if (!auth()->loggedIn()): ?>
        @add('modals.register')
        @add('modals.login')
    <?php endif; ?>
    
    <script src="https://kit.fontawesome.com/fd2e347369.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="{{ config('app.url') }}/assets/js/main.js?{{ time() }}"></script>
    @get('scripts')

    <?php if (!auth()->loggedIn()): ?>
        <script src="{{ config('app.url') }}/assets/js/authentication.js?{{ time() }}"></script>
    <?php endif; ?>
</body>
</html>