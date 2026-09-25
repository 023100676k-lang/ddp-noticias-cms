<?php
require_once 'conexion.php';
$sobre_footer = $conexion->query("SELECT mision FROM sobre_dd ORDER BY id ASC LIMIT 1")->fetch_assoc();
?>
<!-- Redes Sociales -->
<div class="middle py-5">
    <div class="container py-xl-5 py-lg-3">
        <div class="welcome-left text-center py-md-5 py-3">
            <h3 class="title-big">Síguenos en nuestras Redes Sociales</h3>
            <div class="main-social-footer-29">
                <a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru" class="facebook"><span class="fa fa-facebook-square fa-2x"></span></a>
                <a target="_blank" href="https://www.tiktok.com/@dialogo.y.desarrollo" class="twitter"><img src="./recursos/tiktokg.png"></a>
                <a target="_blank" href="https://www.instagram.com/dialogo.y.desarrollo/" class="instagram"><span class="fa fa-instagram fa-2x"></span></a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<section class="w3l-footer-29-main py-5" id="footer">
  <div class="footer-29 py-md-3">
    <div class="container">
      <div class="row footer-top-29">
        <div class="col-lg-6 col-md-6 footer-list-29 footer-1">
          <h6 class="footer-title-29">Quiénes Somos</h6>
          <p><?php echo htmlspecialchars($sobre_footer['mision'] ?? 'Somos un espacio de periodismo independiente.'); ?></p>
          <div class="main-social-footer-29">
            <a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru" class="facebook"><span class="fa fa-facebook-square"></span></a>
            <a target="_blank" href="https://www.tiktok.com/@dialogo.y.desarrollo" class="twitter"><img src="./recursos/tiktokp.png"></a>
            <a target="_blank" href="https://www.instagram.com/dialogo.y.desarrollo/" class="instagram"><span class="fa fa-instagram"></span></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 footer-list-29 footer-2 mt-md-0 mt-5">
          <ul>
            <h6 class="footer-title-29">Contenido</h6>
            <li><a href="index.php#actualidad">Noticias</a></li>
            <li><a href="reportajes-1.php">Reportajes</a></li>
            <li><a href="podcast.php">Podcast</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6 mt-lg-0 mt-5 footer-list-29 footer-3">
          <div class="properties">
            <h6 class="footer-title-29">Contacto</h6>
            <ul>
              <li><a href="mailto:info@dialogoydesarrollo.com.pe">info@dialogoydesarrollo.com.pe</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="bottom-copies text-center">
        <p class="copy-footer-29">© 2026 Diálogo y Desarrollo Perú. All rights reserved</p>
      </div>
    </div>
  </div>
  <button onclick="topFunction()" id="movetop" title="Go to top" style="display: none;">
    <span class="fa fa-angle-up"></span>
  </button>
</section>

<script src="./recursos/jquery-3.3.1.min.js.descarga"></script>
<script src="./recursos/theme-change.js.descarga"></script>
<script src="./recursos/easyResponsiveTabs.js.descarga"></script>
<script src="./recursos/owl.carousel.js.descarga"></script>
<script src="./recursos/jquery.magnific-popup.min.js.descarga"></script>
<script src="./recursos/bootstrap.min.js.descarga"></script>
<script>
  $(document).ready(function () {
    $('.owl-logos').owlCarousel({ loop: true, margin: 0, nav: false, responsiveClass: true, autoplay: true, autoplayTimeout: 5000, autoplaySpeed: 1000, autoplayHoverPause: false, responsive: { 0: {items: 2}, 480: {items: 2}, 568: {items: 3}, 1000: {items: 5} } });
    $('.owl-carousel').owlCarousel({ loop: true, margin: 0, responsiveClass: true, responsive: { 0: {items: 1, nav: true}, 400: {items: 2, nav: true, margin: 20}, 768: {items: 3, nav: true, margin: 20}, 1000: {items: 4, nav: true, loop: true, margin: 25} } });
    $('.popup-with-zoom-anim').magnificPopup({ type: 'inline', fixedContentPos: false, fixedBgPos: true, overflowY: 'auto', closeBtnInside: true, preloader: false, midClick: true, removalDelay: 300, mainClass: 'my-mfp-zoom-in' });
    $('.navbar-toggler').click(function () { $('body').toggleClass('noscroll'); });
  });
  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 80) { $("#site-header").addClass("nav-fixed"); } else { $("#site-header").removeClass("nav-fixed"); }
  });
  function topFunction() { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; }
  window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("movetop").style.display = "block";
    } else {
      document.getElementById("movetop").style.display = "none";
    }
  };
</script>
</body>
</html>
