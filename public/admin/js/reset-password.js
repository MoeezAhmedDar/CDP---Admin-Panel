var resetPasswordForm = $("#reset-password-form");

resetPasswordForm.validate({
    rules: {
        email: {
            email: true,
        },

    },
    submitHandler: function(form) {
        form.submit();
    }
});