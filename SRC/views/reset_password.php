<?php ob_start() ?>

<section>
      <div class="form-container">

            <?php if (!empty($error)): ?>
                  <div class="error"><?= htmlspecialchars($error) ?></div>
                  <a href="/">Retour sur la page de connexion</a>
            <?php endif; ?>

            <?php if (!empty($error_token)): ?>
                  <div class="error"><?= htmlspecialchars($error_token) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                  <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($reset && !$success): ?>

                  <h1>Réinitialisation du mot de passe</h1>

                  <form action="" method="POST" class="form_reset_password">

                        <div class="form__input_password">
                              <label for="new_password" class="sr-only">Nouveau mot de passe</label>
                              <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
                              <i class="toggle-eye bi bi-eye"></i>
                              <i class="toggle-eye bi bi-eye-slash" style="display:none;"></i>
                        </div>

                        <div class="form__input_password">
                              <label for="confirm_password" class="sr-only">Confirmation du mot de passe</label>
                              <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                              <i class="toggle-eye bi bi-eye"></i>
                              <i class="toggle-eye bi bi-eye-slash" style="display:none;"></i>
                        </div>

                        <button type="submit">Réinitialiser</button>

                  </form>
            <?php endif; ?>
      </div>
</section>

<?php
render('default', true, [
      'title' => "changement de mot de passe",
      'style' => "assets/css/reset_password.css",
      'js' => "assets/js/reset_password.js",
      'content' => ob_get_clean()
]) ?>