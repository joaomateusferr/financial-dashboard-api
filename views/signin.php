<section class="page-shell">

    <div class="page-card">

        <h1 class="page-title">Criar sua conta</h1>

        <p class="page-description">Preencha seus dados para começar a usar o painel.</p>

        <div class="form-alert" id="form-alert" role="alert" aria-live="polite" hidden>
        </div>

        <form class="form-grid" method="post" novalidate>

            <div class="form-field">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-input" type="email" id="email" name="email" placeholder="user@domain.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="email_confirmation">Confirmar e-mail</label>
                <input class="form-input" type="email" id="email_confirmation" name="email_confirmation" placeholder="user@domain.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Senha</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="Create a password" autocomplete="new-password" minlength="8" maxlength="50" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="password_confirmation">Confirmar senha</label>
                <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" minlength="8" maxlength="50" required>
            </div>

            <div class="form-row">
                <label class="form-check" for="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span>Li e aceito os termos de uso</span>
                </label>
            </div>

            <button class="btn-primary" type="submit">Criar conta</button>
        </form>

        <div class="page-footer">
            Já tem uma conta? <a href="<?php echo "/";?>">Entrar</a>
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