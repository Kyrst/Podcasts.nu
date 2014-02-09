<div id="grid_view"<?php if (isset($selected_view_type) && $selected_view_type === 'list'): ?> style="display:none"<?php endif; ?>>
	<?php for ( $i = 0; $i < $num_podcasts; ++$i): ?>
		<?php $podcast = $podcasts[$i]; ?>

		<div class="artist col-lg-2 col-md-2 col-sm-3 col-xs-6">
			<div class="thumbnail">
				<a href="<?= $podcast->getLink('poddar'); ?>"><img src="<?= $podcast->getImage('standard', false, true); ?>" width="100%" height="" alt="..."></a>

				<div class="caption">
					<h3><a href="<?= $podcast->getLink('poddar'); ?>"><?= $podcast->name; ?></a></h3>

					<div class="btn-group" style="float:left">
						<button type="button" class="btn btn-default"><?= $podcast->get_subscription_link('Följ', 'Följer', $user) ?></button>
						<?php /*<button type="button" class="btn btn-default">0</button>
						<button type="button" class="btn btn-default">0</button>*/ ?>
					</div>

					<div style="float:left"><?= $podcast->print_rater() ?></div>
				</div>
			</div>
		</div>

	<?php endfor; ?>
</div>

<div id="list_view" style="display:<?php echo ((isset($selected_view_type) && $selected_view_type === 'grid') || !isset($selected_view_type)) ? 'none' : 'block'; ?>">
    <div class="container">

		<?php foreach ( $podcasts as $podcast ): ?>
            <div class="media">
                <a class="pull-left" href="<?= $podcast->getLink('poddar'); ?>" title="<?= $podcast->name; ?>">
                    <img class="media-object" src="<?= $podcast->getImage('standard', false, true); ?>" width="50" height="" alt="...">
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><?= $podcast->name; ?></h4>
                </div>
            </div>
		<?php endforeach; ?>

	</div>
</div>
