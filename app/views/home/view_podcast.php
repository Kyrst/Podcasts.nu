<div class="container">
    <ul class="breadcrumb col-sm-12 col-xs-12">
        <li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
        <li><a href="<?= URL::route('poddar', array(), ''); ?>">Poddar</a></li>
        <li><?= $podcast->name; ?></li>
    </ul>
    <div class="row"></div>
    <div class="container">
        <!--IF Panorama-bild-->
    </div>

    <h1 class="podcast-head"><?= $podcast->name; ?></h1>
    <?php if ( $user !== NULL ): ?>
        <h1>HEJSAN</h1>
    <?php endif ?>
    <?php if ( $podcast->category ): ?>
    	<p class="podcast-category"><?= $podcast->category->title ?></p>
    <?php endif ?>
    <div style="margin-bottom:10px">
        <?= $podcast->get_subscription_link('Prenumerera', 'Prenumererar', $user, 'btn btn-default btn-sm') ?>
    </div>

    <div class="row">
        <div class="container col-lg-3 col-md-3 col-sm-5 col-xs-12">

            <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Podcast-bild -->
                <div id="links">
                    <?php if ( $podcast->homepage ): ?>
                        <h6><a href="<?= $podcast->homepage ?>">Hemsida</a></h6>
                    <?php endif ?>

                    <?php if ( $podcast->facebook ): ?>
                        <h6><a href="<?= $podcast->facebook ?>">Facebook</a></h6>
                    <?php endif ?>

                    <?php if ( $podcast->twitter ): ?>
                        <h6><a href="<?= $podcast->twitter ?>">Twitter</a></h6>
                    <?php endif ?>

                    <?php if ( $podcast->itunes ): ?>
                        <h6><a href="<?= $podcast->itunes ?>">Itunes</a></h6>
                    <?php endif ?>
                </div>
                <img class="media-object podcast-image" src="<?= $podcast->getImage('standard', false, true); ?>" width="250" height="" alt="...">
            </div>
            <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Podcast-information -->

				<?php if ( strlen($podcast) > 50 ): ?>
					<div id="description">
						<?= Str::limit($podcast->description, 100) ?> <a href="javascript:" id="show_more_description" class="btn btn-xs btn-primary">Visa mer</a>
					</div>

					<div id="full_description" style="display:none"><?= $podcast->description ?></div>
                <?php else: ?>
					<?= $podcast->description ?>
                <?php endif ?>
            </div>
        </div>
        <div class ="content col-lg-8 col-md-8 col-sm-7 col-xs-12">
            <div class="container episode-container">
                <!-- Avsnitt -->
                <h2>Avsnitt</h2>
                <div id="episodes_container">
					<?= $podcast_episodes_html ?>
				</div>

				<!-- Paging -->
				<div id="pagination_container">
					<?= $pagination_html ?>
				</div>
            </div>
        </div>
    </div>
</div>