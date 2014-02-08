<!--<div class="jumbotron">
	<div class="container">
		<h1>Podcasts.Nu!</h1>
		<p>Svenska Podcasts. Lyssna direkt i din dator, surfplatta eller mobil!</p>
		<p><a class="btn btn-primary btn-lg">Läs mer</a></p>
	</div>
</div>-->
<!--  Carousel - consult the Twitter Bootstrap docs at
      http://twitter.github.com/bootstrap/javascript.html#carousel -->
<p>Hejsan</p>
<div id="panorama-front" class="carousel slide"><!-- class of slide for animation -->
    <div class="carousel-inner">

        <div class="item active"><!-- class of active since it's the first item -->
            <img src="images/carousel/panorama-front-1.jpg" alt="" />
            <div class="carousel-caption">
                <h3>Stor Info här</h3>
                <p>Infotext här</p>
            </div>
        </div>
        <div class="item">
            <img src=""images/carousel/panorama-front-1.jpg"" alt="" />
            <div class="carousel-caption">
                <h3>Stor Info här</h3>
                <p>Infotext här</p>
                <p><a href="/avsnitt"></a></p>
            </div>
        </div>
        <div class="item">
            <img src=""images/carousel/panorama-front-1.jpg"" alt="" />
            <div class="carousel-caption">
                <h3>Stor Info här</h3>
                <p>Infotext här</p>
            </div>
        </div>
        <div class="item">
            <img src=""images/carousel/panorama-front-1.jpg"" alt="" />
            <div class="carousel-caption">
                <h3>Stor Info här</h3>
                <p>Infortext här</p>
            </div>
        </div>
    </div><!-- /.carousel-inner -->
    <!--  Next and Previous controls below
          href values must reference the id for this carousel -->
    <a class="carousel-control left" href="#this-carousel-id" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#this-carousel-id" data-slide="next">&rsaquo;</a>
</div><!-- /.carousel -->

<div class="container">
	<div class="row">
		<div class="content col-xs-12 col-sm-8 col-md-8 col-lg-8">
			<div class="page-header">
				<h3 class="icon news-items">Nyheter</h3>
			</div>

			<div class="container">
				<?php foreach ( $latest_news_and_blog_items as $news_or_blog_item ): ?>
					<div class="row">
						<strong><?= e($news_or_blog_item['title']); ?></strong> - <em><?= date('Y-m-d', $news_or_blog_item['timestamp']); ?></em>

						<?= Str::limit($news_or_blog_item['content'], 250); ?>

						<br>

						<a href="<?= $news_or_blog_item['link'] ?>" class="btn btn-xs btn-primary">Läs mer</a>
					</div>
				<?php endforeach; ?>

			</div>
		</div>
		<div class="sidebar col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="page-header">
				<h3 class="icon comment">Kommenterat</h3>
			</div>
			<div class="container">
				<?php foreach ( $latest_comments as $comment ): ?>
					<div class="row">
						<div class="col-lg-2">
							<?= $comment['avatar'] ?>
						</div>

						<div class="col-lg-10">
							<?= $comment['comment'] ?>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>

		<?php if ( count($listens_right_now) > 0 ): ?>
			<div class="sidebar col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<div class="page-header">
					<h3 class="icon played">Spelas Nu</h3>
				</div>
				<div class="container">
					<?php foreach ( $listens_right_now as $listen ): ?>
						<div class="row">
							<p>
								<?= $listen['text'] ?>
							</p>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		<?php endif ?>
	</div>
</div>