const Signin = {

    validateForm: function(Form, AlertBox) {

        if (!Form.checkValidity()) { //HTML field validations

            const InvalidField = Form.querySelector(':invalid');

            let LabelText = '';

            if (InvalidField.id) {

                const Label = document.querySelector('label[for="'+InvalidField.id+'"]');

                if (Label){

                    LabelText = Label.textContent + ': ';

                }

            }

            AlertBox.textContent = LabelText + InvalidField.validationMessage;
            AlertBox.hidden = false;

            InvalidField.focus();

            return false;

        }

        const Email = document.getElementById('Email');
        let Regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(!Regex.test(Email.value)){

            AlertBox.textContent = 'Invalid email!';
            AlertBox.hidden = false;
            Email.focus();
            return false;

        }

        const EmailConfirmation = document.getElementById('EmailConfirmation');

        if(Email.value != EmailConfirmation.value){

            AlertBox.textContent = 'The email addresses provided do not match!';
            AlertBox.hidden = false;
            Email.focus();
            return false;

        }

        const Password = document.getElementById('Password');

        Regex = /[A-Z]/;

        if(!Regex.test(Password.value)){

            AlertBox.textContent = 'The password must contain at least one capital letter!';
            AlertBox.hidden = false;
            Password.focus();
            return false;

        }

        Regex = /[\W_]/;

        if(!Regex.test(Password.value)){

            AlertBox.textContent = 'The password must contain at least one special character!';
            AlertBox.hidden = false;
            Password.focus();
            return false;

        }

        const PasswordConfirmation = document.getElementById('PasswordConfirmation');

        if(Password.value != PasswordConfirmation.value){

            AlertBox.textContent = 'The passwords provided do not match!';
            AlertBox.hidden = false;
            Password.focus();
            return false;

        }

        AlertBox.textContent = '';
        AlertBox.hidden = true;
        return true;

    }

};