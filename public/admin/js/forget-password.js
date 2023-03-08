var forgetPasswordForm = $("#forget-password-form");

forgetPasswordForm.validate({
    rules: {
        email: {
            email: true,
        },

    },
    submitHandler: function(form) {
        form.submit();
    }
});