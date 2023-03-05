<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sanpham_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}
	//hàm thêm sản phẩm
	public function addsanpham($tensanpham,$iddanhmuc,$trichdan,$mota,$loai,$dongia,$img)
	{
	    $arr=array(
	    	'nameProduct'=>$tensanpham,
	    	'idcategory'=>$iddanhmuc,
	    	'subcontent'=>$trichdan,
	    	'content'=>$mota,
	    	'type'=>$loai,
	    	'price'=>$dongia,
	    	'img'=>$img
	    );
	    $this->db->insert('product', $arr);
	    return $this->db->insert_id();
	}

	//hàm hiển thị sản phẩm theo số lượng chỉ định
	public function listsanpham($soluong)
	{
	    $this->db->select('*');
	    $this->db->join('category', 'product.idcategory = category.id');
	    return $this->db->get('product',$soluong,0);
	}

	//hàm xóa sản phẩm theo id
	public function deletesanpham($idsanpham)
	{
	    $this->db->where('idproduct', $idsanpham);
	    return $this->db->delete('product');
	}
	

	//hàm join 2 bảng sản phẩm và danh mục 
	public function ketnoi2bang($idsanpham)
	{
	    $this->db->select('*');
	    $this->db->from('product');
	    $this->db->join('category', 'product.idcategory = category.id');
	    $this->db->where('product.idproduct', $idsanpham);
	    return $this->db->get();
	}
	//hàm sửa dữ liệu
	public function edit($idsanpham,$tensanpham,$iddanhmuc,$trichdan,$mota,$loai,$dongia,$img)
	{
	    $this->db->where('idproduct', $idsanpham);
	    $arr=array(
	    	'nameProduct'=>$tensanpham,
	    	'idcategory'=>$iddanhmuc,
	    	'subcontent'=>$trichdan,
	    	'content'=>$mota,
	    	'type'=>$loai,
	    	'price'=>$dongia,
	    	'img'=>$img
	    );
	    return $this->db->update('product', $arr);
	}

	//hàm lấy các sản phẩm mới nhất
	public function moinhat()
	{
	    $this->db->select('*');
	    $this->db->where('type','Mới nhất');
	    return $this->db->get('product');
	}
	//hàm lấy các sản phẩm giảm giá
	public function giamgia()
	{
	    $this->db->select('*');
	    $this->db->where('type','Giảm giá');
	    return $this->db->get('product');
	}
	//hàm lấy các sản phẩm bán chạy
	public function banchay()
	{
	    $this->db->select('*');
	    $this->db->where('type','Bán chạy');
	    return $this->db->get('product');
	}

	//mặc định ban đầu 1 trang sẽ hiển thị $sosanphamtheodanhmuctrong1trang sản phẩm và lấy từ sản phẩm thứ 0
	public function sanphamtheoiddanhmuc($sosanphamtheodanhmuctrong1trang,$iddanhmuc)
	{
	    $this->db->select('*');
	   
	    $this->db->join('product', 'product.idcategory = category.id');
	    $this->db->where('category.id', $iddanhmuc);
	    return $this->db->get('category',$sosanphamtheodanhmuctrong1trang,0);
	}
	//tính vị trí để hiển thị tất cả sản phẩm
	public function page1($sosanphamtrong1trang,$trangthumay)
	{
		//ban đầu nếu chưa chọn phân loại và sắp xếp thì hiển thị mặc định
		$session=$this->session->userdata("giatriphanloai");
		$session1=$this->session->userdata("giatrisapxep");
		if(empty($session)&&empty($session1)){
		$vitri=($trangthumay-1)*$sosanphamtrong1trang;
		$this->session->set_userdata('vitrisanpham',$vitri);
	    $this->db->select('*');
	    return $this->db->get('product', $sosanphamtrong1trang,$vitri);
		}
		else if(!empty($session)&&empty($session1)){
			$phanloai=$this->session->userdata("giatriphanloai");
			$vitri=($trangthumay-1)*$sosanphamtrong1trang;
			$this->session->set_userdata('vitrisanpham',$vitri);
			$this->db->select('*');
			
			if($phanloai==1){
		    $this->db->where('price>=',10000);
		    $this->db->where('price<=',15000);
		    }
		    else if($phanloai==2){
		    $this->db->where('price>=',20000);
		    $this->db->where('price<=',30000);
		    }
		    else if($phanloai==3){
		    $this->db->where('price>=',50000);
		    $this->db->where('price<=',100000);
		    }
		    else if($phanloai==4){
		    $this->db->where('price>=',200000);
		    $this->db->where('price<=',500000);
		    }
		    else if($phanloai==5){
		    $this->db->where('price>=',1000000);
		    $this->db->where('price<=',2000000);
		    }
		    
		    return $this->db->get('product',$sosanphamtrong1trang,$vitri);
		}
		else if(!empty($session1)&&empty($session)){
			$tieuchi=$session1;
			$vitri=($trangthumay-1)*$sosanphamtrong1trang;
			$this->session->set_userdata('vitrisanpham',$vitri);
			$this->db->select('*');
			if($tieuchi==1){
				//sắp xếp mặc định nên không xét điều kiện gì
			}
			else if($tieuchi==2){
				//sắp xếp theo tên sản phẩm giảm dần
				$this->db->order_by('nameProduct', 'desc');
			}
			else if($tieuchi==3){
				//sắp xếp giá tăng dần
				$this->db->order_by('price', 'asc');
			}
			else if($tieuchi==4){
				//sắp xếp giá giảm dần
				$this->db->order_by('price', 'desc');
			}
			
			return $this->db->get('product',$sosanphamtrong1trang,$vitri);
		}

		//chọn cả phân loại theo giá và sắp xếp
		else if(!empty($session) && !empty($session1)){
			$vitri=($trangthumay-1)*$sosanphamtrong1trang;
			$this->session->set_userdata('vitrisanpham',$vitri);
			$phanloai=$session;
			$tieuchi=$session1;
			$this->db->select('*');
			if($phanloai==1){
			    $this->db->where('price>=',10000);
			    $this->db->where('price<=',15000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==2){
			    $this->db->where('price>=',20000);
			    $this->db->where('price',30000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==3){
			    $this->db->where('price>=',50000);
			    $this->db->where('price<=',100000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==4){
			    $this->db->where('price>=',200000);
			    $this->db->where('price<=',500000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==5){
			    $this->db->where('price>=',1000000);
			    $this->db->where('price<=',2000000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    return $this->db->get('product', $sosanphamtrong1trang, $vitri);
		}
	}
	//mặc định trang đầu tiên của menu sản phẩm sẽ hiển thị $sosanphamtrong1trang sản phẩm và lấy từ vị trí 0
	public function dulieubandau($sosanphamtrong1trang)
	{
		$gia=$this->session->userdata("giatriphanloai");
		$tieuchi=$this->session->userdata("giatrisapxep");
		if(!isset($gia)&&!isset($tieuchi)){
			
	    $this->db->select('*');
	    return $this->db->get('product',$sosanphamtrong1trang,0);
		}
		if(isset($gia)&&empty($tieuchi)){
			$phanloai=$gia;
		
			$this->db->select('*');
			
			if($phanloai==1){
		    $this->db->where('price>=',10000);
		    $this->db->where('price<=',15000);
		    }
		    else if($phanloai==2){
		    $this->db->where('price>=',20000);
		    $this->db->where('price<=',30000);
		    }
		    else if($phanloai==3){
		    $this->db->where('price>=',50000);
		    $this->db->where('price<=',100000);
		    }
		    else if($phanloai==4){
		    $this->db->where('price>=',200000);
		    $this->db->where('price<=',500000);
		    }
		    else if($phanloai==5){
		    $this->db->where('price>=',1000000);
		    $this->db->where('price<=',2000000);
		    }
		    return $this->db->get('product',$sosanphamtrong1trang,0);
		}
		if(isset($tieuchi)&&empty($gia)){
			
			$this->db->select('*');
			if($tieuchi==1){
				//sắp xếp mặc định nên không xét điều kiện gì
			}
			else if($tieuchi==2){
				//sắp xếp theo tên sản phẩm giảm dần
				$this->db->order_by('nameProduct', 'desc');
			}
			else if($tieuchi==3){
				//sắp xếp giá tăng dần
				$this->db->order_by('price', 'asc');
			}
			else if($tieuchi==4){
				//sắp xếp giá giảm dần
				$this->db->order_by('price', 'desc');
			}
			return $this->db->get('product',$sosanphamtrong1trang,0);
		}
		//chọn cả phân loại theo giá và sắp xếp
		if(!empty($gia) && !empty($tieuchi)){
			$phanloai=$gia;
			$this->db->select('*');
			if($phanloai==1){
			    $this->db->where('price>=',10000);
			    $this->db->where('price<=',15000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==2){
			    $this->db->where('price>=',20000);
			    $this->db->where('price<=',30000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('price', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==3){
			    $this->db->where('price>=',50000);
			    $this->db->where('price<=',100000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==4){
			    $this->db->where('price>=',200000);
			    $this->db->where('price<=',500000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==5){
			    $this->db->where('price>=',1000000);
			    $this->db->where('price<=',2000000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    return $this->db->get('product', $sosanphamtrong1trang, 0);
	}
}
	//tính số trang để hiển thị hết số sản phẩm trong bảng sản phẩm
	public function sotrangsanpham($sosanphamtrong1trang)
	{
		$session=$this->session->userdata("giatriphanloai");
		$session1=$this->session->userdata("giatrisapxep");
		
		
		//không chọn phân loại hoặc chọn sắp xếp
		if(($session==""&&$session1!="")||($session==""&&$session1=="")){
		
	    $this->db->select('*');
	    $this->db->from('product');
	    $arr=$this->db->get();
	    $arr=$arr->result_array();
	    $sotrang=ceil(count($arr)/$sosanphamtrong1trang);
	    return $sotrang;
		}

		//chọn phân loại theo giá và không chọn sắp xếp 
		if(!empty($session)&&empty($session1)){
		
			$phanloai=$session;
			$this->db->select('*');
			if($phanloai==1){
		    $this->db->where('price>=',10000);
		    $this->db->where('price<=',15000);

		    }
		    else if($phanloai==2){
		    $this->db->where('price>=',20000);
		    $this->db->where('price<=',30000);
		    }
		    else if($phanloai==3){
		    $this->db->where('price>=',50000);
		    $this->db->where('price<=',100000);
		    }
		    else if($phanloai==4){
		    $this->db->where('price>=',200000);
		    $this->db->where('price<=',500000);
		    }
		    else if($phanloai==5){
		    $this->db->where('price>=',1000000);
		    $this->db->where('price<=',2000000);
		    }
		    $arr=$this->db->get('product');
		    $arr=$arr->result_array();
		    $sotrang=ceil(count($arr)/$sosanphamtrong1trang);
		    return $sotrang;
		}

		//chọn cả phân loại theo giá và sắp xếp
		if(!empty($session) && !empty($session1)){
		
			$phanloai=$session;
			$tieuchi=$session1;
			$this->db->select('*');
			if($phanloai==1){
			    $this->db->where('price>=',10000);
			    $this->db->where('price<=',15000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==2){
			    $this->db->where('price>=',20000);
			    $this->db->where('price<=',30000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==3){
			    $this->db->where('price>=',50000);
			    $this->db->where('price<=',100000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==4){
			    $this->db->where('price>=',200000);
			    $this->db->where('price<=',500000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    else if($phanloai==5){
			    $this->db->where('price>=',1000000);
			    $this->db->where('price<=',2000000);
			    if($tieuchi==1){
					//sắp xếp mặc định nên không xét điều kiện gì
				}
				else if($tieuchi==2){
					//sắp xếp theo tên sản phẩm giảm dần
					$this->db->order_by('nameProduct', 'desc');
				}
				else if($tieuchi==3){
					//sắp xếp giá tăng dần
					$this->db->order_by('price', 'asc');
				}
				else if($tieuchi==4){
					//sắp xếp giá giảm dần
					$this->db->order_by('price', 'desc');
				}
		    }
		    $arr=$this->db->get('product');
		    $arr=$arr->result_array();
		    $sotrang=ceil(count($arr)/$sosanphamtrong1trang);
		    return $sotrang;
		}
		
	}

	//tính vị trí để hiển thị sản phẩm theo danh mục
	public function page($sosanphamtheodanhmuctrong1trang,$trangthumay,$iddanhmucsession)
	{
	    $vitri=($trangthumay-1)*$sosanphamtheodanhmuctrong1trang;

	    $this->db->select('*');
	    $this->db->join('product', 'product.idcategory = category.id');
	    $this->db->where('category.id', $iddanhmucsession);
	    return $this->db->get('category',$sosanphamtheodanhmuctrong1trang,$vitri);
	}

	//hàm tính số trang cho các sản phẩm theo từng danh mục
	public function sotrangtheodanhmuc($sosanphamtheodanhmuctrong1trang,$iddanhmuc)
	{
	    $this->db->select('*');
	    $this->db->from('category');
	    $this->db->join('product', 'product.idcategory = category.id');
	    $this->db->where('category.id', $iddanhmuc);
	    $arr=$this->db->get();
	    $arr=$arr->result_array();
	    //đếm số sản phẩm trong mảng
	    $sosanpham=count($arr);
	    //tính số trang cần có để hiển thị sản phẩm theo danh mục
	    $sotrang=ceil($sosanpham/$sosanphamtheodanhmuctrong1trang);//làm tròn lên giá trị
	    return $sotrang;
	}


	//phân loại sản phẩm theo giá
	public function phanloaigia($phanloai)
	{
	    $this->db->select('*');
	    $this->db->from('product');
	    if($phanloai==1){
	    $this->db->where('price>=',10000);
	    $this->db->where('price<=',15000);
	    }
	    else if($phanloai==2){
	    $this->db->where('price>=',20000);
	    $this->db->where('price<=',30000);
	    }
	    else if($phanloai==3){
	    $this->db->where('price>=',50000);
	    $this->db->where('price<=',100000);
	    }
	    else if($phanloai==4){
	    $this->db->where('price>=',200000);
	    $this->db->where('price<=',500000);
	    }
	    else if($phanloai==5){
	    $this->db->where('price>=',1000000);
	    $this->db->where('price<=',2000000);
	    }
	    return $this->db->get();
	}
	//hàm sắp xếp sản phẩm theo các tiêu chí
	public function sapxeptheotieuchi($tieuchi){
		$this->db->select('*');
		if($tieuchi==0){
			//sắp xếp mặc định nên không xét điều kiện gì
		}
		else if($tieuchi==1){
			//sắp xếp theo tên sản phẩm giảm dần
			$this->db->order_by('nameProduct', 'desc');
		}
		else if($tieuchi==2){
			//sắp xếp giá tăng dần
			$this->db->order_by('price', 'asc');
		}
		else if($tieuchi==3){
			//sắp xếp giá giảm dần
			$this->db->order_by('price', 'desc');
		}
		return $this->db->get('product');
	}
	//viết hàm tính số lượng sản phẩm và lưu vào session để hiển dữ liệu thống kê lên views
	public function soluongsanphamtrongbangsanpham()
	{
	    $this->db->select('*');
	    return $this->db->get('product');
	}

	//hàm chi tiết sản phẩm
	public function chitietsanpham($idsp)
	{
	    $this->db->select('*');
	    $this->db->from('product');
	    $this->db->join('category', 'product.idcategory = category.id');
	    $this->db->where('product.idproduct', $idsp);
	    return $this->db->get();
	}

	//hàm các sản phẩm tương tự trong cùng danh mục
	public function sanphamlienquan($id)
	{
		//từ id sản phẩm lấy ra id danh mục
	    $this->db->select('idcategory');
	    $this->db->where('idproduct', $id);
	    $iddanhmuc=$this->db->get('product');
	    $iddanhmuc=$iddanhmuc->result_array();
	    //sau khi có iddanhmuc thì lấy ra tất cả các sản phẩm thuộc danh mục này nhưng phải khác sản phẩm hiện tại
	    $this->db->select('*');
	    $this->db->where('idcategory', $iddanhmuc[0]['idcategory']);
	    $this->db->where('idproduct!=', $id);
	    return $this->db->get('product');
	}
	//hàm thêm sản phẩm trong giỏ hàng vào bảng giỏ hàng
	public function giohang($id,$soluong)
	{
		//kiểm tra khi thêm sản phẩm vào giỏ hàng thì sản phẩm đó đã có trong giỏ hay chưa,nếu chưa có thì thêm còn có rồi thì chỉ cần tăng số lượng 
		$this->db->select('idproduct');
		$this->db->where('idproduct', $id);
		$ketqua=$this->db->get('cart');
		$ketqua=$ketqua->result_array();
		//nếu $ketqua có dữ liệu tức là sản phẩm đó đã có trước đó trong giỏ hàng thì lấy được số lượng trước đó của sản phẩm này
		if(!empty($ketqua)){//khác trống tức là có dữ liệu
			$this->db->select('amount');//lấy số lượng trước đó
			$this->db->where('idproduct', $id);	
			$dulieu=$this->db->get('cart');
			$dulieu=$dulieu->result_array();
			$soluongtruocdo=$dulieu[0]['amount'];
			//cộng số lượng trước đó với biến $soluong
			$soluongcapnhat=$soluongtruocdo+$soluong;
			//sửa lại số lượng của sản phẩm đó và để sửa dữ liệu thì cần lưu vào 1 mảng 
			$arrsoluong=array('amount'=>$soluongcapnhat);
			//điều kiện là sửa cho sản phẩm có $id
			$this->db->where('idproduct', $id);
			$this->db->update('cart', $arrsoluong);
			
		}
		else{//nếu sản phẩm đó chưa có trong giỏ hàng thì thêm mới vào giỏ hàng

		//thêm sản phẩm vào bảng giỏ hàng dựa vào id của sản phẩm đó
		//lấy thông tin của sản phẩm qua id
		$this->db->select('*');
		$this->db->where('idproduct', $id);
		$dulieu=$this->db->get('product');
		$dulieu=$dulieu->result_array();

		$idsanpham=$dulieu[0]['idproduct'];//lấy id sản phẩm
		$tensanpham=$dulieu[0]['nameProduct'];//lấy tên sản phẩm
		$dongia=$dulieu[0]['price'];//lấy đơn giá
		$img=$dulieu[0]['img'];
		//sau khi có dữ liệu thì insert vào bảng giỏ hàng
		//tạo 1 mảng để lưu giá trị các trường thêm vào
		$arr=array(
			'idproduct'=>$idsanpham,
			'nameProduct'=>$tensanpham,
			'price'=>$dongia,
			'amount'=>$soluong,
			'img'=>$img,
			'idcustomer'=>$this->session->userdata("idhosokhachhang")
		);
		 $this->db->insert('cart', $arr);
		}  
	}
	//hàm lấy dữ liệu giỏ hàng
	public function laydulieugiohang()
	{
	    $this->db->select('*');
	    $this->db->where('idcustomer', $this->session->userdata("idhosokhachhang"));
	    return $this->db->get('cart');
	}
	//viết hàm cập nhật số lượng sản phẩm đã có trong giỏ hàng khi số lượng thay đổi
	public function update_soluong($dulieu)
	{
	    //vì $dulieu là 1 mảng gồm nhiều hàng dữ liệu nhưng khi update chỉ update được từng hàng dữ liệu nên cần duyệt for 
	    
	    foreach ($dulieu as $value) {
	        $this->db->where('idproduct', $value['idproduct']);
	        $this->db->where('idcustomer', $this->session->userdata("idhosokhachhang"));
	        $this->db->update('cart', $value);
	        
	    }
	}

	//hàm xóa sản phẩm trong giỏ hàng
	public function xoasanphamtronggiohang($id)
	{
	    $this->db->where('idproduct', $id);
	    $this->db->where('idcustomer', $this->session->userdata("idhosokhachhang"));
	    return $this->db->delete('cart');
	}
	//hàm tìm kiếm sản phẩm theo tên
	public function timkiemsanpham($tensanpham)
	{
		$connect=mysqli_connect('localhost','root','','thucpham');
	    $query="select * from product where nameProduct like '%$tensanpham%' ";

	    $n=mysqli_query($connect, $query);
	    return $n;
	    
	}
	//hàm đã thanh toán offline thành công và lưu dữ liệu vào bảng đặt hàng
	public function thanhtoan1($array_idsanpham,$session_idkhachhang)
	{
		//$array_idsanpham là 1 mảng lưu các id sản phẩm trong giỏ hàng nên sẽ lấy tất cả dữ liệu của các sản phẩm đó và thêm vào bảng đặt hàng
		//duyệt for để lấy tất cả sản phẩm
		//khai báo 1 mảng
		if(empty($array_idsanpham)){
		//cần phải kiểm tra mảng $array_idsanpham khác null thì mới lấy dữ liệu,vì nếu null mà lấy dữ liệu thì bị lỗi do không duyệt được foreach
		}
		else{

		foreach ($array_idsanpham as $key=>$value) {
			
				$this->db->select('*');
			$this->db->where('idproduct', $value);
			$ketqua=$this->db->get('cart');
			$ketqua=$ketqua->result_array();
				
			//lưu mỗi sản phẩm vào 1 mảng
			foreach ($ketqua as $value1) {
				$arr=array(
				'idproduct'=>$value1['idproduct'],
				'nameProduct'=>$value1['nameProduct'],
				'idcustomer'=>$session_idkhachhang,
				'amount'=>$value1['amount'],
				'price'=>$value1['amount']*$value1['price'],
				'status'=>'chờ duyệt đơn'
			);
				//thêm vào bảng đặt hàng
				$this->db->insert('bill', $arr);
			}
			
				//sau khi các sản phẩm trong giỏ hàng đã được thanh toán và lưu trong bảng đặt hàng thì sẽ xóa sản phẩm đó khỏi bảng giỏ hàng
				$this->db->where('idproduct', $value);
				$this->db->delete('cart');

			}

		}
		}
	
		
	
	//hàm lấy dữ liệu đặt hàng theo id khách hàng
	public function laydulieudathang($idkhachhang)
	{
	    $this->db->select('*');
	    $this->db->where('idcustomer', $idkhachhang);
	    return $this->db->get('bill');
	}
	//hàm cho khách hàng xác nhận đã nhận đơn hàng đã đặt 
	public function xacnhandonhangchokhachhang($idsanpham,$iddathang)
	{
	   //cập nhật đơn hàng đã nhận
		$arr=array('status'=>'đã nhận hàng');
		$this->db->where('idproduct', $idsanpham);
		$this->db->where('idbill', $iddathang);
		$this->db->where('status', 'đang giao hàng');
		return $this->db->update('bill', $arr);
	}

	//code lấy dữ liệu blog
	public function listBlog()
	{
	    $this->db->select("*");
	    return $this->db->get("blog",4,0);//lấy 4 hàng dữ liệu bắt đầu từ vị trí 0
	}
	//code chi tiết blog
	public function chitietblog($id)
	{
	    $this->db->select("*");
	    $this->db->where("idblog",$id);
	    return $this->db->get("blog");
	}
}


/* End of file sanpham_model.php */
/* Location: ./application/models/sanpham_model.php */