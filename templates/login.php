<?php $error_class = count($errors) ? "form--invalid" : ""; ?>
<form class="form container <?=$error_class; ?>" action="login.php" method="post">
    <h2>Вход</h2>

    <?php 
      $error_class = isset($login["email"]) ? "form__item--invalid" : "";
      $value = isset($login["email"]) ? filter_data($login["email"]) : ""; 
      $error_text = isset($errors["email"]) ? $errors["email"] : "";
    ?>
    <div class="form__item <?=$error_class; ?>">
    	<label for="email">E-mail*</label>
        <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?=$value; ?>" required>
        <span class="form__error"><?=$error_text; ?></span>
    </div>

    <?php 
      $error_class = isset($errors["password"]) ? "form__item--invalid" : "";
      $error_text = isset($errors["password"]) ? filter_data($errors["password"]) : "";
    ?>
    <div class="form__item form__item--last <?=$error_class; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="login[password]" placeholder="Введите пароль" required>
        <span class="form__error"><?=$error_text; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>