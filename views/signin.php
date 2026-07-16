<section class="page-shell overflow-x-auto">

    <div class="page-card">

        <h1 class="page-title">Criar sua conta</h1>

        <p class="page-description">Preencha seus dados para começar a usar o painel.</p>

        <div class="form-alert" id="form-alert" role="alert" aria-live="polite" hidden>
        </div>

        <form class="form-grid" method="post" action="/signin/result" novalidate>

            <div class="form-field">
                <label class="form-label" for="Email">E-mail</label>
                <input class="form-input" type="email" id="Email" name="Email" placeholder="user@domain.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="EmailConfirmation">Email Confirmation</label>
                <input class="form-input" type="email" id="EmailConfirmation" name="EmailConfirmation" placeholder="user@domain.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="Password">Password</label>
                <input class="form-input" type="password" id="Password" name="Password" placeholder="Create a password" autocomplete="new-password" minlength="8" maxlength="50" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="PasswordConfirmation">Password Confirmation</label>
                <input class="form-input" type="password" id="PasswordConfirmation" name="PasswordConfirmation" placeholder="Repeat your password" autocomplete="new-password" minlength="8" maxlength="50" required>
            </div>

            <div class="form-row">
                <label class="form-check" for="Terms">
                    <input type="checkbox" id="Terms" name="Terms" value="true" required>
                    <span>Li e aceito os termos de uso</span>
                </label>
            </div>

            <button class="btn-primary" type="submit">Criar conta</button>
        </form>

        <div class="page-footer">
            Já tem uma conta? <a href="/login">Entrar</a>
        </div>

    </div>

</section>

<script src="/js/signin.js" defer></script>

<script>

    document.addEventListener('DOMContentLoaded', () => {

        const Form = document.querySelector('.form-grid');
        const AlertBox = document.getElementById('form-alert');

        Form.addEventListener('submit', (Event) => {

            const IsValid = Signin.validateForm(Form, AlertBox);

            if (!IsValid) {

                Event.preventDefault();
                return;

            }

        });

    });

</script>
