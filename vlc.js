var vlc = null;
var button_play_pause = null;
var button_fullscreen = null;

window.onload = function () {
	controls_events ();
}

// ========== Controls ========== //

function controls_events () {
	vlc = document.getElementById("vlc-embed");
	button_play_pause = document.getElementById("button-play-pause");
	button_fullscreen = document.getElementById("button-fullscreen");

	button_play_pause.onclick = function () {
		toggle_play_pause ();
	}

	button_fullscreen.onclick = function () {
		toggle_fullscreen ();
	}

	document.onkeyup = function () {
		var key_id = event.which;
		if (key_id == null)
			key_id = event.keyCode;

		// Toggle fullscreen on key press
		if (key_id == 27 && fullscreen == true) { // Esc
			toggle_fullscreen ();
		}
		else if (key_id == 70) { // f
			toggle_fullscreen ();
		}

		// Toggle play/pause
		else if (key_id == 80) { // p
			toggle_play_pause ();
		}
	}
}

function toggle_play_pause () {
	if (vlc.playlist.isPlaying) {
		vlc.playlist.togglePause ();
		//button_play_pause.class = "play";
	}
	else {
		vlc.playlist.play ();
		//button_play_pause.class = "pause";
	}
}

var fullscreen = false;
function toggle_fullscreen () {
	//vlc.video.fullscreen = true; // Doesn't work

	// NOTE: when changing the style of the "embed" tag, the VLC plugin
	// stops playing the video and seek is reset to the start.

	var current_time = vlc.input.time;

	if (fullscreen) {
		fullscreen = false;
		vlc.style.position = "static";
		vlc.style.width = "848px";
		vlc.style.height = "540px";
	}
	else {
		fullscreen = true;
		vlc.style.position = "absolute";
		vlc.style.top = "0px";
		vlc.style.left = "0px";
		vlc.style.width = "100%";
		vlc.style.height = "100%";
	}

	setTimeout ("vlc.playlist.play (); vlc.input.time = " + current_time + ";", 1000);
}

