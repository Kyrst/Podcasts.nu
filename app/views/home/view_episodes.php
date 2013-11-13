<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a></li>
	<?php if ( $podcast ): ?><li><?= $podcast->name; ?></li><?php endif; ?>
</ul>

<h1>Avsnitt</h1>

<?php if ( $num_episodes > 0 ): ?>
	<div id="episodes">
		<?php foreach ( $episodes as $episode ): ?>
			<!--<div class="episode">
				<h2><a href="<?= $episode->podcast->getLink('avsnitt') ?>"><?= $episode->podcast->name ?></a> - <a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a></h2>

				<img src="<?= $episode->podcast->getImage('standard', false, true); ?>" width="250" height="" alt="...">

				<p><?= $episode->created_at; ?></p>

				<div class="btn-group">
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
						Admin <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?= URL::to('admin/avsnitt/' . $episode->id) ?>">Ändra</a></li>
						<li><a href="<?= URL::to('admin/avsnitt/ta-bort/' . $episode->id) ?>">Ta bort</a></li>
					</ul>
				</div>
			</div>-->
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="<?= $episode->podcast->getImage('standard', false, true); ?>" width="60" height="" alt="...">
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><a href="<?= $episode->podcast->getLink('avsnitt') ?>"><?= $episode->podcast->name ?></a> - <a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a> </h4>
                    <p><?= $episode->created_at; ?></p>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    Admin <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?= URL::to('admin/avsnitt/' . $episode->id) ?>">Ändra</a></li>
                    <li><a href="<?= URL::to('admin/avsnitt/ta-bort/' . $episode->id) ?>">Ta bort</a></li>
                </ul>
            </div>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<p>Inga avsnitt.</p>
<?php endif; ?>