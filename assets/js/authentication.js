/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

$(document).ready(() => {
    $('#login-form').on('submit', e => {
        e.preventDefault()
        
        const form = $(e.target)

        toggleLoadingForm(form)
        clearFormErrors(form)

        const email = document.getElementById('login-email')
        const password = document.getElementById('login-password')

        $.ajax({
            type: 'POST',
            url: '/api/auth/signin',
            data: {
                email: email.value,
                password: password.value
            }
        }).done(({ success, data }) => {
            if (!success)
                return

            console.log(data.message)
            location.href = data.redirectPath
        }).fail(({ responseJSON }) => {
            const errors = responseJSON.data.errors

            if (errors.email)
                showInputError(email, errors.email)

            if (errors.password)
                showInputError(password, errors.password)

        }).always(() => toggleLoadingForm(form))
    })
    
    $('#register-form').on('submit', e => {
        e.preventDefault()
        
        const form = $(e.target)

        toggleLoadingForm(form)
        clearFormErrors(form)

        const fullName = document.getElementById('register-fullname')
        const email = document.getElementById('register-email')
        const password = document.getElementById('register-password')

        $.ajax({
            type: 'POST',
            url: '/api/auth/signup',
            data: {
                username: fullName.value,
                email: email.value,
                password: password.value
            }
        }).done(({ success, data }) => {
            if (!success)
                return

            console.log(data.message)
            location.href = data.redirectPath
        }).fail(({ responseJSON }) => {
            const errors = responseJSON.data.errors

            if (errors.username)
                showInputError(fullName, errors.username)

            if (errors.email)
                showInputError(email, errors.email)

            if (errors.password)
                showInputError(password, errors.password)

        }).always(() => toggleLoadingForm(form))
    })
})

toggleLoadingForm = form => {
    const isDisabled = $('#submit-button', form).prop('disabled')

    $('.form-control, #submit-button', form).prop('disabled', !isDisabled)
    $('#submit-button', form).html(isDisabled ? $('#submit-button', form).data('text') : '<i class="fas fa-spinner fa-spin"></i>')
}

showInputError = (element, message) => {
    element.classList.add('is-invalid')
    element.nextElementSibling.innerHTML = message
}

clearFormErrors = form => {
    $('.is-invalid', form).next().html('')
    $('.is-invalid', form).removeClass('is-invalid')
}