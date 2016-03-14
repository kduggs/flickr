'use strict';

/**
 * @ngdoc function
 * @name gallaryApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the gallaryApp
 */
angular.module('galleryApp')
	.controller('GalleryCtrl', ['FlickrService', function (FlickrService) {
		var self = this;
		self.page = 1;
		self.pages = null;
		self.photos = [];
		self.tag = null;
		self.loading = false;
		
		/*
		 * Load page with search
		 */
		self.loadPage = function ()
		{
			
			if ( self.loading ) { 
				//controller already loading a page
				return;
			}
			
			self.loading = true;
			
			var flickr = new FlickrService();
			
			if ( self.tag ) {
				flickr.tag = self.tag;
			}

			flickr.loadPage(self.page).then(function (response) {

				var photos = response.data.gallery.photos.photo;

				for (var i = 0; i < photos.length; i++) {
					self.photos.push(photos[i]);
				}

				self.pages = response.data.gallery.photos.pages;
				self.page += 1;
				
				self.loading = false;
			});
		};

		/*
		 * Search on a tag
		 */
		self.searchtag = function (tag) {
			self.tag = tag;
			self.photos = [];
			self.page = 1;
			self.loadPage();
		};
		
		/*
		 * Clear tag search
		 */
		self.cleartag = function (tag) {
			self.tag = null;
			self.photos = [];
			self.page = 1;
			self.loadPage();
		};
		
	}]);
