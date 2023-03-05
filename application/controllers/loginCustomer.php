<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class loginCustomer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('loginCustomer_model');
		$this->load->model('danhmuc_model');
	}

	public function index()
	{
		$this->load->view('loginCustomer_views');
	}
	public function kiemtradangnhap()
	{
	    
		$taikhoan=$this->input->post('taikhoan');
		$matkhau=$this->input->post('matkhau');
		if(empty($taikhoan)||empty($matkhau)){
			$this->session->set_userdata('dangnhapkhongthanhcong','');
			$this->index();
			return;
		}
		$dulieu=$this->loginCustomer_model->kiemtradangnhap($taikhoan,$matkhau);
		$dulieu=$dulieu->result_array();
		//khi 1 khách hàng đăng nhập tài khoản thành công thì sẽ lấy được id của chính khách hàng đó và sẽ tạo 1 session để lưu lại id đó để áp dụng cho hồ sơ khách hàng
				
			
		if(!empty($dulieu)){
		$this->session->set_userdata('idhosokhachhang',$dulieu[0]['idcustomer']); 
		$this->session->set_userdata('taikhoankhachhang',$taikhoan);
		$this->session->set_userdata('matkhaukhachhang',$matkhau);
			redirect('sanpham/laydulieugiohang','refresh');
		}else{

		$this->load->view('loginCustomer_views');
		}
	}
	//hàm logout khách hàng
	public function logout()
	{
	    $this->session->unset_userdata('taikhoankhachhang','matkhaukhachhang');

	    //khi logout thì xóa session $this->session->set_userdata('idhosokhachhang',$dulieu[0]['idkhachhang']); để khi chưa đăng nhập thì không thể truy nhập hồ sơ
	    $this->session->unset_userdata('idhosokhachhang'); 
	    redirect('sanpham','refresh');
	}
	
	public function formdangkitaikhoan()
	{
	    $this->load->view('dangkitaikhoan_views');
	}
	//hàm đăng kí tài khoản
	public function dangkitaikhoan()
	{
	    $ten=$this->input->post('ten');
	    $email=$this->input->post('email');
	    $quocgia=$this->input->post('quocgia');
	    if($quocgia==1)
	    	$quocgia="Việt Nam";
	    else if($quocgia==2)
	    	$quocgia="Đà Lạt";
	    else if($quocgia==3)
	    	$quocgia="Nghệ An";
	    $diachi=$this->input->post('diachi');
	    $sdt=$this->input->post('sodienthoai');
	    $mk=$this->input->post('matkhau');

	    if(empty($ten)||empty($email)||empty($quocgia)||empty($diachi)||empty($sdt)||empty($mk)){
	    	$this->session->set_userdata('dangkikhongthanhcong','kiemtralai');
	    	$this->load->view('dangkitaikhoan_views');
	    	
	    }else{
	    $this->loginCustomer_model->dangkitaikhoan($ten,$email,$quocgia,$diachi,$sdt,$mk);
	    $this->session->set_userdata('dangkithanhcong','thanhcong');	
	    
	    $this->load->view('dangkitaikhoan_views');
	    }
	}
	//hàm hồ sơ khách hàng
	public function hoso()
	{
		//vì khi đăng nhập thành công đã lấy được id của khách hàng đó và lưu trong session nên từ id này sẽ lấy dữ liệu của khách hàng để đưa ra hồ sơ khách hàng
		$hoso=$this->loginCustomer_model->hoso($this->session->userdata('idhosokhachhang'));
		$hoso=$hoso->result_array();
		$hoso=array('hoso'=>$hoso);

		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		$dulieu=array($dm,$hoso);
		$dulieu=array('dulieu'=>$dulieu);
	    $this->load->view('hosokhachhang_views',$dulieu);
	}
	//hàm lấy dữ liệu hồ sơ khách hàng trước khi cập nhật
	public function laydulieuhoso($id)
	{
	    $hoso=$this->loginCustomer_model->laydulieuhoso($id);
	    $hoso=$hoso->result_array();
		$hoso=array('hoso'=>$hoso);

		//lấy danh sách danh mục 
		$dm=$this->danhmuc_model->listIdDanhMuc();
		$dm=$dm->result_array();
		$dm=array('danhmuc'=>$dm);

		$dulieu=array($dm,$hoso);
		$dulieu=array('dulieu'=>$dulieu);
	    $this->load->view('capnhathosokhachhang_views',$dulieu);

	}
	//sau khi lấy dữ liệu thì cập nhật hồ sơ
	public function capnhathoso()
	{
	    $idkhachhang=$this->input->post('idkhachhang');
	    $tenkhachhang=$this->input->post('tenkhachhang');
	    $email=$this->input->post('email');
	    $quocgia=$this->input->post('quocgia');
	    if($quocgia==1)
	    	$quocgia="Việt Nam";
	    else if($quocgia==2)
	    	$quocgia="Đà Lạt";
	    else if($quocgia==3)
	    	$quocgia="Nghệ An";
	    $diachi=$this->input->post('diachi');
	    $sdt=$this->input->post('sdt');
	    $matkhau=$this->input->post('matkhau');
	    if($this->loginCustomer_model->capnhathoso($idkhachhang,$tenkhachhang,$email,$quocgia,$diachi,$sdt,$matkhau)){
	    		$this->hoso();
		}
	}
}

/* End of file loginCustomer.php */
/* Location: ./application/controllers/loginCustomer.php */