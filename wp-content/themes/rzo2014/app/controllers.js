rzo.controller('mainController', function($scope, ngDialog, audioPlayer, $rootScope)
{
	$scope.temp= 'jipefjaifje';
	/*
	ngDialog.open({
	    template: '<p>my template</p>',
	    plain: true
	});
	*/

	 $rootScope.$on('audioplayer:load', function (event, autoplayNext) {
	    // Tell someone a song is gonna get loaded.
	  });
});