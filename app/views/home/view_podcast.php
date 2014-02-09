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
    <div style="margin-bottom:10px">
        <?= $podcast->get_subscription_link('Prenumerera', 'Prenumererar', $user, 'btn btn-default btn-sm') ?>
    </div>
    <h1 class="podcast-head"><?= $podcast->name; ?></h1><p class="podcast-category"><?= $podcast->category->title ?></p>

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
					<div id="description"><?= Str::limit($podcast->description, 100) ?> <a href="javascript:" id="show_more_description" class="btn btn-xs btn-primary">Visa mer</a></div>
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
				<?php if ( $num_episodes > 0 ): ?>
					<div id="episodes">
						<?php foreach ( $episodes as $episode ): ?>

                            <div class="media">
                                <?php if ( $episode->podcast !== NULL ): ?>


                                    <div class="media-body">
                                        <h3 class="episode-head"><?php endif ?><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a></h3>
                                        <p class="pub-date">(<?=date('Y-m-d H:i:s', $episode->pub_date) ?>)</p>
                                        <div class="clear"></div>

                                        <?= $episode->print_rater() ?>

										<?php if ( $user !== NULL ): ?>
											<p class="episode-status"><?= $user->get_episode_status($episode->id) ?></p>
										<?php endif ?>
                                    </div>
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


						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<p>Inga avsnitt.</p>
				<?php endif; ?>

            </div>
        </div>
    </div>
</div>