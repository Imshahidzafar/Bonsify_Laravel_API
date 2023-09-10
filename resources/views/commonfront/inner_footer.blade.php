<footer class="footer">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-9">
            <div class="row">
              <div class="col-md-3">
                <ul class="nomargin footer-links list-unstyled" id="footer-Make" >
                  <li><h5>Cars By Make</h5></li>
                  <?php $car_makes=DB::table('car_make')->limit(10)->orderBy('name', 'asc')->get(); ?>
                  @foreach ($car_makes as $key => $items)
                  <li>
                    <a href="/used-cars/{{$items->name}}/" id="footer_{{$items->name}}" title="Cars for Sale in {{$items->name}}" >{{$items->name}}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
              <div class="col-md-3">
                <ul class="nomargin footer-links list-unstyled" id="footer-City">
                  <li><h5>Cars By City</h5></li>
                  <?php $cities=DB::table('cities')->limit(10)->orderBy('name', 'asc')->get(); ?>
                  @foreach ($cities as $key => $items)
                  <li>
                    <a href="/used-cars/{{$items->name}}/" id="footer_{{$items->name}}" title="Cars for Sale in {{$items->name}}" >Cars in {{$items->name}}</a>
                  </li>
                  @endforeach
                </ul>
              </div>

              <div class="col-md-3">
                <ul class="nomargin footer-links list-unstyled">
                  <li><h5>Explore Sayarat</h5></li>
                  <li><a href="/used-cars/" title="Used Cars">Used Cars</a></li>
                  <li><a href="/new-cars/" title="New Cars">New Cars</a></li>
                  <li><a href="{{url('/forum')}}" rel="nofollow" title="Forums">Forums</a></li>
                  <li><a href="/sitemap/" title="Sitemap">Sitemap</a></li>
                </ul>
              </div>
              <div class="col-md-3">
                <ul class="nomargin footer-links list-unstyled">
                  <li><h5>Sayarat.com</h5></li>
                  <li><a  href="{{url('/about')}}" rel="nofollow" title="About Sayarat.com" >About Sayarat.com</a></li>
                  <li><a href="{{url('/faqs')}}" rel="nofollow" title="FAQs">FAQs</a></li>
                  <li><a href="{{url('contact_us')}}" rel="nofollow" title="Contact Us">Contact Us</a></li>
                </ul>
              </div>
            </div>

            <div class="row mt20">
              <div class="col-md-3">
                <ul class="nomargin footer-links list-unstyled" id="footer-category">
                  <li><h5>Cars by Category</h5></li>
                  <li>
                    <a href="/used-cars/jeep/72893" title="Jeeps for sale in Pakistan">Jeep</a>
                  </li>
                </ul>
              </div>

              <div class="col-md-3">
                <ul class="nomargin footer-links list-unstyled" id="footer-body_type" >
                  <li><h5>Cars by Body Type</h5></li>
                  <?php $car_body=DB::table('car_body')->limit(10)->orderBy('name', 'asc')->get(); ?>
                  @foreach ($car_body as $key => $items)
                  <li>
                    <a href="/used-cars/{{$items->name}}/" id="footer_{{$items->name}}" title="Cars for Sale in {{$items->name}}" >{{$items->name}}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
              <div class="col-md-3">
                <ul
                  class="nomargin footer-links list-unstyled"
                  id="footer-color"
                >
                  <li><h5>Cars by Color</h5></li>
                  <?php $car_colors=DB::table('car_colors')->limit(10)->orderBy('name', 'asc')->get(); ?>
                  @foreach ($car_colors as $key => $items)
                  <li>
                    <a href="/used-cars/{{$items->name}}/" id="footer_{{$items->name}}" title="Cars for Sale in {{$items->name}}" >{{$items->name}}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <form
              id="subscribe_newsletter"
              class="form-horizontal newsletter-subscription"
            >
              <h5>Subscribe to our Newsletter</h5>
              <div
                id="newsletter-message"
                style="display: none; font-size: 14px; color: #999999"
              ></div>
              <div class="form-fields">
                <input
                  type="text"
                  id="email"
                  name="email"
                  placeholder="name@email.com"
                  style="width: 100%; font-family: arial"
                />
                <button type="submit" class="btn btn-success btn-xs">
                  Subscribe
                </button>
                <div class="error-message" style="color: red"></div>
              </div>
            </form>
            <div class="mb30 footer-social clearfix">
              <h5>Follow Us</h5>
              <ul class="list-unstyled list-inline networks primary-lang">
                <li>
                  <a
                    href="https://www.twitter.com/pakwheels"
                    class="twitter"
                    rel="nofollow"
                    target="_blank"
                    title="Follow Us On Twitter"
                    ><i class="fab fa-twitter"></i
                  ></a>
                </li>
                <li>
                  <a
                    href="https://www.facebook.com/pakwheels"
                    class="facebook"
                    rel="nofollow"
                    target="_blank"
                    title="Follow Us On Facebook"
                    ><i class="fab fa-facebook"></i
                  ></a>
                </li>
                <li>
                  <a
                    href="https://plus.google.com/117775652035228984610"
                    class="googleplus"
                    rel="me"
                    target="_blank"
                    title="Follow Us On Google Plus"
                    ><i class="fab fa-google-plus"></i
                  ></a>
                </li>
                <li>
                  <a
                    href="https://pinterest.com/pakwheels/"
                    class="pinterest"
                    rel="nofollow"
                    target="_blank"
                    title="Follow Us On Pinterest"
                    ><i class="fab fa-pinterest"></i
                  ></a>
                </li>
                <li>
                  <a
                    href="https://instagram.com/pakwheels"
                    class="instagram"
                    rel="nofollow"
                    target="_blank"
                    title="Follow Us On Instagram"
                    ><i class="fab fa-instagram"></i
                  ></a>
                </li>
                <li>
                  <a
                    href="https://www.youtube.com/user/pakwheels"
                    class="youtube"
                    rel="me"
                    target="_blank"
                    title="Follow Us On Youtube"
                    ><i class="fab fa-youtube"></i
                  ></a>
                </li>
              </ul>
            </div>
            <div>
              <h5>
                <a href="/apps" class="generic-white">Download Mobile Apps</a>
              </h5>
              <a
                href="https://play.google.com/store/apps/details?id=com.pakwheels&amp;referrer=utm_source%3Dpakwheels.com%26utm_medium%3Dweb-link%26utm_content%3Dfooter%26utm_campaign%3Dorganic-installs"
                target="_blank"
                title="Sayarat Android App"
                ><img
                  alt="Google-play-badge"
                  height="40"
                  loading="lazy"
                  src="https://wsa4.pakwheels.com/assets/google-play-badge-f4bed6cbd8a3a1be7c79377c4441447a.svg"
              /></a>
              <a
                href="https://click.google-analytics.com/redirect?tid=UA-642162-19&amp;url=https%3A%2F%2Fitunes.apple.com%2Fpk%2Fapp%2Fpakwheels%2Fid739776365%3Fmt%3D8&amp;aid=com.pakwheels.www&amp;idfa={idfa}&amp;cs=pakwheels.com&amp;cm=web-link&amp;cn=organic-installs&amp;cc=footer&amp;hash=md5"
                target="_blank"
                title="Sayarat iOS App"
                ><img
                  alt="App-store-badge"
                  height="40"
                  loading="lazy"
                  src="https://wsa1.pakwheels.com/assets/app-store-badge-4d05ff70e5546f31e3891739ea40abad.svg"
              /></a>
            </div>
          </div>
        </div>

        <hr class="dark" />

        <div class="copyright footer-links mt30">
          Copyright © 2021 - <?php echo date('Y'); ?> Sayarat (Pvt) Ltd. - All Rights Reserved.<br />
          <a href="/terms" rel="nofollow" title="Terms of Service"
            >Terms of Service</a
          >&nbsp;|&nbsp;
          <a href="/privacy" rel="nofollow" title="Privacy Policy"
            >Privacy Policy</a
          >
        </div>

        <p class="copyright mt5">
          Reproduction of material from any Sayarat.com pages without
          permission is strictly prohibited.
        </p>
      </div>
    </footer>