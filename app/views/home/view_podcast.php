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

            <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Podcast-bild -->
                <img class="media-object" src="<?= $podcast->getImage('standard', false, true); ?>" width="200" height="" alt="...">
            </div>
            <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Podcast-information -->
                <h3><?= $podcast->name; ?></h3>

				<div style="margin-bottom:10px">
					<?= $podcast->get_subscription_link('Följ', 'Följer', $user, 'btn btn-default btn-sm') ?>
				</div>

				<?php if ( strlen($podcast) > 50 ): ?>
					<div id="description"><?= Str::limit($podcast->description, 50) ?> <a href="javascript:" id="show_more_description" class="btn btn-xs btn-primary">Visa mer</a></div>
					<div id="full_description" style="display:none"><?= $podcast->description ?></div>
                <?php else: ?>
					<?= $podcast->description ?>
                <?php endif ?>

				<h4>Kategori</h4>
                <?= $podcast->category->title ?>

				<?php if ( $podcast->homepage ): ?>
					<h4>Webbplats</h4>
					<a href="<?= $podcast->homepage ?>"><?= $podcast->homepage ?></a>
                <?php endif ?>

				<?php if ( $podcast->facebook ): ?>
					<h4>Facebook</h4>
					<a href="<?= $podcast->facebook ?>"><?= $podcast->facebook ?></a>
				<?php endif ?>

				<?php if ( $podcast->twitter ): ?>
					<h4>Twitter</h4>
					<a href="<?= $podcast->twitter ?>"><?= $podcast->twitter ?></a>
				<?php endif ?>

				<?php if ( $podcast->itunes ): ?>
					<h4>iTunes</h4>
					<a href="<?= $podcast->itunes ?>"><?= $podcast->itunes ?></a>
				<?php endif ?>
            </div>
        </div>
        <div class ="content col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="container">
                <!-- Avsnitt -->

				<?php if ( $num_episodes > 0 ): ?>
					<div id="episodes">
						<?php foreach ( $episodes as $episode ): ?>
							<div class="episode clearfix">
								<div class="right">
									<h4>
										<?php if ( $episode->haveMedia() ): ?>
											<?= $episode->printPlayButton(); ?>
										<?php endif; ?>

										<a href="<?= $episode->getLink('avsnitt'); ?>"><?=$episode->title; ?></a>
									</h4>
									<p class="created"><?=date('Y-m-d H:i:s', $episode->pub_date) ?></p>

									<?php if ( $user !== NULL && $user->is_admin() ): ?>
										<div class="btn-group" style="margin-top:8px">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
												Admin <span class="caret"></span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li><a href="<?= URL::to('admin/avsnitt/' . $episode->id) ?>">Ändra</a></li>
												<li><a href="<?= URL::to('admin/avsnitt/ta-bort/' . $episode->id) ?>">Ta bort</a></li>
											</ul>
										</div>
									<?php endif ?>
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