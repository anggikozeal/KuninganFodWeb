
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
                  <th>Nominal Request</th>
                  <th>Saldo Tersedia</th>
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
                    if(response.content.drawdown[x].transaction.length > 0){
                      for(var i=0;i<response.content.drawdown[x].transaction.length;i++){
                        if(response.content.drawdown[x].transaction[i].detail.length > 0){
                          for(var j=0;j<response.content.drawdown[x].transaction[i].detail.length;j++){
                            if( parseFloat(response.content.drawdown[x].transaction[i].detail[j].discount) > 0){
                                var harga = parseFloat(response.content.drawdown[x].transaction[i].detail[j].price);
                                var diskon = parseFloat(response.content.drawdown[x].transaction[i].detail[j].discount);
                                var qty = parseFloat(response.content.drawdown[x].transaction[i].detail[j].qty);
                                var bayar = (harga - (diskon / 100)) * qty;
                                total_penjualan = total_penjualan + bayar;
                            }else{
                                var harga = parseFloat(response.content.drawdown[x].transaction[i].detail[j].price);
                                var qty = parseFloat(response.content.drawdown[x].transaction[i].detail[j].qty);
                                var bayar = harga * qty;
                                total_penjualan = total_penjualan + bayar;
                            }
                          }
                        }
                      }
                    }
                    var saldo_tersedia = total_penjualan - response.content.drawdown[x].recent_drawdown;
                    str += '<tr>';
                    str += '<td>' + no+ '</td>';
                    str += '<td>' + response.content.drawdown[x].shop.shop_name + '<br>' + response.content.drawdown[x].seller.full_name  + '</td>';
                    str += '<td>' + response.content.drawdown[x].total + '</td>';
                    str += '<td>' + saldo_tersedia + '</td>';
                    str += '<td>' + response.content.drawdown[x].total + '</td>';
                    str += '</tr>';
                    no ++;
                  }
                  $("#tbl").html(str);
              }else{
                str += '<tr>';
                str += '<td colspan="5">Tidak ada data</td>';
                str += '</tr>';

                $("#tbl").html(str);
              }
          },
          error : function(response){
          console.log(response);
              alert("error");
              str += '<tr>';
              str += '<td colspan="5">Error</td>';
              str += '</tr>';

              $("#tbl").html(str);
          },
      });
    };
    request_list();
    </script>
  </body>
</html>
