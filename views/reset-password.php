<?php echo $ID; ?>
<section class="page-shell overflow-x-auto">

    <div class="page-card">

        <h1 class="page-title">Start password reset</h1>

        <p class="page-description">Enter your details to start the password reset.</p>

        <div class="form-alert" id="form-alert" role="alert" aria-live="polite" hidden>
        </div>

        <form class="form-grid" method="post" action="/reset-password/result" novalidate>

            <div class="form-field">
                <label class="form-label" for="Email">E-mail</label>
                <input class="form-input" type="email" id="Email" name="Email" placeholder="user@domain.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="Password">Password</label>
                <input class="form-input" type="password" id="Password" name="Password" placeholder="Create a password" autocomplete="new-password" minlength="8" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="PasswordConfirmation">Confirmar senha</label>
                <input class="form-input" type="password" id="PasswordConfirmation" name="PasswordConfirmation" placeholder="Repeat your password" autocomplete="new-password" minlength="8" required>
            </div>

            <button class="btn-primary" type="submit">Reset password</button>

        </form>

        <div class="page-footer">
            Do you already have an account? <a href="<?php echo "/login";?>">Login</a>
        </div>

    </div>

</section>

<script src="/js/reset-password.js" defer></script>

<script>

    document.addEventListener('DOMContentLoaded', () => {

        const Form = document.querySelector('.form-grid');
        const AlertBox = document.getElementById('form-alert');

        Form.addEventListener('submit', (Event) => {

            const IsValid = ResetPassword.validateForm(Form, AlertBox);

            if (!IsValid) {

                Event.preventDefault();
                return;

            }

        });

    });

</script>
