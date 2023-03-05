<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class loginCustomer_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}
	//hàm kiểm tra đăng nhập
	public function kiemtradangnhap($tk,$mk)
	{
	    $this->db->select('email,password,idcustomer');
	    $this->db->where('email', $tk);
	    $this->db->where('password', $mk);
	    return $this->db->get('customer');
	}
	//hàm đăng kí tài khoản 
	public function dangkitaikhoan($ten,$email,$quocgia,$diachi,$sdt,$mk)
	{
		$arr=array(
			'nameCustomer'=>$ten,
			'email'=>$email,
			'nation'=>$quocgia,
			'address'=>$diachi,
			'phoneNumber'=>$sdt,
			'password'=>$mk
		);
		$this->db->insert('customer', $arr);
		return $this->db->insert_id();
	    
	}
	//hàm hồ sơ khách hàng
	public function hoso($idkhachhang)
	{
	    $this->db->select('*');
	    $this->db->where('idcustomer', $idkhachhang);
	    return $this->db->get('customer');
	}

	//hàm lấy dữ liệu hồ sơ khách hàng trước khi cập nhật
	public function laydulieuhoso($id)
	{
		$this->db->select('*');
		$this->db->where('idcustomer', $id);
		return $this->db->get('customer');	    
	}
	//sau khi lấy dữ liệu thì cập nhật hồ sơ
	public function capnhathoso($idkhachhang,$tenkhachhang,$email,$quocgia,$diachi,$sodienthoai,$matkhau)
	{
	   $arr=array(
	   		'nameCustomer'=>$tenkhachhang,
	   		'email'=>$email,
	   		'nation'=>$quocgia,
	   		'address'=>$diachi,
	   		'phoneNumber'=>$sodienthoai,
	   		'password'=>$matkhau
	   );
	   $this->db->where('idcustomer', $idkhachhang);
	   return $this->db->update('customer', $arr);
	}

}

/* End of file loginCustomer_model.php */
/* Location: ./application/models/loginCustomer_model.php */