<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Artikel_m extends CI_Model
{

	var $table = 'tbl_artikel';
	var $column_order = array('judul_artikel', 'isi_artikel', null);
	var $column_search = array('judul_artikel', 'isi_artikel');
	var $order = array('id' => 'desc');
	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		$i = 0;
		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {

				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	public function save($post)
	{
		$data = [
			'judul_artikel' => $post['judul_artikel'],
			'isi_artikel' => $post['isi_artikel'],
			'thumbnail_artikel' => $post['thumbnail_artikel'],
			'tag_artikel' => $post['tag_artikel'],
			'kategori_artikel' => $post['kategori_artikel'],
		];
		$this->db->insert('tbl_artikel', $data);
	}

	public function getid($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_artikel');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query;
	}
	public function update($post)
	{
		$data = [
			'judul_artikel' => $post['judul_edit'],
			'isi_artikel' => $post['isi_edit'],
			'tag_artikel' => $post['tag_edit'],
			'kategori_artikel' => $post['kategori_edit'],
		];
		if ($post['thumbnail_edit'] != null) {
			$data['thumbnail_artikel'] = $post['thumbnail_edit'];
		}
		$this->db->where('id', $post['id_edit']);
		$this->db->update('tbl_artikel', $data);
	}
	public function delete($post)
	{
		$this->db->where('id', $post['id_hapus']);
		$this->db->delete('tbl_artikel');
	}
}
