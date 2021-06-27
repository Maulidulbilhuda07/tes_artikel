<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Artikel extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		check_not_login();
	}
	public function index()
	{
		$this->load->view('navbar');
		$this->load->view('v_artikel');
		$this->load->view('footer');
	}
	public function ajax_list()
	{
		$list = $this->Artikel_m->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$row[] = $item->judul_artikel;
			$row[] = $item->isi_artikel;
			$row[] = '<img src="' . base_url('img/' . $item->thumbnail_artikel) . '" class="img" style="width:100px">';
			$row[] = $item->tag_artikel;
			$row[] = $item->kategori_artikel;
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" onclick="edit(' . "'" . $item->id . "'" . ')"> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)"onclick="hapus(' . "'" . $item->id . "'" . ')"> Delete</a>';
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Artikel_m->count_all(),
			"recordsFiltered" => $this->Artikel_m->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function save()
	{
		$config['upload_path'] = './img/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']     = '2048';
		$config['file_name']     = 'img-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);
		$this->load->library('upload', $config);
		$post = $this->input->post(null, true);
		if (@$_FILES['thumbnail_artikel']['name'] != null) {
			if ($this->upload->do_upload('thumbnail_artikel')) {
				$post['thumbnail_artikel'] = $this->upload->data('file_name');
				$data = $this->Artikel_m->save($post);
				echo json_encode($data);
			}
		} else {
			$error = $this->upload->display_errors();
			$this->session->set_flashdata('error', $error);
		}
	}
	public function getid($id)
	{
		$data = $this->Artikel_m->getid($id)->row();
		echo json_encode($data);
	}
	public function update()
	{
		$config['upload_path'] = './img/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']     = '2048';
		$config['file_name']     = 'img-' . date('ymd') . '-' . substr(md5(rand()), 0, 10);
		$this->load->library('upload', $config);
		$post = $this->input->post(null, true);
		if (@$_FILES['thumbnail_edit']['name'] != null) {
			if ($this->upload->do_upload('thumbnail_edit')) {
				$post['thumbnail_edit'] = $this->upload->data('file_name');
				$data = $this->Artikel_m->update($post);
				echo json_encode($data);
			}
		} else {
			$post['thumbnail_edit'] = null;
			$data = $this->Artikel_m->update($post);
			echo json_encode($data);
		}
	}

	public function delete()
	{
		$post = $this->input->post(null, true);
		$data = $this->Artikel_m->delete($post);
		echo json_encode($data);
	}
}

	// <script type="text/javascript">
	// 	$(window).on('load', function() {
	// 		CKEDITOR.replace('isi_artikel');
	// 	});
	// </script>
