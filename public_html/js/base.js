var $player,
	$jp_container_1,
	$footer,
	$toggle_footer_button;

var playing_url = null;

var current_episode_id = null,
	current_title = null,
	current_episode_link = null;

var on_load = true;

$(function()
{
	//
	if ( typeof bootbox_alert !== 'undefined' )
	{
		bootbox.alert(bootbox_alert);
	}

	$.cookie.json = true;

	$footer = $('#footer');
	$toggle_footer_button = $('#toogle-footer-button');

	init_player();
	init_raty();
	init_subscription();
});

window.onbeforeunload = function()
{
	var is_playing = false;

	$.cookie('player_open', is_player_open() ? 'yes' : 'no', { path: '/', domain: document.domain });

	if ( $player.data().jPlayer.status.src !== '' )
	{
		var player_data = $player.data();

		is_playing = (player_data.jPlayer.status.paused === false) ? 1 : 0;

		var cookie_object =
		{
			url: player_data.jPlayer.status.src,
			volume: player_data.jPlayer.options.volume,
			title: current_title,
			episode_id: current_episode_id,
			episode_link: current_episode_link,
			progress: player_data.jPlayer.status.currentTime,
			duration: player_data.jPlayer.status.duration,
			is_playing: is_playing,
			percent: (player_data.jPlayer.status.currentTime / player_data.jPlayer.status.duration) * 100
		};

		$.cookie('playing', cookie_object, { path: '/', domain: document.domain });
	}
	else
	{
		// Check if cookie exists
		var cookie = $.cookie('playing');

		if ( typeof cookie !== 'undefined' )
		{
			$.removeCookie('playing', { path: '/', domain: document.domain });
		}
	}

	if ( is_playing )
	{
		/*$.ajax(
		{
			url: BASE_URL + 'save-listen',
			type: 'POST',
			data:
			{
				episode_id: current_episode_id
			},
			async: false
		}).done(function()
		{
		});*/

		save_current_position(false);

		var e = e || window.event;

		var message = 'Any text will block the navigation and display a prompt';

		// For IE6-8 and Firefox prior to version 4
		if ( e )
		{
			e.returnValue = message;
		}

		// For Chrome, Safari, IE8+ and Opera 12+
		return message;
	}
}

