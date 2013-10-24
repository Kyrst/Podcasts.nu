<div class="jumbotron">
	<div class="container">
		<h1>Podcasts.Nu!</h1>
		<p>Svenska Podcasts. Lyssna direkt i din dator, surfplatta eller mobil!</p>
		<p><a class="btn btn-primary btn-lg">Läs mer</a></p>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="content col-xs-12 col-sm-8 col-md-8 col-lg-8">
			<div class="page-header">
				<h3 class="icon news-items">Nyheter</h3>
			</div>
			<div class="container">
				<?php foreach ( $latest_news_items as $news_item ): ?>
					<div class="row">
						<strong><?= $news_item->title; ?></strong> - <em><?= date('Y-m-d', strtotime($news_item->created_at)); ?></em>
						<?= Str::limit($news_item->content, 50); ?>
						<a href="<?= URL::to('nyheter/' . date('Y-m-d', strtotime($news_item->created_at)) . '/' . $news_item->slug); ?>">Läs mer...</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="sidebar col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="page-header">
				<h3 class="icon comment">Kommenterat</h3>
			</div>
			<div class="container">
				<div class="row">
					<p>
						Oscar kommenterade Alex Och Sigges "grym!"
					</p>
					<p>
						Oscar kommenterade
						Den blÃ¥ hÃ¤sten "Otroligt bra avsnitt! mycket intressant"
					</p>
				</div>
			</div>
		</div>
		<div class="sidebar col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<div class="page-header">
				<h3 class="icon played">Spelas Nu</h3>
			</div>
			<div class="container">
				<div class="row">
					<p>
						C/o Hannah Och Amanda 57. â€Cellulit 2013â€
					</p>
					<p>
						Alex Och Sigges 21. "Den inre resan"
					</p>
				</div>
			</div>
		</div>
	</div>
</div>