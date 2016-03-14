'use strict';

/**
 * @ngdoc function
 * @name gallaryApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the gallaryApp
 */
angular.module('galleryApp')
		.controller('MainCtrl', ['$location', function ($location) {
			var self = this;
			self.isActive = function(view){
				return view === $location.path();
			};
		}]);
