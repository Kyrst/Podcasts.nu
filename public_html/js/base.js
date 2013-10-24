var sound;

var $player, $player_details, $player_time, $player_progress_bar_container, $player_progress_bar;

var sound_manager;

var is_playing = false;

var current_id, current_title, current_episode_id, current_episode_link;

var used_ids = [];

$(function()
{
	$.cookie.json = true;

	if (DEBUG)
	{
		console.log('########## Podcasts.nu ##########');
	}

	if ( typeof bootbox_alert !== 'undefined' )
	{
		showAlert(bootbox_alert);
	}

	// Audio player
	sound_manager = soundManager.setup(
	{
		url: '/libs/soundmanager2/',
		flashVersion: 9, // optional: shiny features (default = 8)
		// optional: ignore Flash where possible, use 100% HTML5 mode
		//preferFlash: false,
		debugMode: false,
		onready: function()
		{
			var playing_cookie_object = $.cookie('playing'),
				loaded_from_cookie = false;

			// Load from cookie?
			if ( playing_cookie_object )
			{
				current_id = playing_cookie_object.id;
				current_title = playing_cookie_object.title;
				current_episode_link = playing_cookie_object.episode_link;

				sound = createSound(playing_cookie_object.id, playing_cookie_object.url, playing_cookie_object.volume, playing_cookie_object.progress, playing_cookie_object.is_playing);

				if ( !playing_cookie_object.is_playing )
				{
					sound.position = playing_cookie_object.progress;
				}

				loaded_from_cookie = true;
			}

			// Play from click
			$('.player').on('click', function()
			{
				var $this = $(this),
					url = $this.attr('data-url');

				var force = false;

				// If wants to play another episode
				if ( current_id && current_id !== $this.attr('data-id') )
				{
					// New episode
					sound.stop();

					is_playing = false;
					force = true;
				}

				var previous_id = current_id;

				current_id = $this.attr('data-id'),
				current_title = $this.attr('data-title');
				current_episode_id = $this.attr('data-episode_id');
				current_episode_link = $this.attr('data-episode_link');

				if ( is_playing )
				{
					pause();
				}
				else
				{
					// Totally new sound...
					if ( sound === undefined || force )
					{
						console.log('create new sound with: ' + current_id + ' / ' + url);

						if ( sound_id_exists(current_id) )
						{
							sound_manager.stop(previous_id);
							sound_manager.play(current_id);
						}
						else
						{
							sound = createSound(current_id, url, 50, 0, true);
						}

						used_ids.push(current_id);

						// Save liten
						$.post('/save-listen', { episode_id: current_episode_id }, function(result)
						{
							console.log(result);
						});
					}
					else
					{
						if ( loaded_from_cookie )
						{
							play();
						}
						else
						{
							resume();
						}
					}
				}
			});

			$player.on('click', function()
			{
				if ( is_playing )
				{
					pause();
				}
				else
				{
					if ( loaded_from_cookie )
					{
						play();
					}
					else
					{
						resume();
					}
				}
			});

			$player_progress_bar_container.on('click', function(e)
			{
				if ( !sound )
				{
					return;
				}

				var $this = $(this),
					player_width = $this.width(),
					parent_offset = $this.offset(),
					clicked_x = e.pageX - parent_offset.left;
					//clicked_y = e.pageY - parent_offset.top;

				var ratio = player_width / sound.duration;
				//var click_ratio = clicked_x / sound.duration;

				var percent = clicked_x / ratio;
				//var click_pos = percent / sound.duration;

				console.log(percent);

				sound.setPosition(percent);

				update_player_position(percent, sound.duration);
			});
		}
	});

	/*console.log('----------------');
	console.log(sound_manager);
	console.log('----------------');*/

	$player = $('#player');
	$player_details = $('#player_details');
	$player_time = $('#player_time');
	$player_progress_bar_container = $('#player_progress_bar_container');
	$player_progress_bar = $('#player_progress_bar');
});

function play()
{
	sound.play();
}

function resume()
{
	sound.resume();
}

function pause()
{
	sound.pause();
}

function showAlert(message)
{
	bootbox.alert(message);

	/*if (typeof title !== 'undefined')
	 {
	 bootbox.alert(message);
	 }
	 else
	 {
	 bootbox.alert(message);
	 }*/
}

$(window).on('unload', function()
{
	if ( sound )
	{
		var cookie_object =
		{
			id: sound.id,
			url: sound.url,
			volume: sound.volume,
			title: current_title,
			episode_link: current_episode_link,
			progress: sound.position,
			is_playing: is_playing
		};

		$.cookie('playing', cookie_object, { path: '/' });
	}
	else
	{
		$.removeCookie('playing', { path: '/' });
	}
});

function createSound(player_id, url, volume, progress, play)
{
	var $current_player = $('#' + player_id);

	$player_details.html('<a href="' + current_episode_link + '">' + current_title + '</a>');

	$player_progress_bar_container.show();

	if ( !play )
	{
		$player_time.html('Loading...');
		$player.show();
	}

	return sound_manager.createSound(
	{
		id: player_id,
		url: url,
		volume: volume,
		autoLoad: true,
		autoPlay: play,
		position: progress,
		onload: function()
		{
			update_player_position(progress, this.duration);

			if ( play )
			{
				$player_time.html('Loading...');
			}
		},
		onfinish: function()
		{
			is_playing = false;
		},
		onplay: function()
		{
			is_playing = true;

			// Update player
			$current_player.addClass('sm2_playing');

			// Show top player
			$player.removeClass('sm2_paused').addClass('sm2_playing').show();

			$player_time.html('Laddar...');
		},
		onstop: function()
		{
			is_playing = false;
		},
		onpause: function()
		{
			is_playing = false;

			$current_player.removeClass('sm2_playing').addClass('sm2_paused');

			$player.removeClass('sm2_playing');
			$player.addClass('sm2_paused');
		},
		onresume: function()
		{
			is_playing = true;

			// Update player
			$current_player.addClass('sm2_playing');

			// Update top player
			$player.removeClass('sm2_paused').addClass('sm2_playing');
		},
		whileplaying: function()
		{
			update_player_position(this.position, this.duration);
		}
	});
}

function update_player_position(position, duration)
{
	var _current = new Date(position),
		_duration = new Date(duration),
		current_minutes = _current.getMinutes(),
		current_seconds = _current.getSeconds(),
		duration_minutes = _duration.getMinutes(),
		duration_seconds = _duration.getSeconds(),
		current_str = (current_minutes < 10 ? '0' : '') + current_minutes + ':' + (current_seconds < 10 ? '0' : '') + current_seconds,
		duration_str = (duration_minutes < 10 ? '0' : '') + duration_minutes + ':' + (duration_seconds < 10 ? '0' : '') + duration_seconds;

	$player_time.html(current_str + ' / ' + duration_str);

	$player_progress_bar.css('width', ((position / duration) * 100) + '%');
}

function sound_id_exists(id)
{
	for ( var i = 0, num_used_ids = used_ids.length; i < num_used_ids; i++ )
	{
		if ( used_ids[i] === id )
		{
			return true;
		}
	}

	return false;
}