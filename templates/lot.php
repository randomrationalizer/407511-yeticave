<main>
  <nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $category) : ?>
      <li class="nav__item">
        <a href="all_lots.php?id=<?=$category["id"] ;?>"><?=$category["name"] ;?></a>
      </li>
    <?php endforeach; ?>
    </ul>
  </nav>
  <section class="lot-item container">
    <h2><?=filter_data($lot["name"]); ?></h2>
    <div class="lot-item__content">
      <div class="lot-item__left">
        <div class="lot-item__image">
          <img src="<?=filter_data($lot["img_path"]); ?>" width="730" height="548" alt="<?=filter_data($lot["name"]); ?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=filter_data($lot["category"]); ?></span></p>
        <p class="lot-item__description"><?=filter_data($lot["description"]); ?></p>
      </div>
      <div class="lot-item__right">
        <div class="lot-item__state">
          <div class="lot-item__timer timer <?=show_finishing_class($lot["end_date"]); ?>">
            <?=show_time_left($lot["end_date"]); ?>
          </div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=format_price($current_price); ?></span>
            </div>
            <div class="lot-item__min-cost">
              Мин. ставка <span><?=format_price($min_bid); ?></span>
            </div>
          </div>
          <?php if ($is_auth && ($lot["author_id"] !== $user_id) && ($last_bid_autor !== $user_id) && !$is_expired): ?>
          <form class="lot-item__form" action="lot.php?id=<?=$lot["id"]; ?>" method="post">
            <?php 
              $error_class = isset($errors["cost"]) ? "form__item--invalid" : "";
              $error_text = isset($errors["cost"]) ? $errors["cost"] : "";
            ?>
            <p class="lot-item__form-item form__item <?=$error_class; ?>">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="text" name="cost" placeholder="<?=number_format($min_bid, 0, ".", " "); ?>">
              <span class="form__error"><?=$error_text; ?></span>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
          <?php endif; ?>
        </div>
        <div class="history">
          <h3>История ставок (<span><?=count($bids); ?></span>)</h3>
          <table class="history__list">
          <?php if(!empty($bids)): ?>
            <?php foreach ($bids as $bid): ?>
            <tr class="history__item">
              <td class="history__name"><?=filter_data($bid["username"]); ?></td>
              <td class="history__price"><?=number_format($bid["price"], 0, ".", " "); ?></td>
              <td class="history__time"><?=show_bid_age($bid["date"]); ?></td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>