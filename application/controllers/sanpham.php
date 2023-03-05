<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sanpham extends CI_Controller {

	private $sosanphamtheodanhmuctrong1trang=6;
	private $sosanphamtrong1trang=9;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('sanpham_model');
		$this->load->model('danhmuc_model');
		$this->load->model('loginCustomer_model');
		$this->load->model('slide_model');
		
	}
	//hàm index thay cho hàm views_moinhat
	public function index()
	{
		//lấy danh sách slide
		$slide=$this->slide_model->laydulieuslide();
		$slide=$slide->result_array();
		$slide=array('slide'=>$slide);
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy các sản phẩm mới nhất
		$moinhat=$this->moinhat();
		//lấy các sản phẩm giảm giá
		$giamgia=$this->giamgia();
		//lấy các sản phẩm bán chạy
		$banchay=$this->banchay();
		$dulieu=array($moinhat,$dm,$giamgia,$banchay,$slide);
		$dulieu=array('dulieu'=>$dulieu);
			// echo "<pre>";
			// var_dump($dulieu);
			// echo "</pre>";
			// die();
		$this->load->view('trangchusanpham_views',$dulieu);
	}
	
	
	//hàm hiển thị form để thêm sản phẩm
	public function nhapdulieusanpham()
	{
	    $ketqua=$this->danhmuc_model->listIdDanhMuc();
		$ketqua=$ketqua->result_array();
		$ketqua=array('ketqua10'=>$ketqua);
		 	
		 $this->load->view('themsanpham_views',$ketqua);
	}
	//hàm thêm sản phẩm
	public function addsanpham()
	{
	    $tensanpham=$this->input->post('tensanpham');
	    $iddanhmuc=$this->input->post('iddanhmuc');
	    $trichdan=$this->input->post('trichdan');
	    $mota=$this->input->post('mota');
	    $loai=$this->input->post('loai');
	    $dongia=$this->input->post('dongia');
	    $img=$this->input->post('img');
	    if($this->sanpham_model->addsanpham($tensanpham,$iddanhmuc,$trichdan,$mota,$loai,$dongia,$img)){
	    	$this->load->view('thanhcongsanpham_views');
	    }
	}

	//hàm hiển thị sản phẩm theo số lượng chỉ định
	public function listsanpham($soluong)
	{
	    $ketqua=$this->sanpham_model->listsanpham($soluong);
	    $ketqua=$ketqua->result_array();
	    $ketqua=array('ketqua'=>$ketqua);

	    $this->load->view('danhsachsanpham_views', $ketqua, FALSE);
	}

	//hàm xóa sản phẩm theo id sản phẩm
	public function deletesanpham($idsanpham)
	{
	    if($this->sanpham_model->deletesanpham($idsanpham))
	    	$this->load->view('thanhcongsanpham_views');
	}

	//hàm lấy dữ liệu trước khi sửa theo id sản phẩm
	public function laydulieutruockhisua($idsanpham)
	{
		//lấy id và tên sản phẩm
		$dulieu=$this->danhmuc_model->listIdDanhMuc();
		$dulieu=$dulieu->result_array();
	    $dulieu=array('dulieu1'=>$dulieu);
	    //dữ liệu của bảng danh mục join với bảng sản phẩm
	    $ketqua=$this->sanpham_model->ketnoi2bang($idsanpham);
		$ketqua=$ketqua->result_array();
		$ketqua=array('dulieu2'=>$ketqua);
		 $arr=array($dulieu,$ketqua);
		    $arr=array("arr1"=>$arr);
		     
	    $this->load->view('suasanpham_views', $arr,FALSE);
	}
	//sửa sản phẩm
	public function edit()
	{
	    $idsanpham=$this->input->post('idsanpham');
	    $tensanpham=$this->input->post('tensanpham');
	    $iddanhmuc=$this->input->post('iddanhmuc');
	    $trichdan=$this->input->post('trichdan');
	    $mota=$this->input->post('mota');
	    $loai=$this->input->post('loai');
	    $dongia=$this->input->post('dongia');
	    $img_old=$this->input->post('img_old');
	    $img=$this->input->post('img');
	    if(empty($img)){
	    	$img=$img_old;
	    }
	    if($this->sanpham_model->edit($idsanpham,$tensanpham,$iddanhmuc,$trichdan,$mota,$loai,$dongia,$img)){
	    	$this->load->view('thanhcongsanpham_views');
	    }
	}

	//lấy sản phẩm theo loại mới nhất
	public function moinhat()
	{
	    $new=$this->sanpham_model->moinhat();
	    $new=$new->result_array();
	    $new=array("moinhat"=>$new);
	    return $new;
	}
	//lấy sản phẩm giảm giá
	public function giamgia()
	{
	    $giamgia=$this->sanpham_model->giamgia();
	    $giamgia=$giamgia->result_array();
	    $giamgia=array("giamgia"=>$giamgia);
	    return $giamgia;
	}
	//lấy sản phẩm bán chạy
	public function banchay()
	{
	    $banchay=$this->sanpham_model->banchay();
	    $banchay=$banchay->result_array();
	    $banchay=array("banchay"=>$banchay);
	    return $banchay;
	}


	//sản phẩm theo id danh mục
	public function sanphamtheoiddanhmuc($iddanhmuc)
	{
		$this->session->set_userdata('iddanhmuc',$iddanhmuc);

		//lấy số trang hiện sản phẩm theo từng danh mục
		$sotrang=$this->sanpham_model->sotrangtheodanhmuc($this->sosanphamtheodanhmuctrong1trang,$iddanhmuc);
		
		$sotrang=array('sotrang'=>$sotrang);
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);
	    $dulieu=$this->sanpham_model->sanphamtheoiddanhmuc($this->sosanphamtheodanhmuctrong1trang,$iddanhmuc);
	    $dulieu=$dulieu->result_array();
	    $dulieu=array('sanphamtheoiddanhmuc'=>$dulieu);
	    $arr=array($dm,$dulieu,$sotrang);
	    $arr=array('dulieu'=>$arr);

		$this->load->view('sanphamtheodanhmuc_views',$arr);
		
	}
	//tính vị trí để hiển thị sản phẩm theo danh mục
	public function page($trangthumay)
	{
		//lấy ra số sản phẩm theo vị trí
	    $dulieu=$this->sanpham_model->page($this->sosanphamtheodanhmuctrong1trang,$trangthumay,$this->session->userdata('iddanhmuc'));
	    $dulieu=$dulieu->result_array();
	    $dulieu=array('sanphamtheoiddanhmuc'=>$dulieu);
	    //lấy số trang hiện sản phẩm theo từng danh mục
		$sotrang=$this->sanpham_model->sotrangtheodanhmuc($this->sosanphamtheodanhmuctrong1trang,$this->session->userdata('iddanhmuc'));
		$sotrang=array('sotrang'=>$sotrang);
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		 $arr=array($dm,$dulieu,$sotrang);
		 $arr=array('dulieu'=>$arr);
		 $this->load->view('sanphamtheodanhmuc_views',$arr);

	}
	//tính vị trí để hiển thị tất cả sản phẩm
	public function page1($trangthumay)
	{

		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//tính số trang để hiển thị hết số sản phẩm trong bảng sản phẩm
		$sotrang=$this->sanpham_model->sotrangsanpham($this->sosanphamtrong1trang);
	   	$sotrang=array('sotrang'=>$sotrang);
	

		//lấy sản phẩm theo vị trí
	    $dulieu=$this->sanpham_model->page1($this->sosanphamtrong1trang,$trangthumay);
	  
	    $dulieu=$dulieu->result_array();
	    $dulieu=array('tatca'=>$dulieu);

	    $arr=array($dm,$dulieu,$sotrang);
	    $arr=array('dulieu'=>$arr);

	    
	    $this->load->view('menutatcasanpham_views', $arr, FALSE);
	}

	//tất cả sản phẩm để đưa ra giao diện sản phẩm mặc định là 2 sản phẩm với vị trí đầu tiên là 0
	public function tatcasanpham()
	{
		$gia=$this->input->post('submit_gia');
		$tieuchi=$this->input->get('tieuchi');
		
		
		//ban đầu nếu chưa chọn phân loại và sắp xếp thì hiển thị mặc định
		if(empty($gia)&&empty($tieuchi)){
			

		$this->session->unset_userdata('giatriphanloai');
		$this->session->unset_userdata('giatrisapxep');
		//lưu số lượng sản phẩm trong bảng sản phẩm vào session để hiển thị thống kê cho views
		$dulieu=$this->sanpham_model->soluongsanphamtrongbangsanpham();
	    $dulieu=$dulieu->result();
	    $this->session->set_userdata('tongsanpham',count($dulieu));
	    $this->session->set_userdata('vitrisanpham',0);

		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);
		//lấy tất cả sản phẩm
	    $tatcasanpham=$this->sanpham_model->dulieubandau($this->sosanphamtrong1trang);
	    $tatcasanpham=$tatcasanpham->result_array();
	    $tatcasanpham=array('tatca'=>$tatcasanpham);
	   
	   //tính số trang để hiển thị hết số sản phẩm trong bảng sản phẩm
		$sotrang=$this->sanpham_model->sotrangsanpham($this->sosanphamtrong1trang);
	   	$sotrang=array('sotrang'=>$sotrang);

	    $arr=array($dm,$tatcasanpham,$sotrang);
	    $arr=array('dulieu'=>$arr);

	    $this->load->view('menutatcasanpham_views', $arr, FALSE);
		}
		if(!empty($gia)&&empty($tieuchi)){
			
			//lấy dữ liệu theo phân loại
			$phanloai=$this->input->post('phanloai');

			$this->session->set_userdata('giatriphanloai',$phanloai);
			
			$dulieuphanloai=$this->sanpham_model->phanloaigia($phanloai);
			$dulieuphanloai=$dulieuphanloai->result_array();
			//lưu số lượng sản phẩm trong bảng sản phẩm vào session để hiển thị thống kê cho views
			$this->session->set_userdata('vitrisanpham',0);
			$this->session->set_userdata('tongsanpham',count($dulieuphanloai));

			//lấy danh sách danh mục 
			$dm=$this->danhmuc_model->listIdDanhMuc();
			$dm=$dm->result_array();
			$dm=array('danhmuc'=>$dm);

			//lấy tất cả sản phẩm
	    	$tatcasanpham=$this->sanpham_model->dulieubandau($this->sosanphamtrong1trang);
	    	$tatcasanpham=$tatcasanpham->result_array();
	    	$tatcasanpham=array('tatca'=>$tatcasanpham);
	    		
	    	//tính số trang để hiển thị hết số sản phẩm trong bảng sản phẩm
			$sotrang=$this->sanpham_model->sotrangsanpham($this->sosanphamtrong1trang);
		   	$sotrang=array('sotrang'=>$sotrang);
		   
		   	$arr=array($dm,$tatcasanpham,$sotrang);
		    $arr=array('dulieu'=>$arr);

		    $this->load->view('menutatcasanpham_views', $arr, FALSE);
		}
		if(isset($tieuchi)&&!isset($gia)){
			
			$this->session->set_userdata('giatrisapxep',$tieuchi);
			$dulieusapxep=$this->sanpham_model->sapxeptheotieuchi($tieuchi);
			$dulieusapxep=$dulieusapxep->result_array();

			//lưu số lượng sản phẩm trong bảng sản phẩm vào session để hiển thị thống kê cho views
			$this->session->set_userdata('vitrisanpham',0);
			$this->session->set_userdata('tongsanpham',count($dulieusapxep));

			//lấy danh sách danh mục 
			$dm=$this->danhmuc_model->listIdDanhMuc();
			$dm=$dm->result_array();
			$dm=array('danhmuc'=>$dm);


			//lấy tất cả sản phẩm
	    	$tatcasanpham=$this->sanpham_model->dulieubandau($this->sosanphamtrong1trang);
	    	$tatcasanpham=$tatcasanpham->result_array();
	    	$tatcasanpham=array('tatca'=>$tatcasanpham);

	    	//tính số trang để hiển thị hết số sản phẩm trong bảng sản phẩm
			$sotrang=$this->sanpham_model->sotrangsanpham($this->sosanphamtrong1trang);
		   	$sotrang=array('sotrang'=>$sotrang);

		   	//$arr=array($dm,$tatcasanpham,$sotrang);
		   	$arr=array();
		   	array_push($arr,$dm,$tatcasanpham,$sotrang);
		    $arr=array('dulieu'=>$arr);

		    $this->load->view('menutatcasanpham_views', $arr, FALSE);
		}

		if(isset($gia)&&isset($tieuchi)){
			
			//lấy dữ liệu theo phân loại
			$phanloai=$this->input->post('phanloai');
			$this->session->set_userdata('giatrisapxep',$tieuchi);
			$this->session->set_userdata('giatriphanloai',$phanloai);
			
			$dulieuphanloai=$this->sanpham_model->phanloaigia($phanloai);
			$dulieuphanloai=$dulieuphanloai->result_array();
			//lưu số lượng sản phẩm trong bảng sản phẩm vào session để hiển thị thống kê cho views
			$this->session->set_userdata('vitrisanpham',0);
			$this->session->set_userdata('tongsanpham',count($dulieuphanloai));

			//lấy danh sách danh mục 
			$dm=$this->danhmuc_model->listIdDanhMuc();
			$dm=$dm->result_array();
			$dm=array('danhmuc'=>$dm);

			//lấy tất cả sản phẩm
	    	$tatcasanpham=$this->sanpham_model->dulieubandau($this->sosanphamtrong1trang);
	    	$tatcasanpham=$tatcasanpham->result_array();
	    	$tatcasanpham=array('tatca'=>$tatcasanpham);
	    		
	    	//tính số trang để hiển thị hết số sản phẩm trong bảng sản phẩm
			$sotrang=$this->sanpham_model->sotrangsanpham($this->sosanphamtrong1trang);
		   	$sotrang=array('sotrang'=>$sotrang);
		   
		   	$arr=array($dm,$tatcasanpham,$sotrang);
		    $arr=array('dulieu'=>$arr);

		    $this->load->view('menutatcasanpham_views', $arr, FALSE);
		}
	}
	
	//hàm chi tiết sản phẩm
	public function chitietsanpham($idsp)
	{
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);
		//thông tin chi tiết của sản phẩm hiện tại
	    $dulieu=$this->sanpham_model->chitietsanpham($idsp);
	    $dulieu=$dulieu->result_array();
	    $dulieu=array('chitiet'=>$dulieu);
	
	    //thông tin về các sản phẩm tương tự
	    $dulieukhac=$this->sanpham_model->sanphamlienquan($idsp);
	    $dulieukhac=$dulieukhac->result_array();
	    $dulieukhac=array('dulieukhac'=>$dulieukhac);
	    $arr=array($dulieu,$dulieukhac,$dm);
	    $arr=array('tatca'=>$arr);
	    
	    // echo "<pre>";
	    // var_dump($arr);
	    // echo "</pre>"
	    $this->load->view('chitietsanpham_views', $arr, FALSE);
	}
	//hàm giỏ hàng
	public function giohang()
	{
	    $id=$this->input->post('id');
	    $soluong=$this->input->post('soluong');
	    $this->session->set_userdata("count",$soluong);
	    $this->sanpham_model->giohang($id,$soluong);
	}
	public function laydulieugiohang()
	{

		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy dữ liệu của bảng giỏ hàng
		$dulieu=$this->sanpham_model->laydulieugiohang();
		$dulieu=$dulieu->result_array();
		$dulieu=array('dulieu'=>$dulieu);
		$arr=array($dm,$dulieu);
		$arr=array('tatca'=>$arr);
		$this->load->view('giohang_views',$arr);
		
	}
	//viết hàm cập nhật số lượng sản phẩm đã có trong giỏ hàng
	public function update_soluong()
	{
		//lấy mảng id sản phẩm từ thuộc tính name="idsanpham[]" trong form giohang_views,biến idsanpham[] chính là 1 mảng lưu các giá trị id của các sản phẩm
		$array_idsanpham=$this->input->post('idsanpham');
	    //lấy mảng số lượng cần update,vì biến thuộc tính name="soluongmoi[]" trong form giohang_views là 1 mảng lưu các giá trị số lượng của các sản phẩm
	    $array_soluongmoi=$this->input->post('soluongmoi');

	    $dulieu=array();
	    for($i=0;$i<count($array_idsanpham);$i++){
	    	$arr=array();
	    	$arr['idproduct']=$array_idsanpham[$i];
	    	$arr['amount']=$array_soluongmoi[$i];

	    	array_push($dulieu,$arr);
	    }
	    
	    //truyền sang model để cập nhật cho bảng giỏ hàng trong database
	    $this->sanpham_model->update_soluong($dulieu);

	    //sau khi cập nhật bảng giỏ hàng thì gọi lại giohang_views
	    $this->laydulieugiohang();
	}

	//hàm xóa sản phẩm trong giỏ hàng
	public function xoasanphamtronggiohang($id)
	{
	    if($this->sanpham_model->xoasanphamtronggiohang($id)){
	    	 $this->laydulieugiohang();
	    }
	}
	//hàm tìm kiếm sản phẩm
	public function timkiemsanpham()
	{
	    $timkiemsanphamtheoten=$this->input->post('timkiemsanphamtheoten');
	   

	    //lấy dữ liệu tìm kiếm bên model
	    $n=$this->sanpham_model->timkiemsanpham($timkiemsanphamtheoten);
	    //$row=mysqli_fetch_assoc($n) trả về từng hàng dữ liệu và biến $row sẽ nhận hàng dữ liệu đó
	    $array=array();
	    while($row=mysqli_fetch_assoc($n)){
	    	//thêm các $row vào mảng $array thì các $row sẽ là các phần tử của mảng
	    	array_push($array,$row);			
	    }
	    $array=array('ketqua'=>$array);

	    //lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		$dulieu=array($dm,$array);
		$dulieu=array('dulieu'=>$dulieu);
	    	
	    	$this->load->view('hienthicacsanphamtimkiem_views', $dulieu, FALSE);
	   
	}
	
	//hàm thanh toán offline
	public function thanhtoanoffline()
	{
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy các đơn hàng trong bảng giỏ hàng
		$dulieu=$this->sanpham_model->laydulieugiohang();
		$dulieu=$dulieu->result_array();
		$dulieu=array('giohang'=>$dulieu);
		
		

		//vì khi đăng nhập thành công đã lấy được id của khách hàng đó và lưu trong session nên từ id này sẽ lấy dữ liệu của khách hàng để đưa ra hồ sơ khách hàng
		//lấy dữ liệu về khách hàng
		$hoso=$this->loginCustomer_model->hoso($this->session->userdata('idhosokhachhang'));
		$hoso=$hoso->result_array();
		$hoso=array('hoso'=>$hoso);

		$array=array($dm,$dulieu,$hoso);
		$array=array('dulieu'=>$array);
				
	    $this->load->view('thanhtoanoffline_views',$array);
	}
	//hàm đã thanh toán offline thành công và lưu dữ liệu vào bảng đặt hàng
	public function thanhtoan1()
	{
		
		//biến idsanpham là 1 mảng lưu các đơn hàng trong bảng giỏ hàng
	    $array_idsanpham=$this->input->post('idsanpham');
	    
	    //lưu tất cả các sản phẩm trong giỏ hàng vào bảng đặt hàng và cả id khách hàng đã thanh toán những sản phẩm đó mục đích là để phân biệt những sản phẩm nào được đặt bởi khách hàng nào
	    $this->sanpham_model->thanhtoan1($array_idsanpham,$this->session->userdata('idhosokhachhang'));

		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy các đơn hàng trong bảng đặt hàng
		$dulieu=$this->sanpham_model->laydulieudathang($this->session->userdata('idhosokhachhang'));
		$dulieu=$dulieu->result_array();
		$dulieu=array('dathang'=>$dulieu);

	    $array=array($dm,$dulieu);
		$array=array('dulieu'=>$array);
			
	    $this->load->view('thanhtoan1_views', $array, FALSE);
	}

	//hàm cho khách hàng xác nhận đã nhận đơn hàng đã đặt 
	public function xacnhandonhangchokhachhang($idsanpham,$iddathang)
	{

	    if($this->sanpham_model->xacnhandonhangchokhachhang($idsanpham,$iddathang)){
	    	//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy các đơn hàng trong bảng đặt hàng
		$dulieu=$this->sanpham_model->laydulieudathang($this->session->userdata('idhosokhachhang'));
		$dulieu=$dulieu->result_array();
		$dulieu=array('dathang'=>$dulieu);

	    $array=array($dm,$dulieu);
		$array=array('dulieu'=>$array);
			
	    $this->load->view('thanhtoan1_views', $array, FALSE);
	    }
	}

	//hàm cho menu giới thiệu
	public function gioithieu()
	{
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);
		// echo "<pre>";
		// var_dump($dm);
		// echo "</pre>";
	    $this->load->view('gioithieu_views',$dm);
	}
	//hàm lấy dữ liệu blog
	public function blog()
	{
	    //lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy dữ liệu bài viết
		$dulieu=$this->sanpham_model->listBlog();
		$dulieu=$dulieu->result_array();
		$dulieu=array("blog"=>$dulieu);
		$arr=array($dm,$dulieu);
		$arr=array("data"=>$arr);
		// echo "<pre>";
		// var_dump($arr);
		// echo "</pre>";
		$this->load->view('blog_views',$arr);
	}

	//code chi tiết blog
	public function chitietblog($id)
	{
		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		//lấy dữ liệu bài viết
		$dulieu=$this->sanpham_model->listBlog();
		$dulieu=$dulieu->result_array();
		$dulieu=array("blog"=>$dulieu);

		//lấy dữ liệu chi tiết blog
	    $chitiet=$this->sanpham_model->chitietblog($id);
	    $chitiet=$chitiet->result_array();
		$chitiet=array("chitiet"=>$chitiet);
		$arr=array($dm,$dulieu,$chitiet);
		$arr=array("data"=>$arr);
		// echo "<pre>";
		// var_dump($arr);
		// echo "</pre>";
		$this->load->view('chitietblog_views',$arr);
	}

}

/* End of file sanpham.php */
/* Location: ./application/controllers/sanpham.php */