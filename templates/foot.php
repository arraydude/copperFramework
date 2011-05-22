    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo copperConfig::get('appId'); ?>',
          status: false,
          cookie: true,
          xfbml: true,
          channelUrl: '<?php echo copperConfig::get('callbackUrl'); ?>channel.html'
        });
        FB.getLoginStatus(function(response) {
            if (response.session) {
              afterFbLogin();
            }
        });
        FB.Canvas.setAutoResize();
      };
      (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
  </body>
</html>
