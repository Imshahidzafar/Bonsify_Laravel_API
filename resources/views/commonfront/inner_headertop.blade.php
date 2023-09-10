
<!-- HEADER TOP -->
<div class="header_top">
   <div class="container_c">
      <div class="header_top_item">
         <a href="#" class="top_btn">
         <i class="fas fa-mobile-alt generic-red"></i>
         Download App via SMS
         </a>
         <div class="header_top_log">
            <ul>
               <li class="urdu"><a href="#">Arabic</a></li>
                
               @if(!empty(session('user_name')))
               <li class="dropdown" id="user-menu">
                  <a href="javascript:void(0)" class="username dropdown-toggle" data-toggle="dropdown" rel="nofollow" title="Welcome  aimankhanji123!">Welcome <strong> {{ Session::get('user_name')}}</strong>! </a>
                  <ul class="dropdown-menu">
                     <li><a href="{{ url('/myprofile')}}" >Profile</a></li>
                     <!--  <li><a href="/users/my-ads" rel="nofollow">My Ads</a></li>
                        <li><a href="/users/saved-ads" rel="nofollow">My Saved Ads</a></li>
                        <li><a href="/rides/my_rides/" rel="nofollow">My Rides</a></li>
                        <li><a href="/alerts?type=car" rel="nofollow">Alerts</a></li>
                        <li><a href="/conversations" rel="nofollow">Messages (0)</a></li>
                        <li><a href="/password_resets/4408488/edit" rel="nofollow">Change Password</a></li>
                        <li><a href="/users/my-credits" rel="nofollow">Payments &amp; Receipts</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout" onclick="removeCookies()" rel="nofollow">Sign Out</a></li> -->
                  </ul>
               </li>
               </li>
               @endif
               @if (session()->get('id'))
               <li><a href="{{ url('/logout')}}">Sign Out</a></li>
               @endif
               @if (!session()->get('id'))         
               <li><a href="{{ url('/signup')}}">Sign Up</a></li>
               <li><a href="{{ url('/dealer_signup')}}">Dealer Sign Up</a></li>
               <li><a href="{{ url('/login')}}">Sign In</a></li>
               @endif
            </ul>
         </div>
      </div>
   </div>
