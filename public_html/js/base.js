var $player;

var playing_url = null;

$(function()
{
	$.cookie.json = true;

	$player = $('#player');

	$player.jPlayer(
	{
		swfPath: BASE_URL + 'libs/jplayer/',
		solution: 'html, flash',
		supplied: 'mp3',
		preload: 'metadata',
		volume: 0.8,
		muted: false,
		backgroundColor: '#000000',
		cssSelectorAncestor: '#jp_container_1',
		cssSelector:
		{
			videoPlay: '.jp-video-play',
			play: '.jp-play',
			pause: '.jp-pause',
			stop: '.jp-stop',
			seekBar: '.jp-seek-bar',
			playBar: '.jp-play-bar',
			mute: '.jp-mute',
			//unmute: '.jp-unmute',
			volumeBar: '.jp-volume-bar',
			volumeBarValue: '.jp-volume-bar-value',
			//volumeMax: '.jp-volume-max',
			playbackRateBar: '.jp-playback-rate-bar',
			playbackRateBarValue: '.jp-playback-rate-bar-value',
			currentTime: '.jp-current-time',
			duration: '.jp-duration',
			fullScreen: '.jp-full-screen',
			restoreScreen: '.jp-restore-screen',
			//repeat: '.jp-repeat',
			//repeatOff: '.jp-repeat-off',
			gui: '.jp-gui',
			noSolution: '.jp-no-solution'
		},
		errorAlerts: true,
		warningAlerts: false,
		ready: function()
		{
			if ( typeof playing_cookie_object !== 'undefined' )
			{
				$player.jPlayer('setMedia',
				{
					mp3: playing_cookie_object.url
				});

				show_player();

				if ( playing_cookie_object.is_playing )
				{
					$player.jPlayer('play', playing_cookie_object.progress);
				}
				else
				{
					$player.jPlayer('playHead', playing_cookie_object.percent);
				}
			}
		},
		loadstart: function()
		{
			console.log('loadstart');
		},
		play: function()
		{
			$('.play').each(function(index, element)
			{
				if ( $(element).data('url') === playing_url )
				{
					$(element).addClass('sm2_playing');
				}
				else
				{
					$(element).removeClass('sm2_playing');
				}
			});
		},
		progress: function()
		{
			console.log('progress');
		},
		pause: function(e)
		{
			$('.play').each(function(index, element)
			{
				if ( $(element).data('url') === playing_url )
				{
					$(element).removeClass('sm2_playing');
				}
				else
				{
					$(element).addClass('sm2_playing');
				}
			});
		}
	});

	$('.play').on('click', function()
	{
		var $this = $(this),
			url = $this.data('url');

		if ( $this.hasClass('sm2_playing') ) // Pause
		{
			//$this.removeClass('sm2_playing');

			$player.jPlayer('pause');
		}
		else // Play
		{
			//$this.addClass('sm2_playing');

			if ( $player.data().jPlayer.status.src !== url )
			{
				$player.jPlayer('setMedia',
				{
					mp3: url
				});
			}

			show_player();

			$player.jPlayer('play'/*, TIME */);

			playing_url = url;
		}
	});

	$('#player_controls').find('.jp-stop').on('click', function()
	{
		$player.jPlayer('clearMedia');

		hide_player();
	});
});

function hide_player()
{
	$('#jp_container_1').hide();
}

function show_player()
{
	$('#jp_container_1').show();
}

window.onbeforeunload = function()
{
	if ( $player.data().jPlayer.status.src !== '' )
	{
		var player_data = $player.data();

		var cookie_object =
		 {
			 url: player_data.jPlayer.status.src,
			 volume: player_data.jPlayer.options.volume,
			 title: '',
			 episode_link: '',
			 progress: player_data.jPlayer.status.currentTime,
			 duration: player_data.jPlayer.status.duration,
			 is_playing: (player_data.jPlayer.status.paused === false) ? 1 : 0,
			 percent: (player_data.jPlayer.status.currentTime / player_data.jPlayer.status.duration) * 100
		 };

		 $.cookie('playing', cookie_object, { path: '/' });
	}
	else
	{
		// Check if cookie exists
		var cookie = $.cookie('playing');

		if ( typeof cookie !== 'undefined' )
		{
			$.removeCookie('playing', { path: '/' });
		}
	}
}

