'use strict';

/**
 * @ngdoc overview
 * @name gallaryApp
 * @description
 * # gallaryApp
 *
 * Main module of the application.
 */
angular
  .module('galleryApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
	'infinite-scroll',
	'ui.bootstrap'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html'
      })
	  .when('/contact', {
        templateUrl: 'views/contact.html'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
