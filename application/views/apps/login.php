<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">
    <title>Food Marketplace</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/signin.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/jquery.js');?>"></script>
  </head>

  <body class="text-center">
    <form class="form-signin" style="background-color:white" id="my_form" role="form" method="post" enctype="multipart/form-data">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <div id="notif">
        <div class="alert alert-<?php echo $severity; ?>" role="alert">
          <?php echo $message; ?>
        </div>
      </div>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="text" id="username" name="username" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="password" name="password"  class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y")?></p>
    </form>
    <script>
      $("form#my_form").submit(function(e) {
          var notif = '';
          e.preventDefault();    
          var formData = new FormData(this);
          $.ajax({
              url: "<?php echo site_url('/api/verifikasi_web/'); ?>",
              type: 'POST',
              data: formData,
              dataType : 'json',
              success: function(response) {
                  console.log(response);
                  if(response.severity == "success"){
                    notif += '<div class="alert alert-' + response.severity + '" role="alert">';
                    notif += response.message;
                    notif += '</div>';
                    $("#notif").html(notif);
                    location.reload();
                  }else{
                    notif += '<div class="alert alert-' + response.severity + '" role="alert">';
                    notif += response.message;
                    notif += '</div>';
                    $("#notif").html(notif);
                  }
              },
              error: function(response){
                  notif += '<div class="alert alert-' + response.severity + '" role="alert">';
                  notif += response.message;
                  notif += '</div>';
                  $("#notif").html(notif);
              },
              cache: false,
              contentType: false,
              processData: false
          });
          notif = '';
      });
      </script>
  </body>
</html>
