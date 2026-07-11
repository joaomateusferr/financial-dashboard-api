<section class="page-shell">

    <div class="page-card">

        <h1 class="page-title">Entrar na sua conta</h1>

        <p class="page-description">Continue de onde parou...</p>

        <form class="form-grid" method="post">

            <div class="form-field">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-input" type="email" id="email" name="email" placeholder="seuemail@empresa.com" autocomplete="email" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Senha</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="Digite sua senha" autocomplete="current-password" required>
            </div>

            <div class="form-row">
                <label class="form-check" for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Lembrar-me</span>
                </label>

                <a class="form-link" href="<?php echo "/?page=start-reset-password";?>">Esqueci minha senha</a>

            </div>

            <button class="btn-primary" type="submit">Entrar</button>
        </form>

        <div class="page-footer">
            Não tem uma conta? <a href="<?php echo "/?page=signin";?>">Criar conta</a>
        </div>

    </div>

</section>
