jQuery(document).ready(function($){
	$("div#banner").nivoSlider({
		effect: "random",
		slices: 15,
		animSpeed: 400,
		pauseTime: 4000,
		startSlide: 0,
		directionNav: true,
		directionNavHide: true,
		controlNav: false,
		controlNavThumbs: false,
		controlNavThumbsFromRel: false,
		controlNavThumbsSearch: '.jpg',
		controlNavThumbsReplace: '_thumb.jpg',
		keyboardNav: true,
		pauseOnHover: true,
		manualAdvance: false,
		captionOpacity:0.75,
		beforeChange: function(){},
		afterChange: function(){},
		slideshowEnd: function(){},
		lastSlide: function(){},
		afterLoad: function(){}
	});
});