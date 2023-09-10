<!DOCTYPE html>
<html class="no-js" lang="">
  <head>
    @include('commonfront.inner_header')
    <!-- Favicons -->
    @include('commonfront.inner_header_script')
  </head>

  <body>
    <!-- BODY SOCAIL -->
    @include('commonfront.inner_socialbar')
    <!-- BODY SOCAIL -->
    
    <header>
      <!-- hEadEr TOP -->
      @include('commonfront.inner_headertop')
      <!-- hEadEr TOP -->
      
      </header>
      <!-- COnTENT AREA -->
      @include('commonfront.tab')
      @yield('content')
      <!-- COnTENT AREA -->
      
      <!-- FOOTER AREA -->
      @include('commonfront.inner_footer')
      @include('commonfront.inner_footerscript')
      <!-- FOOTER AREA -->
  </body>
</html>