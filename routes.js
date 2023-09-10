myApp.config(['$stateProvider', '$urlRouterProvider', '$locationProvider','$validatorProvider','$cookiesProvider', function($stateProvider, $urlRouterProvider, $locationProvider,$validatorProvider,$cookiesProvider){
    
    $validatorProvider.setDefaults({
        errorElement: 'div',
        errorClass: 'error-message-form'
    });

    $validatorProvider.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "Space not allowed");

    $validatorProvider.addMethod("passwordFormate", function (value) {
        return /[\@\#\$\%\^\&\*\(\)\_\+\!]/.test(value) && /[a-z]/.test(value) && /[0-9]/.test(value) && /[A-Z]/.test(value);
    },"Invalid password format");

    $validatorProvider.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[\w.-]+$/i.test(value);
    }, "Special Characters not allowed");

    $locationProvider.html5Mode({
      enabled: true,
      requireBase: false
    });
    
    $urlRouterProvider.otherwise('/');
    
        $stateProvider
        .state('app', {
            component: 'app'
        })
        .state('dashboardapp', {
            component: 'dashboardapp'
        })
        .state('app.home-default', {
            url: '/home',
            component: 'home'
        })
        .state('app.home', {
            url: '/',
            component: 'home'
        })
        .state('app.cms', {
            url: '/:page',
            component: 'cms'
        })
        .state('app.gallery', {
            url: '/:slug/gallery',
            component: 'gallery',
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                return $ocLazyLoad.load({
                    files: [
                        'assets/plugins/light-box/css/lightbox.min.css',
                        'assets/plugins/light-box/js/lightbox.min.js'
                        ]
                    });
                }]
            }
        })
        .state('app.contact', {
            url: '/contact-us',
            component: 'contact',
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                return $ocLazyLoad.load({
                    files: [
                        'assets/plugins/validate/jquery.validate.min.js',
                        'assets/plugins/validate/additional-methods.min.js'
                        ]
                    });
                }]
            }
        })
        .state('app.howItWorks', {
            url: '/how-it-works',
            component: 'howItWorks',
            resolve: {
            }
        })
        .state('app.health', {
            url: '/health',
            component: 'health',
            resolve: {
            }
        })
        .state('app.forgotPassword', {
            url: '/forgotPassword',
            component: 'forgotPassword',
            resolve: {
                bioAList: ['$http', function ($http,$location) {
                    return checkLogout($http);
                }]
            }
        })
        .state('app.resetPassword', {
            url: '/resetPassword/:token',
            component: 'resetPassword',
            resolve: {
                bioAList: ['$http', function ($http,$location) {
                    return checkLogout($http);
                }]
            }
        })
        .state('app.login', {
            url: '/login',
            component: 'clientLogin',
            resolve: {
                bioAList: ['$http', function ($http,$location) {
                    return checkLogout($http);
                }]
            }
        })
        .state('app.logout', {
            url: '/logout',
            component: 'logout',
        })
        .state('app.signup', {
            url: '/signup',
            component: 'clientSignup',
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                return $ocLazyLoad.load({
                    files: [
                        'assets/plugins/password_strength/password.min.css',
                        'assets/plugins/password_strength/password.min.js',
                        'assets/plugins/build/css/intlTelInput.css',
                        'assets/plugins/build/js/intlTelInput.js',
                        'assets/plugins/build/js/jquery.mask.min.js'
                        ]
                    });
                }],
                bioAList: ['$http', function ($http,$location) {
                    return checkLogout($http);
                }]
            }
        })
        .state('app.help', {
            url: '/help',
            component: 'help'
        })
        .state('app.search-beauty', {
            url: '/search-beauty/:loc/:type/:id',
            params: {
              a: { squash: true, value: null },
              lat: null,
              lng: null
            },
            component: 'searchBeauty',
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                return $ocLazyLoad.load({
                    files: [
                        'assets/plugins/slick/slick.css',
                        'assets/plugins/slick/slick-theme.css',
                        '//code.jquery.com/jquery-migrate-1.2.1.min.js',
                        'assets/plugins/slick/slick.min.js'
                        ]
                    });
                }]
            }
        })
        .state('app.search-health', {
            url: '/search-health/:a/:b/:c/:d/:e/:f',
            params: {
              a: { squash: true, value: null }
            },
            component: 'searchHealth',
            resolve: {
            }
        })
        .state('app.businessDetails', {
            url: '/businessDetails/:a',
            component: 'businessDetails',
            resolve: {
            }
        })
        .state('app.profile', {
            url: '/profile/:slug',
            component: 'profile',
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/dateTimePicker/jquery.datetimepicker.css',
                            'assets/plugins/dateTimePicker/moment.js',
                            'assets/plugins/dateTimePicker/jquery.datetimepicker.js',
                            'assets/plugins/owl-carousel/owl.carousel.css',
                            'assets/plugins/owl-carousel/owl.carousel.js',
                            'https://js.stripe.com/v3/'
                            //'assets/plugins/socialShare/angular-socialshare.js'
                            //'assets/plugins/angular-multiselect/angularjs-dropdown-multiselect.min.js'
                            //"https://platform-api.sharethis.com/js/sharethis.js#property=5d0c85cca0d27e001207a2b5&product='inline-share-buttons"
                        ]
                    });
                }]
            }
        })
        .state('app.editprofile', {
            url: '/editprofile',
            component: 'editProfile'
        })
        .state('profile-completion', {
            url: '/profile-completion',
            component: 'profileCompletion',
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                return $ocLazyLoad.load({
                    files: [
                        'assets/plugins/validate/jquery.validate.min.js',
                        'assets/plugins/validate/additional-methods.min.js'
                        ]
                    });
                }]
            }
        })
        .state('app.business-profile', {
            url: '/business-profile',
            component: 'businessProfile'
        })
        .state('app.booking', {
            url: '/:business/book-now',
            component: 'booking',
            params: {
                //business: '1',
                service: null,
                service_name: null,
                provider: null,
                deal: null,
                date:null
            },
            resolve: {
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/dateTimePicker/jquery.datetimepicker.css',
                            'assets/plugins/dateTimePicker/moment.js',
                            'assets/plugins/dateTimePicker/jquery.datetimepicker.js',
                            'assets/plugins/owl-carousel/owl.carousel.css',
                            'assets/plugins/owl-carousel/owl.carousel.js',
                            'https://js.stripe.com/v3/'
                            //'assets/plugins/socialShare/angular-socialshare.js'
                            //'assets/plugins/angular-multiselect/angularjs-dropdown-multiselect.min.js'
                            //"https://platform-api.sharethis.com/js/sharethis.js#property=5d0c85cca0d27e001207a2b5&product='inline-share-buttons"
                        ]
                    });
                }]
            }
        })
        .state('app.dealBooking', {
            url: '/:business/book-a-deal',
            component: 'dealBooking',
            params: {
                deal: null
            }
        })
        .state('app.deal', {
            url: '/deal/:slug',
            component: 'deal'
        })
        .state('app.addreview', {
            url: '/:param/addreview',
            component: 'review',
            params: {
                services: null,
                provider: null,
                order: null
            },
            resolve: {
                bioAList: ['$http', function ($http,$location) {
                    return checkLogout($http);
                }],
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                    return $ocLazyLoad.load({
                    files: [
                        //'assets/plugins/validate/jquery.validate.min.js',
                        //'assets/plugins/validate/additional-methods.min.js'
                        ]
                    });
                }]
            }
        })
        .state('app.checkout', {
            url: '/:business/checkout',
            component: 'checkout',
            params: {
                slot:null,
                params:null,
            },
            resolve: { 
                loadMyCtrl:['$ocLazyLoad',function($ocLazyLoad){
                return $ocLazyLoad.load({
                    files: [
                        'https://js.stripe.com/v3/',
                        /*'assets/plugins/stripe/base.css',*/
                        //'assets/plugins/stripe/index.js',
                        //'assets/plugins/stripe/example3.js'
                        ]
                    });
                }]
            }
        });
}]);

function checkLogin($http,$location) {
    $http.get("index1.php/profile/check_login").then(function(res) {
        if (res.data.condition == 'not-login') {
            window.location.href = "http://localhost/ondaq_testing/login";
        }
    });
}
function checkLogout($http) {
    $http.get("index1.php/profile/check_login").then(function(res) {
        if (res.data.condition == 'login') {
            window.location.href = "http://localhost/ondaq_testing/";
        }
    });
}