/*var sound;

var $player, $player_details, $player_time, $player_progress_bar_container, $player_progress_bar;

var sound_manager;

var is_playing = false;

var current_id, current_title, current_episode_id, current_episode_link;

var used_ids = [];

var loaded_from_cookie = false;

var forced_set_duration = false;

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
		url: BASE_URL + 'libs/soundmanager2/',
		flashVersion: 9, // optional: shiny features (default = 8)
		// optional: ignore Flash where possible, use 100% HTML5 mode
		preferFlash: false,
		debugMode: false,
		onready: function()
		{
			//var playing_cookie_object = $.cookie('playing');

			// Load from cookie?
			if ( typeof playing_cookie_object !== 'undefined' )
			{
				//playing_cookie_object = JSON.parse(playing_cookie_object);

				current_id = playing_cookie_object.id;
				current_title = playing_cookie_object.title;
				current_episode_link = playing_cookie_object.episode_link;

				sound = createSound(playing_cookie_object.id, playing_cookie_object.url, playing_cookie_object.volume, playing_cookie_object.progress, playing_cookie_object.is_playing);

				if ( !playing_cookie_object.is_playing )
				{
					sound.position = playing_cookie_object.progress;
				}

				//update_player_position(playing_cookie_object.progress, playing_cookie_object.duration, true);

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
						//$.post(BASE_URL + 'save-listen', { episode_id: current_episode_id }, function(result)
						//{
						//	console.log(result);
						//});
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

				var duration = 0;

				if ( sound.duration === 0 && loaded_from_cookie )
				{
					duration = playing_cookie_object.duration;
				}
				else
				{
					duration = sound.duration;
				}

				var ratio = player_width / sound.duration;
				//var click_ratio = clicked_x / sound.duration;

				var percent = clicked_x / ratio;
				//var click_pos = percent / sound.duration;

				sound.setPosition(percent);

				update_player_position(percent, duration, true);
			});
		}
	});

	//console.log('----------------');
	//console.log(sound_manager);
	//console.log('----------------');

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
}

$(window).on('unload', function()
{
	if ( sound )
	{
		console.log('had sound on unload');

		var cookie_object =
		{
			id: sound.id,
			url: sound.url,
			volume: sound.volume,
			title: current_title,
			episode_link: current_episode_link,
			progress: sound.position,
			duration: sound.duration,
			is_playing: is_playing
		};

		$.cookie('playing', cookie_object, { path: '/' });
	}
	else
	{
		console.log('no sound on unload');

		// Check if cookie exists
		//var cookie = $.cookie('playing');

		//if ( typeof cookie !== 'undefined' )
		//{
		//	$.removeCookie('playing', { path: '/' });
		//}
	}
});

function createSound(player_id, url, volume, progress, play)
{
	var $current_player = $('#' + player_id);

	$player_details.html('<a href="' + current_episode_link + '">' + current_title + '</a>');

	$player_progress_bar_container.show();

	if ( loaded_from_cookie )
	{
		$player.show();
	}

	return sound_manager.createSound(
	{
		id: player_id,
		url: BASE_URL + 'play?url=' + url,
		//url: url,
		volume: volume,
		autoLoad: true,
		autoPlay: play,
		position: progress,
		onconnect: function()
		{
			if ( !play )
			{
				if ( loaded_from_cookie )
				{
					update_player_position(progress, this.duration);
				}
			}
		},
		onload: function() // Denna funkar, när den är helt färdigbuffrad
		{
			update_player_position(progress, this.duration, true);

			//if ( play )
			//{
			//	$player_time.html('Loading...');
			//}
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
			//if ( this.loaded )
			//{
				update_player_position(this.position, this.duration, true);
			//}

			$player_progress_bar.css('background-color', '#AAA');
		},
		whileloading: function()
		{
			$player_progress_bar.css('background-color', 'yellow');
		}
	});
}

function update_player_position(position, duration, update_progress_bar)
{
	console.log(position + ' / ' + duration);

	var _current = new Date(position),
		_duration = new Date(duration),
		current_minutes = _current.getMinutes(),
		current_seconds = _current.getSeconds(),
		duration_minutes = _duration.getMinutes(),
		duration_seconds = _duration.getSeconds(),
		current_str = (current_minutes < 10 ? '0' : '') + current_minutes + ':' + (current_seconds < 10 ? '0' : '') + current_seconds,
		duration_str = (duration_minutes < 10 ? '0' : '') + duration_minutes + ':' + (duration_seconds < 10 ? '0' : '') + duration_seconds;

	$player_time.html(current_str + ' / ' + duration_str);

	if ( update_progress_bar )
	{
		$player_progress_bar.css('width', ((position / duration) * 100) + '%');
	}
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
}*/