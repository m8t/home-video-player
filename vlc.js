var vlc = null;

// ========== Controls ========== //
var button_play_pause = null;
var button_fullscreen = null;

function init_player_controls () {
	controls_events ();
	sync_slider (false);
}

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

function sync_slider () {
	if (slider != null && handle_follow_mouse == false) {
		set_handle_position (vlc.input.position);
	}
	var video_length = document.getElementById ("video-length");
	if (video_length != null) {
		var length = parseInt (vlc.input.length / 1000);
		var total_min = parseInt (length / 60);
		var total_sec = length - (total_min * 60);
		if (total_sec < 10)
			total_sec = "0" + total_sec;
		var current_time = parseInt (vlc.input.time / 1000);
		var current_min = parseInt (current_time / 60);
		var current_sec = current_time - (current_min * 60);
		if (current_sec < 10)
			current_sec = "0" + current_sec;
		video_length.innerHTML = current_min + ":" + current_sec + " / " + total_min + ":" + total_sec;
	}
	setTimeout ("sync_slider (true);", 1000);
}

/* ========== Slider ========== */
var handle_follow_mouse = false;
var position = 0;
var slider = null;
var handle = null;

function init_slider (slider_id, width, height) {
	var childrens = document.getElementById (slider_id).getElementsByTagName ("div");
	slider = childrens[0];
	handle = childrens[1];

	slider.style.width = width;
	slider.style.height = height;
	handle_size = parseInt (height) - 2;
	handle.style.width = handle_size + "px";
	handle.style.height = handle_size + "px";

	slider.onmousedown = function () {
		if (event.button == 0)
			handle_follow_mouse = true;
		return false;
	}
	window.onmouseup = function () {
		handle_follow_mouse = false;
		if (vlc != null) {
			var stream_time = parseInt(vlc.input.length * position);
			vlc.input.time = stream_time;
		}
		return false;
	}
	window.onmousemove = move_handle;
}

function get_mouse_position () {
	var posx = 0;
	var posy = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) 	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY) 	{
		posx = e.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = e.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}
	return [posx, posy];
}

function move_handle () {
	if (handle_follow_mouse == false)
		return;

	var mouse_position = get_mouse_position ();

	var padding_left = parseInt (document.getElementById ("video-player").offsetLeft);
	position = (mouse_position[0] - slider.offsetLeft - padding_left) / parseInt (slider.style.width);
	if (position < 0)
		position = 0;
	else if (position > 1)
		position = 1;

	set_handle_position (position);
}

function set_handle_position (position) {
	var slider_width = parseInt (slider.style.width);
	var handle_width = parseInt (handle.style.width);
	var handle_position = parseInt (position * (slider_width - handle_width));
	if (handle_position < 1)
		handle_position += 1;
	else if (handle_position > (slider_width - handle_width - 1))
		handle_position -= 1;
	handle.style.left = handle_position + "px";
}

