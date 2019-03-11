<?php $error_class = count($errors) ? "form--invalid" : ""; ?>
<form class="form container <?=$error_class; ?>" action="signup.php" method="post" enctype ="multipart/form-data">
	<h2>Регистрация нового аккаунта</h2>
	<?php 
      $error_class = isset($errors["email"]) ? "form__item--invalid" : "";
      $value = isset($signup["email"]) ? $signup["email"] : ""; 
      $error_text = isset($errors["email"]) ? $errors["email"] : "";
    ?>
	<div class="form__item <?=$error_class; ?>">
		<label for="email">E-mail*</label>
		<input id="email" type="text" name="signup[email]" placeholder="Введите e-mail" value="<?=$value; ?>" required>
		<span class="form__error"><?=$error_text; ?></span>
	</div>

	<?php 
      $error_class = isset($errors["password"]) ? "form__item--invalid" : "";
      $error_text = isset($errors["password"]) ? $errors["password"] : "";
  ?>
	<div class="form__item <?=$error_class; ?>">
		<label for="password">Пароль*</label>
		<input id="password" type="text" name="signup[password]" placeholder="Введите пароль">
		<span class="form__error"><?=$error_text; ?></span>
	</div>

	<?php 
      $error_class = isset($errors["username"]) ? "form__item--invalid" : "";
      $value = isset($signup["username"]) ? filter_data($signup["username"]) : ""; 
      $error_text = isset($errors["username"]) ? $errors["username"] : "";
    ?>
	<div class="form__item <?=$error_class; ?>">
		<label for="name">Имя*</label>
		<input id="name" type="text" name="signup[username]" placeholder="Введите имя" value="<?=$value; ?>" required>
		<span class="form__error"><?=$error_text; ?></span>
	</div>

	<?php 
      $error_class = isset($errors["contacts"]) ? "form__item--invalid" : "";
      $value = isset($signup["contacts"]) ? filter_data($signup["contacts"]) : ""; 
      $error_text = isset($errors["contacts"]) ? $errors["contacts"] : "";
    ?>
	<div class="form__item <?=$error_class; ?>">
		<label for="message">Контактные данные*</label>
		<textarea id="message" name="signup[contacts]" placeholder="Напишите как с вами связаться" required><?=$value; ?></textarea>
		<span class="form__error"><?=$error_text; ?></span>
	</div>

	<?php 
      $error_class = isset($errors["file"]) ? "form__item--invalid" : "";
      $path = isset($signup["avatar"]) ? $signup["avatar_path"] : "";
      $error_text = isset($errors["file"]) ? $errors["file"] : "";
  	?>
	<div class="form__item form__item--file form__item--last <?=$error_class; ?>">
		<label>Аватар</label>
		<div class="preview">
			<button class="preview__remove" type="button">x</button>
			<div class="preview__img">
				<img src="<?=$path; ?>" width="113" height="113" alt="Ваш аватар">
			</div>
		</div>
		<div class="form__input-file">
			<input class="visually-hidden" type="file" id="photo2" name="avatar" value="<?=$path; ?>">
			<label for="photo2">
				<span>+ Добавить</span>
			</label>
		</div>
		<span class="form__error"><?=$error_text; ?></span>
	</div>
	  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
	  <button type="submit" class="button">Зарегистрироваться</button>
	  <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>