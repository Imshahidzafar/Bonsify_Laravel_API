<div id="page-sidebar">
    <div class="scroll-sidebar">
        <ul id="sidebar-menu">
             <li>
                <a href="{{ url('/') }}" title="Elements">
                    <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="javascript:;" title="Events">
                    <i class="fab fa-bandcamp"></i>
                    <span>Events</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ url('/manage_eventposts') }}" title="Buttons"><span>Manage Events</span></a></li>
                        <li><a href="{{ url('/manage_addresses') }}" title="Buttons"><span>Event Addresses</span></a></li>
                        <li><a href="{{ url('/manage_eventtickets') }}" title="Buttons"><span>Event Tickets</span></a></li>
                        <li><a href="{{ url('/manage_eventtags') }}" title="Buttons"><span>Event Tags</span></a></li>

                    </ul>
                </div>
            </li>

            <li>
                <a href="javascript:;" title="Users">
                    <i class="fab fa-bandcamp"></i>
                    <span>Users</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ url('/new_manage_users')  }}" title="Buttons"><span>Manage Users</span></a></li>
                        <li><a href="{{ url('/new_manage_usercategories') }}" title="Buttons"><span>User Categories</span></a></li>

                    </ul>
                </div>
            </li>

             <li>
                <a href="{{ url('')}}" title="Sales">
                    <i class="fab fa-bandcamp"></i>
                    <span>Manage Sales</span>
                </a>
            </li>

            <li>
                <a href="javascript:;" title="Manage site">
                    <i class="fab fa-bandcamp"></i>
                    <span>Manage System</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ url('/manage_categories') }}" title="Buttons"><span>Categories</span></a></li>
                        <li><a href="{{ url('/manage_eventtypes') }}" title="Buttons"><span>Event types</span></a></li>
                        <li><a href="{{ url('/manage_dresscodes') }}" title="Buttons"><span>Dress Codes</span></a></li>
                        <li><a href="{{ url('/manage_tags') }}" title="Buttons"><span>Tags</span></a></li>

                    </ul>
                </div>
            </li>

            <li>
                <a href="javascript:;" title="Payments Management">
                    <i class="fab fa-bandcamp"></i>
                    <span>Payments Management</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ url('/manage_cards') }}" title="Buttons"><span>Cards</span></a></li>
                        <li><a href="{{ url('/manage_conncash') }}" title="Buttons"><span>Conncash</span></a></li>
                        <li><a href="{{ url('/request_payment') }}" title="Buttons"><span>Requested Payments</span></a></li>
                    </ul>
                </div>
            </li>




        {{--    PREVIOUS ADMIN PANEL SIDE NAVIGATION ITEMS  --}}
         {{--
            <li>
                <a href="javascript:;" title="Elements">
                    <i class="fab fa-bandcamp"></i>
                    <span>General Settings</span>
                </a>
                <div class="sidebar-submenu">
                    <ul>
                        <li>
                            <a href="{{ url('/backoffice/manage_car_body') }}" title="Manage car body">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage car body</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_make') }}" title="Manage Make">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage makes</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_car_color') }}" title="Manage Car Colors">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage Car Colors</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_years') }}" title="Manage Years">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage Years</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_payment_method') }}" title="Manage Payment Method">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage Payment Method</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_packages') }}" title="Manage Packages">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage Packages</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_states') }}" title="Manage states">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage States</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/manage_cities') }}" title="Manage cities">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage Cities</span>

                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/backoffice/static_content') }}" title="Manage cities">
                                <i class="fab fa-bandcamp" aria-hidden="true"></i>
                                <span>Manage Static Content</span>

                            </a>
                        </li>
                         <li>
                            <a href="javascript:;" title="Elements">
                                <i class="fab fa-bandcamp"></i>
                                <span>Manage Static Pages</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <li><a href="{{ url('/backoffice/about') }}" title="Buttons"><span>About Us</span></a></li>
                                    <li><a href="{{ url('/backoffice/privacy') }}" title="Buttons"><span>Privacy Policy</span></a></li>
                                    <li><a href="{{ url('/backoffice/terms') }}" title="Buttons"><span>Terms And Conditions</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a href="{{ url('/backoffice/email_templates') }}" title="Buttons"><span>Email Template Settings</span></a></li>

                        <li><a href="{{ url('/backoffice/language_settings') }}" title="Buttons"><span>Language Settings</span></a></li>
                        <li><a href="{{ url('/backoffice/system_settings') }}" title="Buttons"><span>System Settings</span></a></li>
                    </ul>
                </div>
            </li>

            <li>
                <a href="{{ url('/backoffice/account_settings') }}" title="Elements">
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                    <span>Account Settings</span>
                </a>
            </li>
                         --}}
        </ul>
    </div>
</div>
