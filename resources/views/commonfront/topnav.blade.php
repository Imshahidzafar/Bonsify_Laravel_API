<style type="text/css">
    #page-header .user-account-btn .dropdown-menu:after, #page-header .user-account-btn .dropdown-menu:before {
        left: 93%;
    }
</style>

<div id="page-header" class="bg-gradient-9">
    <div id="mobile-navigation">
        <button id="nav-toggle" class="collapsed" data-toggle="collapse" data-target="#page-sidebar"><span></span></button>
        <a href="{{url('/admin/')}}" class="logo-content-small" title="MonarchUI"></a>
    </div>
    <div id="header-logo" class="logo-bg">
        <a href="{{url('/admin/')}}" class="logo-content-big" title="Wan-You">
            Monarch <i>UI</i>
            <span>The perfect solution for user interfaces</span>
        </a>
        <a href="index-2.html" class="logo-content-small" title="Wan-You">
            WAN <i>YOU</i>
            <span>The perfect solution for user interfaces</span>
        </a>
        <a id="close-sidebar" href="#" title="Close sidebar">
            <i class="fas fa-chevron-left"></i>
        </a>
    </div>
    
    <div id="header-nav-left" style="float: right;">
        <div class="user-account-btn dropdown " >
            <a href="#" title="My Account" class="user-profile clearfix" data-toggle="dropdown">
                <img width="28" src="{{asset('assets/image-resources/gravatar.jpg')}}" alt="Profile image">
                <span style="">{{ Session::get('fname')}} {{ Session::get('lname')}}</span>
                <i class="fas fa-chevron-down"></i>
            </a>

            <div class="dropdown-menu float-left">
                <div class="box-sm" style="width: 100px;">
                    <div class="pad5A button-pane button-pane-alt text-center">
                        <a href="{{url('/admin/logout')}}" class="btn display-block font-normal btn-danger" style="    width: 132px;">
                            <i class="glyph-icon icon-power-off"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- #header-nav-left -->
</div>