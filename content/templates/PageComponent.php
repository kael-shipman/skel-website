    <article class="main-article">
      <header class="">
        <div class="content">
          <h2 class="page-title"><?= str_replace(array('<p>','</p>'), '', $context->getFormattedContent($title)) ?></h2>
        </div>
      </header>

      <section class="article-content">
        <?= $context->getFormattedContent($content); ?>
      </section><!-- end article-content -->
    </article> <!-- end main-article -->


