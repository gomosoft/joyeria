$(document).on('ready',function(){
	$("#button-enter").on('click',function(){
		$('.intro,.container,nav').addClass('anim');
	});
	$('#sound-box').on('click',function() {
		$(this).toggleClass('select');
		var m = document.getElementById('music');
		if(m.paused)
			m.play();
		else
			m.pause();
	});
});

var app = angular.module('app',['ngRoute','ngResource','ngAnimate']);
app.factory("AppFactory",function($resource){
	a = {
		loading: function($scope){
			$scope.isViewLoading = false;
			$scope.$on('$routeChangeStart', function() {
			  $scope.isViewLoading = true;
			});
			$scope.$on('$routeChangeSuccess', function() {
			  $scope.isViewLoading = false;
			});
		}
	}
	return{
		a: a
	};
});
app.config(function($routeProvider) {
	$routeProvider
	.when('/Inicio', {
		templateUrl: 'views/inicio.html',
		controller: 'appController'
	})
	.when('/Productos', {
		templateUrl: 'views/productos.html',
		controller: 'appController'
	})
	.when('/Servicios', {
		templateUrl: 'views/servicios.html',
		controller: 'appController'
	})
	.when('/Empresa',{
		templateUrl: 'views/empresa.html',
		controller: 'appController'
	})
	.when('/Contacto',{
		templateUrl: 'views/contacto.html',
		controller: 'appController'
	})
	
});

function appController ($scope, $http, AppFactory) {
	AppFactory.a.loading($scope);
	
}