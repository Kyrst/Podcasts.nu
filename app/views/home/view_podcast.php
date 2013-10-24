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
    <div class="row">
        <div class="container col-lg-4 col-md-4 col-sm-12 col-xs-12">

            <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12"
                <!-- Podcast-bild -->
                <img class="media-object" src="<?= $podcast->getImage('standard', false, true); ?>" width="250" height="" alt="...">
            </div>
            <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Podcast-information -->
                <h3><?= $podcast->name; ?></h3>
                <h4>Podcast-information.</h4>
            </div>
        </div>
        <div class ="content col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="container">
                <!-- Avsnitt -->

				<?php if ( $num_episodes > 0 ): ?>
					<div id="episodes">
						<?php foreach ( $episodes as $episode ): ?>
							<div class="episode">
								<?php if ( $episode->haveMedia() ): ?>
									<?= $episode->printPlayButton(); ?>
								<?php endif; ?>

								<div class="right">
									<h2><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->podcast->name, ' - ', $episode->title; ?></a></h2>

									<p class="created"><?= $episode->created_at; ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<p>Inga avsnitt.</p>
				<?php endif; ?>

            </div>
        </div>
    </div>
</div>