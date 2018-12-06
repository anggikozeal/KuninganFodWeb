<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mod_user');
		$this->load->model('mod_shop');
		$this->load->model('mod_product');
		$this->load->model('mod_transaction');
		$this->load->model('mod_notification');
		$this->load->model('mod_review');
		header('Content-type:JSON');
	}

	public function index(){
		$this->load->view('welcome_message');
	}

	//--------------------------------USER-----------------------------
	public function verifikasi(){ 
		if($this->input->post('username') == null && $this->input->post('password') == null){
			$login = file_get_contents('php://input');
			$json = json_decode($login);
			if($json == null){
				$severity = "warning";
				$username = null;
				$password = null;
				$message = "Tidak ada data dikirim ke server";
				$content = array("user" => array());
			}else{
				$username = $json->username;
				$password = $json->password;
			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
		}
		if($username != null && $password != null ){
			$check = $this->mod_user->is_registered($username);
			if(sizeof($check) > 0){
				if($check[0]->password == md5($password)){
					$shop = $this->mod_shop->shop_detail(array("id_user" => $check[0]->id));
					if(sizeof($shop) > 0){
						$shop[0]->user = $check;
					}
					$severity = "success";
					$message = "Login berhasil";
					$content = array("user" => $check, "shop" => $shop);
				}else{
					$severity = "warning";
					$message = "Nama pengguna dan kata sandi tidak sesuai";
					$content = array("user" => array(), "shop" => array());
				}
			}else{
				$severity = "danger";
				$message = "Nama pengguna tidak terdaftar";
				$content = array("user" => array(), "shop" => array());
			}
		}else{
			$severity = "warning";
			$message = "Tidak ada data dikirim ke server";
			$content = array("user" => array(), "shop" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	
	function verifikasi_register(){
		if($this->input->post('username') == null && 
		$this->input->post('password') == null && 
		$this->input->post('full_name') == null &&  
		$this->input->post('address') == null && 
		$this->input->post('phone') == null){
			$login = file_get_contents('php://input');
			$json = json_decode($login);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$content_count = "0";
				$content = array();
				$username = null;
				$password = null;
				$full_name = null;
				$address = null;
				$phone = null;
			}else{
				$username = $json->username;
				$password = $json->password;
				$full_name = $json->full_name;
				$address = $json->address;
				$phone = $json->phone;
			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$full_name = $this->input->post('full_name');
			$address = $this->input->post('address');
			$phone = $this->input->post('phone');
		}

		if($username != null && $password != null && $full_name != null && $address != null && $phone != null){
			$check = $this->mod_user->is_registered($username);
			if(sizeof($check) > 0){
				$severity = "danger";
				$message = "Nama pengguna sudah digunakan";
				$content = array();
			}else{
				$content = array(
					"username" => $username,
					"password" => md5($password),
					"full_name" => $full_name,
					"address" => $address,
					"phone" => $phone,
					"last_login" => date("Y-m-d H:i:s"));
				$resultcek = $this->mod_user->register($content);
				if($resultcek > 0){
					$severity = "success";
					$message = "Registrasi berhasil";
					$content = array();
				}else{
					$severity = "danger";
					$message = "Registrasi gagal. Silakan coba lagi";
					$content = array();
				}
			}
		}else{
			$severity = "warning";
			$message = "Tidak ada data dikirim ke server";
			$content = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function user_detail($key, $val){
		$data = $this->mod_user->user_detail(array(
			$key => $val 
		));
		if(sizeof($data) > 0){
			$shop = $this->mod_shop->shop_detail(array("id_user" => $data[0]->id));
			if(sizeof($shop) > 0){
				$shop[0]->user = $data;
			}
			$severity = "success";
			$message = "Load user berhasil";
			$content = array("user" => $data, "shop" => $shop);
		}else{
			$severity = "warning";
			$message = "No data";
			$content = array("user" => array(), "shop" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------ END OF USER ------------------------------------
	


	// ------------------------ PRODUCT ------------------------------------
	public function product_latest($limit){
		$data = $this->mod_product->product_latest($limit);
		if(sizeof($data) > 0){
			for($x=0;$x<sizeof($data);$x++){
				$shop = $this->mod_shop->shop_detail(array("id" => $data[$x]->id_shop));
				$user = $this->mod_user->user_detail(array("id" => $shop[0]->id_user));
				$data[$x]->shop = $shop[0];
				$data[$x]->shop->user = $user[0];
			}
			$severity = "success";
			$message = "Latest product";
			$content = array("product" => $data);
		}else{
			$severity = "success";
			$message = "No data";
			$content = array("product" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function product_detail($key, $val){
		$data = $this->mod_product->product_detail(array(
			$key => $val 
		));
		if(sizeof($data) > 0){
			$shop = $this->mod_shop->shop_detail(array("id" => $data[0]->id_shop));
			$user = $this->mod_user->user_detail(array("id" => $shop[0]->id_user));
			$data[0]->shop = $shop[0];
			$data[0]->shop->user = $user[0];
			$severity = "success";
			$message = "Product detail";
			$content = array("product" => $data);
		}else{
			$severity = "success";
			$message = "No data";
			$content = array("product" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function product_insert(){
		// $id_shop,$name,$category(1/2), $status, $price, discunt, description, rating, image
		if($this->input->post('id_shop') == null && 
		$this->input->post('name') == null && 
		$this->input->post('category') == null &&
		$this->input->post('status') == null && 
		$this->input->post('price') == null &&
		$this->input->post('discount') == null && 
		$this->input->post('description') == null &&
		$this->input->post('rating') == null && 
		$this->input->post('image') == null
		){
			$inp = file_get_contents('php://input');
			$json = json_decode($inp);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$content = array();
				$id_shop = null;
				$name = null;
				$category = null;
				$status = null;
				$price = null;
				$discount = null;
				$description = null;
				$rating = null;
				$image = null;
			}else{
				$id_shop = $json->id_shop;
				$name = $json->name;
				$category = $json->category;
				$status = $json->status;
				$price = $json->price;
				$discount = $json->discount;
				$description = $json->description;
				$rating = $json->rating;
				$image = $json->image;
			}
		}else{
			$id_shop = $this->input->post('id_shop');
			$name = $this->input->post('name');
			$category = $this->input->post('category');
			$status = $this->input->post('status');
			$price = $this->input->post('price');
			$discount = $this->input->post('discount');
			$description = $this->input->post('description');
			$rating = $this->input->post('rating');
			$image = $this->input->post('image');
		}

		if($id_shop != null && $name != null && $category != null && 
		$status != null && $price != null && $discount != null && 
		$description != null && $rating != null && $image != null){
			$product_insert = $this->mod_product->product_insert(
				array(
					"id_shop" => $id_shop,
					"name" => $name,
					"category" => $category,
					"status" => $status,
					"price" => $price,
					"discount" => $discount,
					"description" => $description,
					"rating" => $rating,
					"image" => $image
				)
			);
			if($product_insert > 0){
				$severity = "success";
				$message = "Simpan product berhasil";
				$content = array();
			}else{
				$severity = "warning";
				$message = "Simpan product gagal. Silakan coba lagi";
				$content = array();
			}
		}else{
			$severity = "danger";
			$message = "Tidak ada data dikirim ke server";
			$content = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------ END OF PRODUCT ------------------------------------
	

	// ------------------------ NOTIFICATION ------------------------------------
	function notification_insert($id_user,$title,$message){
		$insert = array(
			"id_user" => $id_user,
			"title" => $title,
			"message" => $message,
			"datetime" => date("Y-m-d H:i:s")
		);
		$this->mod_notification->notification_insert($insert);
	}

	public function notification_by_user($id){
		$data = $this->mod_notification->notification_by_user(array(
			"id" => $id
		));
		if(sizeof($data) > 0){
			$severity = "success";
			$message = "Notification";
			$content = array("notification" => $data);
		}else{
			$severity = "success";
			$message = "No data";
			$content = array("notification" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function notification_detail($key, $val){
		$data = $this->mod_notification->notification_detail(array(
			$key => $val 
		));
		if(sizeof($data) > 0){
			$severity = "success";
			$message = "Notification";
			$content = array("notification" => $data);
		}else{
			$severity = "success";
			$message = "No data";
			$content = array("notification" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------ END OF NOTIFICATION ------------------------------------
	

	// ------------------------ TRANSACTION ------------------------------------
	function transaction_insert(){
		if($this->input->post('id_shop') == null && 
		$this->input->post('id_user') == null && 
		$this->input->post('id_product') == null &&
		$this->input->post('qty') == null && 
		$this->input->post('price') == null &&
		$this->input->post('discount') == null ){
			$inp = file_get_contents('php://input');
			$json = json_decode($inp);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$content = array();
				$id_shop = null;
				$id_user = null;
				$id_product = null;
				$qty = null;
				$price = null;
				$discount = null;
				$note = null;
			}else{
				$id_shop = $json->id_shop;
				$id_user= $json->id_user;
				$id_product = $json->id_product;
				$qty = $json->qty;
				$price = $json->price;
				$discount = $json->discount;
				$note = $json->note;
			}
		}else{
			$id_shop = $this->input->post('id_shop');
			$id_user = $this->input->post('id_user');
			$id_product = $this->input->post('id_product');
			$qty = $this->input->post('qty');
			$price = $this->input->post('price');
			$discount = $this->input->post('discount');
			$note = $this->input->post('note');
		}
		if($id_shop != null && $id_user != null && $id_product != null && 
		$qty != null && $price != null && $discount != null){
			$cek_on_keranjang = $this->mod_transaction->transaction_on_keranjang($id_shop, $id_user);
			if(sizeof($cek_on_keranjang) > 0){
				$cek_detail = $this->mod_transaction->transaction_detail_on_keranjang(
					$cek_on_keranjang[0]->id, 
					$id_product
				);
				if(sizeof($cek_detail) > 0){
					$new_qty = $cek_detail[0]->qty + $qty;
					$transaction_detail_update = $this->mod_transaction->transaction_detail_update(
						$cek_on_keranjang[0]->id,
						array(
							"qty" => $new_qty
						)
					);
					if($transaction_detail_update > 0){
						$notification_insert = $this->mod_notification->notification_insert(
							array(
								"id_user" => $id_user,
								"title" => "Transaksi pembelian ",
								"message" => "Anda baru saja menambahkan produk ke keranjang",
								"datetime" => date("Y-m-d H:i:s")
							)
						);
						$severity = "success";
						$message = "Produk ditambahkan ke keranjang";
						$content = array("notification" => array());
					}else{
						$severity = "warning";
						$message = "Gagal ditambahkan ke keranjang c";
						$content = array("notification" => array());
					}
				}else{
					$transaction_detail_insert = $this->mod_transaction->transaction_detail_insert(
						array(
							"id_transaction" => $cek_on_keranjang[0]->id,
							"id_product" => $id_product,
							"qty" => $qty,
							"price" => $price,
							"discount" => $discount,
							"note" => $note
						)
					);
					if($transaction_detail_insert > 0){
						$notification_insert = $this->mod_notification->notification_insert(
							array(
								"id_user" => $id_user,
								"title" => "Transaksi pembelian ",
								"message" => "Anda baru saja menambahkan produk ke keranjang",
								"datetime" => date("Y-m-d H:i:s")
							)
						);
						$severity = "success";
						$message = "Produk ditambahkan ke keranjang";
						$content = array("notification" => array());
					}else{
						$severity = "warning";
						$message = "Gagal ditambahkan ke keranjang";
						$content = array("notification" => array());
					}
				}
			}else{
				$transaction_insert = $this->mod_transaction->transaction_insert(
					array(
						"id_shop" => $id_shop,
						"id_user" => $id_user,
						"status" => "ON_KERANJANG",
						"image" => " ",
						"datetime" => date("Y-m-d H:i:s")
					)
				);
				if($transaction_insert > 0){
					$cek_on_keranjang = $this->mod_transaction->transaction_on_keranjang($id_shop, $id_user);
					$transaction_detail_insert = $this->mod_transaction->transaction_detail_insert(
						array(
							"id_transaction" => $cek_on_keranjang[0]->id,
							"id_product" => $id_product,
							"qty" => $qty,
							"price" => $price,
							"discount" => $discount,
							"note" => $note
						)
					);
					if($transaction_detail_insert > 0){
						$notification_insert = $this->mod_notification->notification_insert(
							array(
								"id_user" => $id_user,
								"title" => "Transaksi pembelian ",
								"message" => "Anda baru saja menambahkan produk ke keranjang",
								"datetime" => date("Y-m-d H:i:s")
							)
						);
						$severity = "success";
						$message = "Produk ditambahkan ke keranjang";
						$content = array("notification" => array());
					}else{
						$severity = "warning";
						$message = "Gagal ditambahkan ke keranjang";
						$content = array("notification" => array());
					}
				}else{
					$severity = "warning";
					$message = "Gagal ditambahkan ke keranjang";
					$content = array("notification" => array());
				}
			}
		}else{
			$severity = "danger";
			$message = "Tidak ada data dikirim ke server";
			$content = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}


	public function transaction($key, $val, $status){
		$data = $this->mod_transaction->transaction(array(
			$key => $val,
			"status" => $status 
		));
		
		if(sizeof($data) > 0){
			for($z=0;$z<sizeof($data);$z++){
				$shop = $this->mod_shop->shop_detail(array("id" => $data[$z]->id_shop));
				$user_buyer = $this->mod_user->user_detail(array("id" => $data[$z]->id_user));
				$user = $this->mod_user->user_detail(array("id" => $shop[0]->id_user));
				$products = $this->mod_transaction->transaction_detail_list($data[$z]->id);
				$data[$z]->shop = $shop[0];
				$data[$z]->buyer = $user_buyer[0];
				$data[$z]->shop->user = $user[0];
				$data[$z]->product = $products;
			}
			
			$severity = "success";
			$message = "Product detail";
			$content = array("transaction" => $data);
		}else{
			$severity = "success";
			$message = "No data";
			$content = array("transaction" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function transaction_update_status($id_transaction,$new_status){
		if($id_transaction != null && $new_status != null){
			$transaction_update = $this->mod_transaction->transaction_update_status($id_transaction,array(
				"status" => $new_status
			));
			if($transaction_update > 0){
				$severity = "success";
				$message = "Update status transaksi berhasil";
				$content = array("transaction" => array());
			}else{
				$severity = "warning";
				$message = "Update status transaksi gagal";
				$content = array("transaction" => array());
			}
		}else{
			$severity = "danger";
			$message = "Tidak ada data dikirim ke server";
			$content = array("transaction" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}


	public function transaction_upload_bukti(){ 
		if($this->input->post('id') == null && $this->input->post('image') == null  && $this->input->post('status') == null){
			$bukti = file_get_contents('php://input');
			$json = json_decode($bukti);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$content = array("transaction" => array());
			}else{
				$id = $json->id;
				$image = $json->image;
				$status = $json->status;
			}
		}else{
			$id = $this->input->post('id');
			$image = $this->input->post('image');
			$status = $this->input->post('status');
		}
		if($id != null && $image != null && $status != null ){
			$transaction_update = $this->mod_transaction->transaction_update_status($id,array(
				"image" => $image,
				"status" => $status
			));
			if($transaction_update > 0){
				$severity = "success";
				$message = "Update status transaksi berhasil";
				$content = array("transaction" => array());
			}else{
				$severity = "warning";
				$message = "Update status transaksi gagal";
				$content = array("transaction" => array());
			}
		}else{
			$severity = "warning";
			$message = "Tidak ada data dikirim ke server";
			$content = array("user" => array(), "shop" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------END OF TRANSACTION ------------------------------------
	

	// ------------------------ SHOP ------------------------------------
	public function shop_insert(){
		// $id_user,$shop_name,$address
		if($this->input->post('id_user') == null && 
		$this->input->post('shop_name') == null && 
		$this->input->post('address') == null){
			$login = file_get_contents('php://input');
			$json = json_decode($login);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$content = array();
				$id_user = null;
				$shop_name = null;
				$address = null;
			}else{
				$id_user = $json->id_user;
				$shop_name = $json->shop_name;
				$address = $json->address;
			}
		}else{
			$id_user = $this->input->post('id_user');
			$shop_name = $this->input->post('shop_name');
			$address = $this->input->post('address');
		}

		if($id_user != null && $shop_name != null && $address != null){
			$shop_insert = $this->mod_shop->shop_insert(
				array(
					"id_user" => $id_user,
					"shop_name" => $shop_name,
					"address" => $address
				)
			);
			if($shop_insert > 0){
				$data = $this->mod_user->user_detail(array(
					"id" => $id_user 
				));
				if(sizeof($data) > 0){
					$shop = $this->mod_shop->shop_detail(array("id_user" => $data[0]->id));
					if(sizeof($shop) > 0){
						$shop[0]->user = $data;
					}
					$severity = "success";
					$message = "Simpan data berhasil";
					$content = array("user" => $data, "shop" => $shop);
				}
			}else{
				$severity = "warning";
				$message = "Mendaftarkan toko gagal. Silakan coba lagi";
				$content = array("user" => array(), "shop" => array());
			}
		}else{
			$severity = "danger";
			$message = "Tidak ada data dikirim ke server";
			$content = array("user" => array(), "shop" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------END OF SHOP ------------------------------------


	// ------------------------ REVIEW ------------------------------------
	public function review_insert(){
		if($this->input->post('id_product') == null && 
		$this->input->post('id_user') == null && 
		$this->input->post('rating') == null && 
		$this->input->post('review') == null){
			$rev = file_get_contents('php://input');
			$json = json_decode($rev);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$content = array();
				$id_product = null;
				$id_user = null;
				$rating = null;
				$review = null;
			}else{
				$id_product = $json->id_product;
				$id_user = $json->id_user;
				$rating = $json->rating;
				$review = $json->review;
			}
		}else{
			$id_product = $this->input->post('id_product');
			$id_user = $this->input->post('id_user');
			$rating = $this->input->post('rating');
			$review = $this->input->post('review');
		}

		if($id_product != null && $id_user != null && $rating != null && $review != null){
			$review_insert = $this->mod_review->review_insert(
				array(
					"id_product" => $id_product,
					"id_user" => $id_user,
					"rating" => $rating,
					"review" => $review
				)
			);
			if($review_insert > 0){
				$severity = "success";
				$message = "Simpan data review berhasil";
				$content = array();
			}else{
				$severity = "warning";
				$message = "Simpan data review gagal. Silakan coba lagi";
				$content = array();
			}
		}else{
			$severity = "danger";
			$message = "Tidak ada data dikirim ke server";
			$content = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function review($key, $val){
		$data = $this->mod_review->review(array(
			$key => $val,
		));
		
		if(sizeof($data) > 0){
			for($z=0;$z<sizeof($data);$z++){
				$user = $this->mod_user->user_detail(array("id" => $data[$z]->id_user));
				$product = $this->mod_product->product_detail(array("id" => $data[$z]->id_product));
				$data[$z]->user = $user[0];
				$data[$z]->product = $product[0];
			}
			$severity = "success";
			$message = "Review";
			$content = array("review" => $data);
		}else{
			$severity = "success";
			$message = "No data";
			$content = array("review" => array());
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"content" => $content
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------END OF REVIEW ------------------------------------
	
}
