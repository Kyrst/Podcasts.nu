<div id="grid_view"<?php if (isset($selected_view_type) && $selected_view_type === 'list'): ?> style="display:none"<?php endif; ?>>
	<?php for ( $i = 0; $i < $num_podcasts; ++$i): ?>
		<?php $podcast = $podcasts[$i]; ?>

		<div class="artist col-lg-3 col-md-3 col-sm-4 col-xs-6">
			<div class="thumbnail">
				<a href="<?= $podcast->getLink('poddar'); ?>"><img src="<?= $podcast->getImage('standard', false, true); ?>" width="270" height="" alt="..."></a>

				<div class="caption">
					<h3><a href="<?= $podcast->getLink('poddar'); ?>"><?= $podcast->name; ?></a></h3>

					<div class="btn-group">
						<button type="button" class="btn btn-default">Följ</button>
						<button type="button" class="btn btn-default">0</button>
						<button type="button" class="btn btn-default">0</button>
					</div>
				</div>
			</div>
		</div>

	<?php endfor; ?>
</div>

<div id="list_view" style="display:<?php echo ((isset($selected_view_type) && $selected_view_type === 'grid') || !isset($selected_view_type)) ? 'none' : 'block'; ?>">
    <div class="container">

		<?php foreach ( $podcasts as $podcast ): ?>
            <div class="media">
                <a class="pull-left" href="<?= $podcast->getLink('poddar'); ?>"<?= $podcast->name; ?>">
                    <img class="media-object" src="<?= $podcast->getImage('standard', false, true); ?>" width="50" height="" alt="...">
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><?= $podcast->name; ?></h4>
                </div>
            </div>
		<?php endforeach; ?>

	</div>
</div>
