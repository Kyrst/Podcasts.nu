var $player,
	$jp_container_1;

var playing_url = null;

var current_title = '';

$(function()
{
	if ( typeof bootbox_alert !== 'undefined' )
	{
		bootbox.alert(bootbox_alert);
	}

	$.cookie.json = true;

	$player = $('#player');

	$jp_container_1 = $('#jp_container_1');

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

				current_title = playing_cookie_object.title;
				$('#player_title').html(current_title);

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
			//console.log('loadstart');
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
			//console.log('progress');
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

		current_title = $this.data('title');

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
				$('#player_title').html(current_title);

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
	$jp_container_1.hide();
}

function show_player()
{
	$jp_container_1.show();
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
			 title: current_title,
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