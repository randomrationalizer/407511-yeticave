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
      <h2><?=$error_header; ?></h2>
      <p><?=$error_text; ?></p>
  </section>
</main>