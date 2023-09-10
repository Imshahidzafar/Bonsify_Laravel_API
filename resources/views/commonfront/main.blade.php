<!DOCTYPE html>
<html class="no-js" lang="">
  <head>
    @include('commonfront.header')
    <!-- Favicons -->
    @include('commonfront.header_script')
  </head>

  <body>
    <!-- BODY SOCAIL -->
    @include('commonfront.socialbar')
    <!-- BODY SOCAIL -->
    
    <header>
    <!-- hEadEr TOP -->
    @include('commonfront.headertop')
    <!-- hEadEr TOP -->
    
    <!-- hEadEr searCh -->
    @include('commonfront.headersearch')
    <!-- hEadEr searCh -->
    </header>
    <!-- COnTENT AREA -->
    @yield('content')
    <!-- COnTENT AREA -->
    
    <!-- FOOTER AREA -->
    @include('commonfront.footer')
    @include('commonfront.footerscript')
    <!-- FOOTER AREA -->
  </body>
</html>