</div>
<!-- HEADER TOP -->
<!-- HEADER MENU LOGO -->
<div class="header_logo_menu">
   <div class="container_c">
      <div class="header_logo_menu">
         <a href="{{ url('/')}}" class="logo">
         <img src="{{asset('front/assets/img/logo.png')}}" alt="logo" />
         <a class="mobile_bars" href="#"><i class="fas fa-bars"></i></a>
         </a>
         <div class="header_main_menu">
            <ul class="main_menu_nav">
               <li class="dropdown">
                  <a href="{{url('used_car')}}" title="Used Cars for sale in Pakistan">
                  Used Cars <i class="fa fa-caret-down"></i>
                  </a>
                  <ul class="dropdown-menu mega-navbar columns-1">
                     <li class="clearfix">
                        <div class="col-md-12">
                           <ul class="list-unstyled">
                              <li>
                                 <a href="{{url('car')}}" title="Used Cars Search">
                                    <i class="fa fa-search"></i>
                                    <strong>Find Used Cars</strong>
                                    <p>Browse over 150k options</p>
                                 </a>
                              </li>
                              <li>
                                 <a
                                    href="{{url('/used_cars/feature')}}"
                                    title="Featured Used Cars"
                                    rel="nofollow"
                                    >
                                    <i class="fa fa-star"></i>
                                    <strong>Featured Used Cars</strong>
                                    <p>View featured cars by PakWheels</p>
                                 </a>
                              </li>
                              <li>
                                 <a
                                    href="<?php
                                     if(session('id')){ ?>
                                       {{url('/car_sale')}} 
                                     <?php }else{?>
                                       {{url('/login')}} 
                                       <?php }?>"
                                    class="sign-in-comp"
                                    title="Sell Cars in Pakistan"
                                    onclick="trackEvents('Navigation', 'SubTabs - SellYourCar','Used Cars');"
                                    >
                                    <i class="fa fa-tag"></i>
                                    <strong>Sell Your Car</strong>
                                    <p>Post a free ad and sell your car quickly</p>
                                 </a>
                              </li>
                              <li>
                                 <a
                                    href="{{url('/dealers')}}"
                                    
                                    title="Dealers OF Used Cars"
                                    rel="nofollow"
                                    >
                                    <i class="fa fa-book"></i>
                                    <strong>Used Car Dealers</strong>
                                    <p>Find Used Car Dealers Near You</p>
                                 </a>
                              </li>
                              <li>
                                 <a
                                    href="{{url('/used_cars/certified')}}"
                                    
                                    title="Featured Used Cars"
                                    rel="nofollow"
                                    >
                                    <i class="fas fa-check-square"></i>
                                    <strong>Certified Used Cars</strong>
                                    <p>View Certified cars by PakWheels</p>
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </li>
               
               <li class="dropdown">
                  <a href="/new-cars/" title="Research New Cars in Pakistan, Car Prices, Reviews and Comparisons">
                  New Cars <i class="fa fa-caret-down"></i>
                  </a>
                  <ul class="dropdown-menu mega-navbar columns-1">
                     <li class="clearfix">
                        <div class="col-md-12">
                           <ul class="list-unstyled">
                              <li>
                                 <a href="{{url('/car')}}" title="Research New Cars in Pakistan, Car Prices, Reviews and Comparisons">
                                    <i class="fa fa-search"></i>
                                    <strong>Find New Cars</strong>
                                    <p>See new cars in Pakistan</p>
                                 </a>
                              </li>
                              <li>
                                 <a href="{{url('/compare')}}" title="Car Comparisons">
                                    <i class="fa fa-columns"></i>
                                    <strong>Car Comparisons</strong>
                                    <p>Compare cars and find their differences</p>
                                 </a>
                              </li>
                              <li>
                                 <a href="{{url('/dealers')}}" title="New Cars Dealers in Pakistan" onclick="trackEvents('Dealers', 'From - Navigation','new cars');" >
                                    <i class="fa fa-book"></i>
                                    <strong>Car Dealers</strong>
                                    <p>Find car dealers</p>
                                 </a>
                              </li>
                              <li>
                             <a href="{{url('/pricelist')}}" title="Reviews">
                                <i class="fa fa-tags"></i>
                               <strong>Price</strong>
                               <p>See Prices of New cars in Pakistan </p>
                             </a>
                           </li>
                           <a href="{{url('/on_road')}}" title="Reviews">
                               <i class="fa fa-road"></i>
                               <strong>On Road Price</strong>
                               <p>calculate the total cost of new car </p>
                             </a>
                           </li>
                              <li>
                             <a href="{{url('/reviews')}}" title="Reviews">
                               <i class="fa fa-comments"></i>
                               <strong>Reviews</strong>
                               <p>Read reviews of all cars in Pakistan </p>
                             </a>
                           </li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </li>
               <li>
                  <a href="{{ url('/forum') }}" >Forums</a>
               </li>
               <li>
                  <a href="{{ url('/faqs') }}" >FAQS</a>
               </li>
               <li>
                  <a href="{{url('/blogs')}}" target="_blank" title="Read Pakistan Automotive News &amp; Blog">Blog</a>
               </li>
               @if (session()->get('id'))      
               <li><a href="{{ url('/my_ads')}}" title="My Ads">My Ads</a></li>
               @endif
               <li class="post-an-ads dropdown">
                  @if (session()->get('id')) 
                  <a href="{{ url('/car_sale_intro')}}" class="sign-in-comp" title="Sell Cars in Pakistan"> Post an Ad <i class="fa fa-caret-down"></i></a>
                  @endif
                  @if (!session()->get('id')) 
                  <a href="{{ url('/login')}}" class="sign-in-comp" title="Sell Cars in Pakistan"> Post an Ad <i class="fa fa-caret-down"></i></a>
                  @endif
                  <ul class="dropdown-menu">
                     @if (session()->get('id')) 
                     <li class="post-an-ads dropdown">
                        <a href="{{ url('/car_sale_intro')}}" class="sign-in-comp" title="Sell Cars in Pakistan">
                        <strong>Sell Your Car</strong>
                        </a>
                     </li>
                     @endif
                     @if (!session()->get('id')) 
                     <li class="post-an-ads dropdown">
                        <a href="{{ url('/login')}}" class="sign-in-comp" title="Sell Cars in Pakistan">
                        <strong>Sell Your Car</strong>
                        </a>
                     </li>
                     @endif
                  </ul>
               </li>
            </ul>
            <!-- mobile menu accoudion -->
            <ul id="accordion" class="accordion">
               <li>
                  <div class="link">Used Cars<i class="fa fa-chevron-down"></i></div>
                  <ul class="submenu ar">
                     <li>
                        <a href="/used-cars/" title="Used Cars Search">
                           <i class="fa fa-search"></i>
                           <strong>Find Used Cars</strong>
                           <p>Browse over 150k options</p>
                        </a>
                     </li>
                     <li>
                        <a href="/used-cars/search/-/featured_1/?nf=true" title="Featured Used Cars" rel="nofollow">
                           <i class="fa fa-star"></i>
                           <strong>Featured Used Cars</strong>
                           <p>View featured cars by PakWheels</p>
                        </a>
                     </li>
                     <li>
                        <a href="/used-cars/sell" class="sign-in-comp" title="Sell Cars in Pakistan" >
                           <i class="fa fa-tag"></i>
                           <strong>Sell Your Car</strong>
                           <p>Post a free ad and sell your car quickly</p>
                        </a>
                     </li>
                     <li>
                                 <a
                                    href="{{url('/dealers')}}"
                                    
                                    title="Featured Used Cars"
                                    rel="nofollow"
                                    >
                                    <i class="fa fa-star"></i>
                                    <strong>Syarat  Used Car Dealers</strong>
                                    <p>Find Used Car Dealers Near You</p>
                                 </a>
                              </li>
                  </ul>
               </li>
               <li>
                  <div class="link">New Cars<i class="fa fa-chevron-down"></i></div>
                  <ul class="submenu ar">
                     <li>
                        <a href="/new-cars/" title="Research New Cars in Pakistan, Car Prices, Reviews and Comparisons">
                           <i class="fa fa-search"></i>
                           <strong>Find New Cars</strong>
                           <p>See new cars in Pakistan</p>
                        </a>
                     </li>
                     <li>
                        <a
                           href="/new-cars/compare/"
                           title="Car Comparisons"
                           >
                           <i class="fa fa-columns"></i>
                           <strong>Car Comparisons</strong>
                           <p>Compare cars and find their differences</p>
                        </a>
                     </li>
                     <li>
                        <a
                           href="/new-cars/dealers/"
                           title="New Cars Dealers in Pakistan"
                           onclick="trackEvents('Dealers', 'From - Navigation','new cars');"
                           >
                           <i class="fa fa-book"></i>
                           <strong>New Car Dealers</strong>
                           <p>Find new car dealers</p>
                        </a>
                     </li>

                  </ul>
               </li>
               <li>
                  <a href="{{ url('/forum') }}" >Forums</a>
               </li>
               <li>
                  <a href="{{ url('/') }}" >FAQS</a>
               </li>
               <li>
                  <a href="/blog/" target="_blank" title="Read Pakistan Automotive News &amp; Blog">Blog</a>
               </li>
               @if (session()->get('id'))      
               <li><a href="{{ url('/my_ads')}}" title="My Ads">My Ads</a></li>
               @endif
               </li>
               <div class="link">Post an Ad<i class="fa fa-chevron-down"></i></div>
               <ul class="submenu ar">
                  @if (session()->get('id'))      
                  <li> 
                     <a href="{{ url('/car_sale_intro')}}" class="sign-in-comp" title="Sell Cars in Pakistan">
                     <strong>Sell Your Car</strong>
                     </a>
                  </li>
                  @endif
                  @if (!session()->get('id'))  
                  <li> 
                     <a href="{{ url('/login')}}" class="sign-in-comp" title="Sell Cars in Pakistan">
                     <strong>Sell Your Car</strong>
                     </a>
                  </li>
                  @endif
               </ul>
               </li>
            </ul>
            <!-- mobile menu accoudion -->
         </div>
      </div>
   </div>
</div>
<!-- HEADER MENU LOGO -->