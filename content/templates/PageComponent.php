    <article class="main-article <?= $sectionId ?>-page">
      <header class="">
        <div class="content">
          <h2 class="page-title"><?= $title ?></h2>
        </div>
      </header>

      <section class="article-content">
        <?= $context->getFormattedContent($content); ?>
      </section><!-- end article-content -->
    </article> <!-- end main-article -->


