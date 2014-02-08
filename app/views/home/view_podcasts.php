<div id="panorama-front" class="carousel slide"><!-- class of slide for animation -->
    <div class="carousel-inner">

        <div class="item active"><!-- class of active since it's the first item -->
            <img src="images/carousel/panorama-front-1.jpg" alt="" />
            <div class="carousel-caption">
                <h3 class="carousel">Välkommen</h3>
                <p class="carousel">Infotext här</p>
            </div>
        </div>
        <div class="item">
            <img src=""images/carousel/panorama-front-2.jpg"" alt="" />
            <div class="carousel-caption">
                <h3>Stor Info här</h3>
                <p>Infotext här</p>
                <p><a href="/avsnitt"></a></p>
            </div>
        </div>
        <div class="item">
            <img src=""images/carousel/panorama-front-3.jpg"" alt="" />
            <div class="carousel-caption">
                <h3>Stor Info här</h3>
                <p>Infotext här</p>
            </div>
        </div>
        <div class="item">
            <img src=""images/carousel/panorama-front-4.jpg"" alt="" />
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
<h1>Poddar</h1>

<a href="javascript:" data-view_type="grid" class="change-view-type btn btn-primary">Grid</a>
<a href="javascript:" data-view_type="list" class="change-view-type btn btn-primary">List</a>

<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span id="selected_category_text">Alla kategorier</span>
		<span class="caret"></span>
	</button>

	<ul class="dropdown-menu">
		<?php foreach ( $categories as $category ): ?>
			<li><a href="javascript:" data-id="<?= $category->id; ?>" data-title="<?= $category->title; ?>" class="category"><?= $category->title; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>

<div id="podcasts">
	<?= $podcasts_html; ?>
</div>