function init_player()
{
	$player = $('#player');

	$jp_container_1 = $('#jp_container_1');

	$player.jPlayer(
	{
		swfPath: BASE_URL + 'libs/jplayer/',
		solution: 'html, flash',
		supplied: 'mp3',
		preload: 'metadata',
		volume: .8,
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

				current_title = playing_cookie_object.title;
				current_episode_id = playing_cookie_object.episode_id;
				current_episode_link = playing_cookie_object.episode_link;

				$('#player_title').html('<a href="' + current_episode_link + '">' + current_title + '</a>');

				if ( !player_open || player_open === '1' )
				{
					show_player();
				}

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
		durationchange: function(e)
		{
			if ( !on_load )
			{
				$.ajax(
				{
					url: BASE_URL + 'save-episode-duration',
					type: 'POST',
					data: { episode_id: current_episode_id, duration: $player.data().jPlayer.status.duration }
				}).done(function()
				{
				});
			}
			else
			{
				on_load = false;
			}
		},
		loadstart: function()
		{
			//console.log('loadstart');
		},
		canplay: function()
		{
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
		progress: function(e)
		{
			/*if ( e.jPlayer.status.seekPercent === 100 )
			{
				mark_as_done();
			}*/
		},
		pause: function(e)
		{
			save_current_position();

			refresh_player_controls();
		},
		ended: function()
		{
			mark_as_done();
		}
	});

	$('.play').on('click', function()
	{
		var $this = $(this),
			url = $this.data('url');

		current_episode_id = $this.data('episode_id'),
		current_episode_link = $this.data('episode_link');
		current_title = $this.data('title');

		if ( $this.hasClass('sm2_playing') ) // Pause
		{
			//$this.removeClass('sm2_playing');

			$player.jPlayer('pause');
		}
		else // Play
		{
			var start_position = $this.data('position');

			if ( !is_player_open() )
			{
				open_player();
			}

			//$this.addClass('sm2_playing');

			// Changed episode!
			if ( $player.data().jPlayer.status.src !== url )
			{
				// Spara position
				save_current_position();

				$('#player_title').html('<a href="' + current_episode_link + '">' + current_title + '</a>');

				$player.jPlayer('setMedia',
				{
					mp3: decodeURIComponent(url)
				});
			}

			show_player();

			$player.jPlayer('play', start_position);

			playing_url = url;

			// Save listen
			/*if ( user_id > 0 )
			{
				save_current_position(false);
			}*/
		}
	});

	$('#player_controls').find('.jp-stop').on('click', function()
	{
		$.ajax(
		{
			type: 'POST',
			url: BASE_URL + 'markera-som-fardig',
			data:
			{
				episode_id: current_episode_id,
				position: $player.data().jPlayer.status.currentTime
			}
		});

		current_episode_id = null;

		$player.jPlayer('clearMedia');

		close_player();

		refresh_player_controls();
	});

	$toggle_footer_button.on('click', function()
	{
		if ( is_player_open() )
		{
			close_player();
		}
		else
		{
			open_player();
		}
	});
}

function hide_player()
{
	$jp_container_1.hide();
}

function show_player()
{
	$jp_container_1.show();
}

function init_raty()
{
	var raty_img_dir = BASE_URL + 'libs/raty/img/';

	$('.raty').raty(
	{
		path: raty_img_dir,
		readOnly: function() { return $(this).attr('data-readOnly') },
		/*cancelOff: raty_img_dir + 'cancel-off.png',
		cancelOn: raty_img_dir + 'cancel-on.png',
		starHalf: raty_img_dir + 'star-half.png',
		starOff: raty_img_dir + 'star-off.png',
		starOn: raty_img_dir + 'star-on.png',*/
		score: function()
		{
			return $(this).attr('data-rating');
		},
		click: function(score)
		{
			var $target = $(this),
				episode_id = $target.data('id');

			$.post
			(
				BASE_URL + 'rate-episode',
				{
					episode_id: episode_id,
					score: score
				},
				function(result)
				{
					if ( result.error === 'NOT_LOGGED_IN' )
					{
						alert('Du måste logga in för att kunna rösta!');

						return;
					}

					/*$target.raty(
					{
						path: raty_img_dir,
						score: result.data.new_score
					})
					.data('rating', result.data.new_score);*/

					$target.raty('score', result.data.new_score).data('rating', result.data.new_score);

					// Disable clicking again
					//$target.find('img').unbind('click');

					if ( result.data.voted_before === 'yes' )
					{
						show_jgrowl('Tack för din röst!' + '<br>' + 'Din tidigare röst har ersatts.');
					}
					else
					{
						show_jgrowl('Tack för din röst!');
					}
				}
			);
		}
	});
}

function open_player()
{
	if ( current_episode_id !== null )
	{
		show_player();
	}

	$toggle_footer_button.html('<img src="' + BASE_URL + 'images/player/minimize-footer-no-border.png" width="30px" height="30px">');

	$footer.addClass('open');
}

function close_player()
{
	hide_player();

	$toggle_footer_button.html('<img src="' + BASE_URL + 'images/player/maximize-footer-no-border.png" width="30px" height="30px">');

	$footer.removeClass('open');
}

function is_player_open()
{
	return $footer.hasClass('open');
}

function show_jgrowl(message, sticky)
{
	$.jGrowl
	(
		message,
		{
			sticky: (sticky === true)
		}
	);
}

function refresh_player_controls()
{
	$('.play').each(function(index, element)
	{
		// Om den som spelar är den vi klickade på
		if ( $(element).data('url') === playing_url )
		{
			$(element).removeClass('sm2_playing'); // Visa pause-knapp

			return false; // break
		}
	});
}

function init_subscription()
{
	subscribe_podcast_bind();
	unsubscribe_podcast_bind();
}

function subscribe_podcast_bind($this)
{
	$('.subscribe').unbind('click').on('click', function()
	{
		var $this = $(this),
			id = $this.data('id'),
			unsubscribe_text = $this.data('unsubscribe_text'),
			subscribe_text = $this.html();

		console.log('unsubscribe_text: ' + unsubscribe_text);

		$.ajax(
		{
			type: 'POST',
			url: BASE_URL + 'subscribe-podcast',
			data:
			{
				podcast_id: id
			}
		}).done(function(result)
		{
			if ( result.error === '' )
			{
				$this.removeClass('subscribe').addClass('unsubscribe').removeAttr('data-unsubscribe_text').attr('data-subscribe_text', subscribe_text).html(unsubscribe_text);

				unsubscribe_podcast_bind();
			}
		});
	});
}

function unsubscribe_podcast_bind($this)
{
	$('.unsubscribe').unbind('click').on('click', function()
	{
		var $this = $(this),
			id = $this.data('id'),
			subscribe_text = $this.data('subscribe_text'),
			unsubscribe_text = $this.html();

		$.ajax(
		{
			type: 'POST',
			url: BASE_URL + 'unsubscribe-podcast',
			data:
			{
				podcast_id: id
			}
		}).done(function(result)
		{
			if ( result.error === '' )
			{
				$this.removeClass('unsubscribe').addClass('subscribe').removeAttr('data-subscribe_text').attr('data-unsubscribe_text', unsubscribe_text).html(subscribe_text);

				subscribe_podcast_bind();
			}
		});
	});
}

function save_current_position(async)
{
	if ( current_episode_id === null || user_id === 0 )
	{
		return;
	}

	async = async || true;

	$.ajax(
	{
		type: 'POST',
		url: BASE_URL + 'save-listen',
		data:
		{
			episode_id: current_episode_id,
			position: $player.data().jPlayer.status.currentTime
		},
		async: async
	});
}

function mark_as_done()
{
	if ( current_episode_id === null || user_id === 0 )
	{
		return;
	}

	$.ajax(
	{
		type: 'POST',
		url: BASE_URL + 'markera-som-fardig',
		data:
		{
			episode_id: current_episode_id,
			position: $player.data().jPlayer.status.currentTime
		}
	});
}