<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		check_not_login();
	}
	public function index()
	{
		$this->load->view('navbar');
		$this->load->view('v_home');
		$this->load->view('footer');
	}
}
