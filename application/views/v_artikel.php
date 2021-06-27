<div class="container mt-5">
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
	<div class="text-center mt-3 mb-5">
		<h3>Data Artikel</h3>
	</div>
	<div class="table-responsive">
		<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Judul Artikel</th>
					<th>Isi Atikel</th>
					<th>Thumbnail</th>
					<th>Tags</th>
					<th>Kategori</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(window).on('load', function() {
		CKEDITOR.replace('isi_artikel');
	})
	var table;
	$(document).ready(function() {
		// reload table
		function reload_table() {
			table.ajax.reload(null, false);
		}
		table = $('#table').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?php echo site_url('artikel/ajax_list') ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [-1],
				"orderable": false,
			}, ],
		});
		//Simpan Artikel
		$('#submit').submit(function(e) {
			e.preventDefault();
			var data = new FormData(this);
			//add the content
			data.append('isi_artikel', CKEDITOR.instances['isi_artikel'].getData());
			$.ajax({
				url: '<?php echo base_url(); ?>artikel/save',
				type: "POST",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function(data) {
					$('#staticBackdrop').modal('hide');
					reload_table();
				}
			});
		});
		// update artikel aksi
		$('#simpan').submit(function(e) {
			e.preventDefault();
			var data = new FormData(this);
			//add the content
			data.append('isi_edit', CKEDITOR.instances['isi_edit'].getData());
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('artikel/update') ?>",
				dataType: "JSON",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function(data) {
					$('#exampleModal').modal('hide');
					reload_table();
				}
			});
			return false;
		});

		// delete artikel aksi
		$('#btn_delete').on('click', function() {
			var id_hapus = $('#id_hapus').val();
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('artikel/delete') ?>",
				dataType: "JSON",
				data: {
					id_hapus: id_hapus,
				},
				success: function(data) {
					$('[name="id_hapus"]').val("");
					$('#delete').modal('hide');
					reload_table();
				}
			});
			return false;
		});
	});
	// show id for delete
	function hapus(id) {
		$.ajax({
			url: "<?php echo site_url('artikel/getid') ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('[name="id_hapus"]').val(data.id);
				$('#judul_hapus').text(data.judul_artikel);
				$('#delete').modal('show');
			},
		});
	}
	// show id for update
	function edit(id) {
		$.ajax({
			url: "<?php echo site_url('artikel/getid') ?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				var editor = CKEDITOR.replace('isi_edit');
				// editor.on('onclick', function(evt) {
				// 	console.log(evt.editor.getData().length);
				// });
				$('[name="id_edit"]').val(data.id);
				$('[name="judul_edit"]').val(data.judul_artikel);
				$('[name="isi_edit"]').val(data.isi_artikel);
				// $('[name="thumbnail_edit"]').val(data.thumbnail_artikel);
				$('[name="tag_edit"]').val(data.tag_artikel);
				$('[name="kategori_edit]').val(data.kategori_artikel);
				$('#exampleModal').modal('show');
			},
		});
	}
</script>
<!-- Modal -->
<form enctype="multipart/form-data" id="submit">
	<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Tambah Data Artikel</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="judul_artikel">Judul Artikel</label>
						<input type="text" name="judul_artikel" class="form-control" id="judul_artikel" required>
					</div>
					<div class="form-group">
						<label for="isi_artikel">Isi Artikel</label>
						<textarea type="text" name="isi_artikel" class="form-control" id="isi_artikel" required></textarea>
					</div>
					<div class="form-group">
						<label for="thumbnail_artikel">Thumbnail Artikel</label>
						<input type="file" name="thumbnail_artikel" class="form-control" id="thumbnail_artikel" required>
					</div>
					<div class="form-group">
						<label for="tag_artikel">Tag Artikel</label>
						<input type="text" name="tag_artikel" class="form-control" id="tag_artikel" required>
					</div>
					<div class="form-group">
						<label for="kategori_artikel">Kategori Artikel</label>
						<input type="text" name="kategori_artikel" class="form-control" id="kategori_artikel" required>
					</div>
				</div>
				<div class="modal-footer">
					<button id="btn_simpan" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- Modal update -->
<form enctype="multipart/form-data" id="simpan">
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit Data Artikel</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" value="" name="id_edit" id="id_edit">
					<div class="form-group">
						<label for="judul_artikel">Judul Artikel</label>
						<input type="text" name="judul_edit" class="form-control" id="judul_edit" required>
					</div>
					<div class="form-group">
						<label for="isi_artikel">Isi Artikel</label>
						<textarea type="text" name="isi_edit" class="form-control" id="isi_edit" required></textarea>
					</div>
					<div class="form-group">
						<label for="thumbnail">Thumbnail</label> <span class="text-info" id=""> Biarkan Kosong Jika Tidak Diganti</span>
						<input type="file" name="thumbnail_edit" class="form-control" id="thumbnail_edit">
					</div>
					<div class="form-group">
						<label for="tag_artikel">Tag</label>
						<input type="text" name="tag_edit" class="form-control" id="tag_edit" required>
					</div>
					<div class="form-group">
						<label for="kategori_artikel">Kategori</label>
						<input type="text" name="kategori_edit" class="form-control" id="kategori_edit" required>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" id="btn_update" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- modal delete -->
<form enctype="multipart/form-data">
	<!-- Modal -->
	<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Are you sure delete this data?</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" value="" name="id_hapus" id="id_hapus">
					<span id="judul_hapus"></span>
				</div>

				<div class="modal-footer">
					<button type="button" id="btn_delete" class="btn btn-primary">Delete</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</form>
