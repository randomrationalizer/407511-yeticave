<section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
        <?php foreach ($categories as $category) : ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/<?=$category["id"] ;?>.html"><?=$category["name"] ;?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
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
                        <div class="lot__timer timer"><?=show_time_left($lot["start_date"], $lot["end_date"]); ?></div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    </section>