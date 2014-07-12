var carousel_ = function (app){


   app.controller('carouselCtrl',function($scope, $http, dataFact){

   	$scope.categorys = [];
   	$scope.products = [];


      $scope.getCategorys = function(){

      		 $http.get('app/api/?cats').success(function(rs){

      		 	     console.log(rs);

                  $scope.categorys = rs.data;


              }); 

      }

      $scope.getProducts = function(category){

      	  dataFact.products(function(rs){

      	  	  $scope.products = rs.data;

      	  }, category || null);

      }


      $scope.getProduct = function(id){

         dataFact.products(function(rs){

               $scope.product = rs.data;

           }, id);

      }

   });



   app.factory('dataFact', function($http){


       this.categorys = function(callback, error){

           $http.get('app/api/?cats')
           .success(callback)
           .error(error || function(err){console.log(error);});

       }

       this.products = function(callback, category, error){

           var category = category ? '&id='+category : '';

       	   $http.get('app/api/?cats' + category + '&prods')
           .success(callback)
           .error(error || function(err){console.log(error);});


       }


        this.product = function(callback, id){


           $http.get('app/api/?products&id='+ id)
           .success(callback)
           .error(error || function(err){console.log(error);});


       }


       return this;

   });

   return app;
	
};