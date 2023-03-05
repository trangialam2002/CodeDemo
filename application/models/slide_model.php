<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class slide_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}
	//hàm thêm slide
	public function themslide($h4,$h2,$p,$backgroundimg)
	{
	    $arr=array(
	    	'h4'=>$h4,
	    	'h2'=>$h2,
	    	'p'=>$p,
	    	'backgroundimg'=>$backgroundimg
	    	);
	    return $this->db->insert('slide', $arr);
	}

	//hàm hiển thị danh sách slide theo số lượng chỉ định
	public function listslide($soluong)
	{
	    $this->db->select('*');
	    return $this->db->get('slide', $soluong,0);
	}
	//hàm lấy dữ liệu trong bảng slide
	public function laydulieuslide()
	{
	    $this->db->select('*');
	    return $this->db->get('slide');
	}

}

/* End of file slide_model.php */
/* Location: ./application/models/slide_model.php */