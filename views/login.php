<section class="page-shell overflow-x-auto">

    <div class="page-card">

        <h1 class="page-title">Log in to your account</h1>

        <p class="page-description">Pick up where you left off...</p>

        <div class="form-alert" id="form-alert" role="alert" aria-live="polite" hidden>
        </div>

        <form class="form-grid" method="post" action="/login/result" novalidate>

            <div class="form-field">
                <label class="form-label" for="Email">E-mail</label>
                <input class="form-input" type="email" id="Email" name="Email" placeholder="user@domain.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="Password">Password</label>
                <input class="form-input" type="Password" id="Password" name="Password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>

            <div class="form-row">
                <label class="form-check" for="Remember">
                    <input type="checkbox" id="Remember" name="Remember" value=1>
                    <span>Remember me</span>
                </label>

                <a class="form-link" href="/reset-password">Forgot your password?</a>

            </div>

            <button class="btn-primary" type="submit">Login</button>
        </form>

        <div class="page-footer">
            Don't have an account? <a href="/signin">Create account</a>
        </div>

    </div>

</section>

<script src="/js/login.js" defer></script>

<script>

    document.addEventListener('DOMContentLoaded', () => {

        const Form = document.querySelector('.form-grid');
        const AlertBox = document.getElementById('form-alert');

        Form.addEventListener('submit', (Event) => {

            const IsValid = Login.validateForm(Form, AlertBox);

            if (!IsValid) {

                Event.preventDefault();
                return;

            }

        });

    });

</script>
