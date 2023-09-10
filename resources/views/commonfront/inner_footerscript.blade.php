
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous"referrerpolicy="no-referrer"></script>
<!-- BOOTSTRAP -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script> -->
<script type="text/javascript" src="{{asset('assets/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{asset('front/assets/js/vendor/modernizr-3.11.2.min.js')}}"></script>
<script src="{{asset('front/assets/js/vendor/owl.carousel.min.js')}}"></script>
<script src="{{asset('front/assets/js/plugins.js')}}"></script>
<script src="{{asset('front/assets/js/main.js')}}"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<style type="text/css">.dataTables_wrapper{  overflow: scroll !important;}</style>
<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );

$(document).ready( function () {
    $('#example').DataTable();
} );

   $('#myModal').modal('toggle');
</script>
<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
<script>
  window.ga = function () {
    ga.q.push(arguments);
  };
  ga.q = [];
  ga.l = +new Date();
  ga("create", "UA-XXXXX-Y", "auto");
  ga("set", "anonymizeIp", true);
  ga("set", "transport", "beacon");
  ga("send", "pageview");
</script>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"> </script>
<script>
  CKEDITOR.replaceClass = 'ckeditor';
  CKEDITOR.config.allowedContent = true;
</script>
<script src="https://www.google-analytics.com/analytics.js" async></script>

  <script type="text/javascript">
 
  </script>