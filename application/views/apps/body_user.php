
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">User</h1>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama User</th>
                  <th>Nama Toko / Rumah Makan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tbl">
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
    <script>
    function request_list(){
      $.ajax({
          url : '<?php echo site_url("api/user_list/") ?>' ,
          type : 'GET',
          dataType : 'json',
          cache: false,
          contentType: false,
          processData: false,
          success : function(response){
              if(response.content.user.length > 0){
                  // alert("OK");
                  var str = '';
                  var no = 1;
                  var total_penjualan = 0;
                  for(var x=0;x<response.content.user.length;x++){
                    str += '<tr>';
                    str += '<td>' + no+ '</td>';
                    str += '<td>' + response.content.user[x].full_name  + '</td>';
                    if(response.content.user[x].shop.length > 0){
                      str += '<td>' + response.content.user[x].shop[0].shop_name + ' <br> ' + response.content.user[x].shop[0].address + '</td>';
                    }else{
                      str += '<td>Tidak memiliki toko</td>';
                    }
                    str += '<td>';
                    str += '<button class="btn btn-danger" onClick="deactive('+ response.content.user[x].id +');">Deactive</button>';
                    str += '</td>';
                    str += '</tr>';
                    no ++;
                  }
                  $("#tbl").html(str);
              }else{
                str += '<tr>';
                str += '<td colspan="4">Tidak ada data</td>';
                str += '</tr>';

                $("#tbl").html(str);
              }
          },
          error : function(response){
          console.log(response);
              alert("error");
              str += '<tr>';
              str += '<td colspan="4">Error</td>';
              str += '</tr>';

              $("#tbl").html(str);
          },
      });
    };

    function deactive(id){
      var konfirmasi = confirm("Apakah Anda yakin akan melakukan deactive user?");
      if(konfirmasi){
        $.ajax({
            url: "<?php echo site_url('api/user_deactive/'); ?>" + id + "/",
            type : 'GET',
            dataType : 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if(response.severity === "success"){
                  alert(response.message);
                  location.reload(); 
                }else{
                  alert(response.message);
                }
            },
            error: function(response){
              alert("Proses deactive gagal.");
            }
        });
      }
    }
    request_list();
    </script>
  </body>
</html>
