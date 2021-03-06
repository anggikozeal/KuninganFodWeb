
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Request Pencairan</h1>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Toko / Nama Owner</th>
                  <th>Saldo Tersedia</th>
                  <th colspan="2">Nominal Request</th>
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
          url : '<?php echo site_url("api/drawdown_request_list/") ?>' ,
          type : 'GET',
          dataType : 'json',
          cache: false,
          contentType: false,
          processData: false,
          success : function(response){
              if(response.content.drawdown.length > 0){
                  // alert("OK");
                  var str = '';
                  var no = 1;
                  var total_penjualan = 0;
                  for(var x=0;x<response.content.drawdown.length;x++){
                    if(response.content.transaction.length > 0){
                      for(var i=0;i<response.content.transaction.length;i++){
                        if(response.content.transaction[i].id_shop == response.content.drawdown[x].id_shop){
                          total_penjualan = total_penjualan + parseFloat(response.content.transaction[i].history.total);
                        }
                      }
                    }
                    var saldo_tersedia = total_penjualan - response.content.drawdown[x].recent_drawdown;
                    str += '<tr>';
                    str += '<td>' + no+ '</td>';
                    str += '<td>' + response.content.drawdown[x].shop.shop_name + '<br>' + response.content.drawdown[x].seller.full_name  + '</td>';
                    str += '<td>' + saldo_tersedia + '</td>';
                    str += '<td>Rp</td>';
                    str += '<td>' + response.content.drawdown[x].total + '</td>';
                    str += '<td>';
                    str += '<button class="btn btn-success" onClick="approve('+ response.content.drawdown[x].id +',\'ON_APPROVE\');">Approve Pencairan</button>';
                    str += '</td>';
                    str += '</tr>';
                    no ++;
                  }
                  $("#tbl").html(str);
              }else{
                str += '<tr>';
                str += '<td colspan="6">Tidak ada data</td>';
                str += '</tr>';

                $("#tbl").html(str);
              }
          },
          error : function(response){
          console.log(response);
              alert("error");
              str += '<tr>';
              str += '<td colspan="6">Error</td>';
              str += '</tr>';

              $("#tbl").html(str);
          },
      });
    };

    function approve(id,new_status){
      alert(id + " dan " + new_status);
      var konfirmasi = confirm("Apakah Anda yakin akan melakukan approval pencairan?");
      if(konfirmasi){
        $.ajax({
            url: "<?php echo site_url('api/drawdown_update/'); ?>" + id + "/" + new_status + "/",
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
              alert("Proses pencairan gagal.");
            }
        });
      }
    }
    request_list();
    </script>
  </body>
</html>
