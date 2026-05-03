document.addEventListener('DOMContentLoaded', function () {
	if (!document.getElementById('body-login')) return;

	var video = document.createElement('video');
	video.id = 'escardar-bg-video';
	video.autoplay = true;
	video.loop = true;
	video.muted = true;
	video.playsInline = true;
	video.setAttribute('playsinline', '');

	var source = document.createElement('source');
	source.src = '/apps/escardar_branding/img/video1.mp4';
	source.type = 'video/mp4';
	video.appendChild(source);

	document.body.insertBefore(video, document.body.firstChild);
});
