myApp.component('app', {
    template: `
        <div ng-if="preLoader === true && (locationPath != 'login' && locationPath != 'signup' && locationPath != 'search-beauty' && locationPath != 'login' && locationPath != 'signup' && locationPath != 'search-health' && locationPath != 'forgotPassword' && locationPath != 'resetPassword' && locationPath != 'profile' && locationPath != 'editprofile' && locationPath != 'dashboard' && locationPath != 'profile-completion' && locationPath != 'business-profile' && locationPath != 'business-hours' && locationPath != 'business-rules')" class="overlay1" id="loadingScreen" style="background:#ffff !important;">
            <p><img  style="width:10% ;" src="assets/images/logo/logo.png"></p>
            <div class="loading-img" style="background: url('assets/images/Velocity2.gif') center center no-repeat;"></div>
        </div>
        <header></header>
        <ui-view></ui-view>
        <footer></footer>`,
    controller: function(enumfactory, $scope, $rootScope, $location){
        $scope.locationPath = $rootScope.locationPath.split('/')[1];
        
        //$scope.locationPath = $rootScope.locationPath;

        $scope.$on("locationPath", function (evt, data) {
            $scope.locationPath = data.split('/')[1];
        });
        $scope.preLoader = false;

        $scope.$on("$locationChangeStart", function(event, next, current) { 
            $scope.preLoader = true;
        });

        $scope.$on("$viewContentLoading", function(event, next, current) { 
            $scope.preLoader = true;
        });

        $scope.$on("$viewContentLoaded", function(event, next, current) { 
            setTimeout(function(){
                
                $scope.$apply(function() {
                    $scope.preLoader = false;
                 })
            }, 3000);
        });
        
    }
});
myApp.component('dashboardapp', {
    template: `
        <div ng-if="preLoader === true" class="overlay1" id="loadingScreen">
            <p><img src="assets/images/logo/logo.png"></p>
            <div class="loading-img"></div>
        </div>
        <header></header>
        <div class="dashboardcontentwrp">
            <div class="container-fluid">
                <div class="row">
                    <leftsidebar></leftsidebar>
                    <ui-view></ui-view>
                </div>
            </div>
        </div>`,
    controller: function(enumfactory, $scope, $rootScope, $location){
        
        $scope.preLoader = false;

        $scope.$on("$locationChangeStart", function(event, next, current) { 
            $scope.preLoader = true;
        });

        $scope.$on("$viewContentLoading", function(event, next, current) { 
            $scope.preLoader = true;
        });

        $scope.$on("$viewContentLoaded", function(event, next, current) { 
            setTimeout(function(){
                
                $scope.$apply(function() {
                    $scope.preLoader = false;
                 })
            }, 3000);
        });
        
    }
});