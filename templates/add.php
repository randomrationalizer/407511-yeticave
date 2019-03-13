<?php $error_class = count($errors) ? "form--invalid" : ""; ?>
<form class="form form--add-lot container <?=$error_class; ?>" action="add.php" method="post" enctype ="multipart/form-data">
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <?php 
      $error_class = isset($errors["name"]) ? "form__item--invalid" : "";
      $value = isset($lot["name"]) ? filter_data($lot["name"]) : ""; 
      $error_text = isset($errors["name"]) ? $errors["name"] : "";
    ?>
    <div class="form__item <?=$error_class; ?>">
      <label for="lot-name">Наименование</label>
      <input id="lot-name" type="text" name="lot[name]" placeholder="Введите наименование лота" value="<?=$value; ?>" required>
      <span class="form__error"><?=$error_text; ?></span>
    </div>

    <?php 
      $error_class = isset($errors["category"]) ? "form__item--invalid" : ""; 
      $value = isset($lot["category"]) ? filter_data($lot["category"]) : ""; 
      $error_text = isset($errors["category"]) ? $errors["category"] : "";
    ?>
    <div class="form__item <?=$error_class; ?>">
      <label for="category">Категория</label>
      <select id="category" name="lot[category]" required>
        <option value="0">Выберите категорию</option>
        <?php foreach ($categories as $category): ?>
        <?php $is_selected = ($category["id"] === $value) ? "selected" : ""; ?>
          <option value="<?=$category["id"]; ?>" <?=$is_selected; ?>><?=$category["name"]; ?></option>
        <?php endforeach; ?>
      </select>
      <span class="form__error"><?=$error_text; ?></span>
    </div>
  </div>

  <?php 
    $error_class = isset($errors["description"]) ? "form__item--invalid" : ""; 
    $value = isset($lot["description"]) ? filter_data($lot["description"]) : "";
    $error_text = isset($errors["description"]) ? $errors["description"] : "";
  ?>
  <div class="form__item form__item--wide <?=$error_class; ?>">
    <label for="message">Описание</label>
    <textarea id="message" name="lot[description]" placeholder="Напишите описание лота" required><?=$value; ?></textarea>
    <span class="form__error"><?=$error_text; ?></span>
	</div>
	  
  <?php 
      $error_class = isset($errors["file"]) ? "form__item--invalid" : "";
      $uploaded_class = isset($lot["img_path"]) ? "form__item--uploaded" : "";
      $error_text = isset($errors["file"]) ? $errors["file"] : "";
  ?>
  <div class="form__item form__item--file <?=$error_class; ?> <?=$uploaded_class; ?>">
    <label>Изображение</label>
    <div class="preview">
      <button class="preview__remove" type="button">x</button>
      <div class="preview__img">
        <img src="" name="lot-photo-preview" width="113" height="113" alt="Изображение лота">
      </div>
    </div>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="photo2" name="lot-photo" value="">
      <label for="photo2">
        <span>+ Добавить</span>
      </label>
    </div>
    <span class="form__error"><?=$error_text; ?></span>
  </div>

  <div class="form__container-three">
    <?php 
      $error_class = isset($errors["start_price"]) ? "form__item--invalid" : "";
      $value = isset($lot["start_price"]) ? intval($lot["start_price"]) : "";
      $error_text = isset($errors["start_price"]) ? $errors["start_price"] : "";
    ?>
    <div class="form__item form__item--small <?=$error_class; ?>">
      <label for="lot-rate">Начальная цена</label>
		  <input id="lot-rate" type="number" name="lot[start_price]" value="<?=$value; ?>" placeholder="0" required>
      <span class="form__error"><?=$error_text; ?></span>
    </div>

    <?php
      $error_class = isset($errors["step"]) ? "form__item--invalid" : "";
      $value = isset($lot["step"]) ? intval($lot["step"]) : "";
      $error_text = isset($errors["step"]) ? $errors["step"] : "";
    ?>
    <div class="form__item form__item--small <?=$error_class; ?>">
		  <label for="lot-step">Шаг ставки</label>
		  <input id="lot-step" type="number" name="lot[step]" value="<?=$value; ?>" placeholder="0" required>
      <span class="form__error"><?=$error_text; ?></span>
    </div>

    <?php
      $error_class = isset($errors["end_date"]) ? "form__item--invalid" : "";
      $value = isset($lot["end_date"]) ? filter_data($lot["end_date"]) : "";
      $error_text = isset($errors["end_date"]) ? $errors["end_date"] : "";
    ?>
    <div class="form__item <?=$error_class; ?>">
      <label for="lot-date">Дата окончания торгов</label>
      <input class="form__input-date" id="lot-date" type="date" name="lot[end_date]" value="<?=$value; ?>" required>
      <span class="form__error"><?=$error_text; ?></span>
    </div>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>