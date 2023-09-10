myApp.run(function($rootScope, $location,Session, $timeout) {
  



  $rootScope.$on('$locationChangeStart', function (event, current, previous) {
    $rootScope.locationPath = $location.path();
    $rootScope.$broadcast("locationPath", $location.path());
    $rootScope.previousPage = previous;

  });

  $rootScope.login=false;
	$rootScope.logout=true;
  // renderWatcher.init(
  //   function() {
  //     console.log('LOADING');
  //   },
  //   function() { 
  //     console.log('READY');
  //   }
  // );

    /*$rootScope.$on('$stateChangeStart', function(evt, toState, toParams, fromState, fromParams) {
      console.log("$stateChangeStart " + fromState.name + JSON.stringify(fromParams) + " -> " + toState.name + JSON.stringify(toParams));
    });
    $rootScope.$on('$stateChangeSuccess', function() {
      console.log("$stateChangeSuccess " + fromState.name + JSON.stringify(fromParams) + " -> " + toState.name + JSON.stringify(toParams));
    });
    $rootScope.$on('$stateChangeError', function() {
      console.log("$stateChangeError " + fromState.name + JSON.stringify(fromParams) + " -> " + toState.name + JSON.stringify(toParams));
    });*/
	
	
  });

// .config(function($httpProvider) {
//   $httpProvider.defaults.headers.post['Content-Type'] = 'application/json';
// });

// myApp.service('renderWatcher', function($rootScope) {
//   var stack = [];
//   var enabled = false;
//   this.init = function(startCallback, readyCallback) {
//     $rootScope.$on('$viewContentLoading', function(event, view) {
//       if (enabled) {
//         stack.push(event.targetScope.$id);
//       }
//     });
//     $rootScope.$on('$viewContentLoaded', function(event, view) {
//       console.log(enabled);
//       if (enabled) {
//         stack.pop(event.targetScope.$id);
//         if (!stack.length) {
//           if (readyCallback) {
//             readyCallback();
//           }
//         }
//       }
//     });  
//     $rootScope.$on('$stateChangeStart', function() {
//       enabled = false;
//       if (startCallback) {
//         startCallback();
//       }
//     });
//     $rootScope.$on('$stateChangeSuccess', function() {
//       enabled = true;
//     });
//   }
// })