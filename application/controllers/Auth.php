<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function index()
	{
		$this->load->view('v_login');
	}
	public function login()
	{
		$post = $this->input->post(null, TRUE);
		if (isset($post['login'])) {
			$this->load->model('Admin_m');
			$query = $this->Admin_m->login($post);
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$params = array(
					'id_admin' 	=> $row->id_admin,
					'name'		=> $row->name,
				);
				$this->session->set_userdata($params);
				echo "<script> 
				alert('login berhasil');
				window.location='" . site_url('dashboard') . "';
				</script>";
			} else {
				echo "<script> 
				alert('login gagal');
				window.location='" . site_url('auth') . "';
				</script>";
			}
		}
	}
	public function logout()
	{
		$params = array('id_admin', 'name');
		$this->session->unset_userdata($params);
		redirect('auth');
	}
}
