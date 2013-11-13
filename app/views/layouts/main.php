<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?= $page_title; ?></title>
		<meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

		<?php foreach ( $assets['css'] as $css ): ?>
			<link href="<?= URL::route('home', array(), false) . $css['file']; ?>" rel="stylesheet">
		<?php endforeach; ?>
	</head>
	<body>
		<!-- Header -->
		<nav id="header" class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<a id="logo" class="navbar-brand" href="<?= URL::route('home', array(), false); ?>"></a>
                    <!-- Orginal utförande
                    <div id="player_container">
						<div class="player-row clearfix">
							<a id="player" class="sm2_button"></a>

							<!-- Player Status -->
                            <!--<span id="player_details"></span>
                        </div>

                        <div class="player-row clearfix last">
                            <!-- Player Progress Bar -->
                            <!--
                            <a id="player" class="sm2_button"></a>
                        <div id="player_progress_bar_container">
                            <span id="player_progress_bar"></span>
                        </div>

                        <span id="player_time"></span>
                        </div>
                    </div>
                    -->
				</div>
				<div id="header_menu" class="collapse navbar-collapse navbar-ex1-collapse">
					<ul class="nav navbar-nav">
						<li<?php if ($current_route === '/poddar'): ?> class="selected"<?php endif; ?>><a href="<?= URL::route('poddar', array(), false); ?>">Poddar</a></li>
						<li<?php if ($current_route === '/avsnitt'): ?> class="selected"<?php endif; ?>><a href="<?= URL::route('avsnitt', array(), false); ?>">Avsnitt</a></li>
						<li<?php if ($current_route === '/topplista'): ?> class="selected"<?php endif; ?>><a href="<?= URL::route('topplista', array(), false); ?>">Topplista</a></li>
						<li<?php if ($current_route === '/bloggar'): ?> class="selected"<?php endif; ?>><a href="<?= URL::route('bloggar', array(), false); ?>">Blogg</a></li>
						<li<?php if ($current_route === '/poddsnacks'): ?> class="selected"<?php endif; ?>><a href="<?= URL::route('poddsnacks', array(), false); ?>">Poddsnack</a></li>

                        <li class="dropdown">
                            <?php if ( $user !== NULL ): ?>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $user->username; ?><b class="caret"></b></a>
								<ul class="dropdown-menu">

									<li><a href="<?= URL::route('min-sida', array(), false); ?>">Min sida</a></li>
									<li class="divider"></li>
									<li><a href="<?= URL::route('admin/nyheter', array(), false); ?>">Nyheter</a></li>
									<li><a href="<?= URL::route('admin/blogg', array(), false); ?>">Blogg</a></li>
									<li><a href="<?= URL::route('admin/poddar', array(), false); ?>">Poddar</a></li>
									<li><a href="<?= URL::route('admin/episodes', array(), false); ?>">Avsnitt</a></li>
									<li><a href="<?= URL::route('admin/anvandare', array(), false); ?>">Användare</a></li>
									<li class="divider"></li>
									<li><a href="<?= URL::route('logga-ut', array(), false); ?>">Logga ut</a></li>
								</ul>
                            <?php endif; ?>
                        </li>
					</ul>
				</div>

				<div id="user_menu">
					<?php if ( $user == NULL ): ?>
						<ul>
							<li><a href="<?= URL::route('logga-in', array(), false); ?>">LOGGA IN</a></li>
							<li><a href="<?= URL::route('bli-medlem', array(), false); ?>">BLI MEDLEM</a></li>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</nav>
        <div class="container">
            <div class="bi-header col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div id="player_container">
                    <div class="player-row clearfix last">
                        <a id="player" class="sm2_button"></a>
                        <span id="player_details"></span>

                        <div id="player_progress_bar_container">
                            <span id="player_progress_bar"></span>
                        </div>
                        <span id="player_time"></span>
                    </div>
                </div>
            </div>
            <div class="bi-header col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <form class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>

            </div>
        </div>
		<div class="clear"></div>

		<!-- Content -->
		<div class="container">
			<?php if ( in_array($current_route, array('/min-sida', '/admin/blogg', '/admin/nyheter', '/admin/nyhet', '/admin/nyhet/{id}', '/admin/anvandare')) ): ?>
				<ul class="nav nav-tabs">
					<li<?php if ($current_route === '/min-sida'): ?> class="active"<?php endif; ?>><a href="<?= URL::route('min-sida', array(), false); ?>">Min sida</a></li>

					<?php if ( $user->is('Admin') ): ?>
						<li<?php if ($current_route === '/admin/nyheter' || $current_route === '/admin/nyhet' || $current_route === '/admin/nyhet/{id}'): ?> class="active"<?php endif; ?>><a href="<?= URL::route('admin/nyheter', array(), false); ?>">Nyheter</a></li>
						<li<?php if ($current_route === '/admin/blogg'): ?> class="active"<?php endif; ?>><a href="<?= URL::route('admin/blogg', array(), false); ?>">Blogg</a></li>
						<li<?php if ($current_route === '/admin/anvandare'): ?> class="active"<?php endif; ?>><a href="<?= URL::route('admin/anvandare', array(), false); ?>">Användare</a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>

			<?= $content; ?>
		</div>

		<?php if ( isset($bootbox_alert) || (count($js_vars) > 0) ): ?>
			<script>
				<?php if ( isset($bootbox_alert) ): ?>
					var bootbox_alert = '<?php echo $bootbox_alert; ?>';
				<?php endif; ?>

				<?php foreach ( $js_vars as $key => $value ): ?>
					var <?= $key; ?> = '<?= $value; ?>';
				<?php endforeach; ?>
			</script>
		<?php endif; ?>

		<?= $jquery_script; ?>

		<?php foreach ( $assets['js'] as $file ): ?>
			<script src="<?= URL::route('home', array(), false) . $file; ?>"></script>
		<?php endforeach; ?>
	</body>
</html>