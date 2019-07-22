function initMap() {
	// Styles a map in night mode.

	var location = new google.maps.LatLng(41.2994958,69.2400734);

	var map = new google.maps.Map(document.getElementById('map'), {
		center: location,
		zoom: 14,
	});

	var marker = new google.maps.Marker({
		position: location,
		map: map,
		animation: google.maps.Animation.DROP,
	});


	function toggleBounce () {
		if (marker.getAnimation() != null) {
			marker.setAnimation(null);
		} else {
			marker.setAnimation(google.maps.Animation.BOUNCE);
		}
	}

	// Add click listener to toggle bounce

	google.maps.event.addListenerOnce(map, 'idle', function(){
		toggleBounce();
		setTimeout(toggleBounce, 1500);
	});
}