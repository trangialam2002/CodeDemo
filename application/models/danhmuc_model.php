<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class danhmuc_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}
	public function addDanhMuc($tendanhmuc)
	{
	    $arr=array('categoryName'=>$tendanhmuc);
	    $this->db->insert('category', $arr);
	    return $this->db->insert_id();
	}
	//hàm hiện thị danh sách danh mục
	public function listDanhMuc($soluong)
	{
	   $this->db->select('*');
	   return $this->db->get('category',$soluong,0);
	}
	//hàm lấy dữ liệu theo id
	public function editdanhmuc($iddanhmuc)
	{
	    $this->db->select('*');
	    $this->db->where('id', $iddanhmuc);
	    return $this->db->get('category');
	}

	//hàm hiển thị danh sách id và tên danh mục để gửi sang sản phẩm
	public function listIdDanhMuc()
	{
		
	   $this->db->select('id,categoryName');
	   return $this->db->get('category');
	}

	//sửa dữ liệu
	public function edit($iddanhmuc,$tendanhmuc)
	{
	    $arr=array('categoryName'=>$tendanhmuc);
	    $this->db->where('id', $iddanhmuc);
	    return $this->db->update('category', $arr);
	}
	//xóa dữ liệu
	public function deletedanhmuc($id)
	{
	    $this->db->where('id', $id);
	    return $this->db->delete('category');
	}
}

/* End of file themdanhmuc_model.php */
/* Location: ./application/models/themdanhmuc_model.php */