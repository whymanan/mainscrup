
  var EmoApp = angular.module('EmoApp', ['ngRoute']);


  EmoApp.filter('renderHTMLCorrectly', function($sce) {
                return function(stringToParse) {
                    return $sce.trustAsHtml(stringToParse);
                }
            });

          
