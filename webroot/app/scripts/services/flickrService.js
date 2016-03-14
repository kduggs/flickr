'use strict';

var app = angular.module('galleryApp');

app.factory('FlickrService',['$http',function($http){
	var FlickrService = function() {
		this.photos = [];
		this.page = 1;
		this.pages = null;
		this.tag = null;
	};
	
	FlickrService.prototype.loadPage = function(page) {
		this.page = page;

		var url = "/gallery.json?page" + this.page;
		
		if ( this.tag ) {
			url += '&tag=' + this.tag;
		}
		
		return $http.get(url);
	};
	
	return FlickrService;
}]);
