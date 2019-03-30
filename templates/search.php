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
	<div class="container">
	<section class="lots">
        <h2>Результаты поиска по запросу «<span><?=$search_query; ?></span>»</h2>
        <?php if(empty($lots)): ?>
        <p>Ничего не найдено по вашему запросу :(</p>
        <?php endif; ?>
        <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=filter_data($lot["img_path"]); ?>" width="350" height="260" alt="<?=filter_data($lot["name"]); ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=filter_data($lot["category"]); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$lot["id"] ;?>"><?=filter_data($lot["name"]); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=format_price($lot["start_price"]); ?></span>
                        </div>
                        <div class="lot__timer timer <?=show_finishing_class($lot["end_date"]); ?>"><?=show_time_left($lot["end_date"]); ?></div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
	  </section>
	  <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">
        
          <li class="pagination-item pagination-item-prev">
          <?php if ($cur_page > 1): ?>
            <a href="search.php?search=<?=$search_query; ?>&page=<?=($cur_page - 1); ?>">Назад</a>
          <?php else: ?>
            <a>Назад</a>
          <?php endif; ?>
          </li>
          
          <?php foreach ($pages as $page): ?>
            <?php $active_class = (intval($page) === intval($cur_page)) ? "pagination-item-active" : ""; ?>
            <li class="pagination-item <?=$active_class; ?>">
              <a href="search.php?search=<?=$search_query; ?>&page=<?=$page; ?>"><?=$page; ?></a>
            </li>
          <?php endforeach; ?>

          <li class="pagination-item pagination-item-next">
          <?php if ($cur_page < $pages_count): ?>
            <a href="search.php?search=<?=$search_query; ?>&page=<?=($cur_page + 1); ?>">Вперед</a>
          <?php else: ?>
            <a>Вперед</a>
          <?php endif;?>
          </li>
          
        </ul>
      <?php endif; ?>
    </div>
</main>
