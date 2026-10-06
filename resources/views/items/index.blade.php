<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Item</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="container mt-3">
            <h2>Data Item</h2>         
            <button type="button" class="btn btn-success" 
            data-bs-toggle="modal" data-bs-target="#modaltambah" id="tambah">ADD</button> 
            <table class="table table-striped" id="tableitem">
                <thead>
                    <tr>
                    <th>Action</th> 
                    <th>Id</th> 
                    <th>Name</th> 
                    <th>Description</th> 
                    <th>Price</th>
                    <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item as $x)
                    <tr>
                        <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEdit" onclick="isiInput({{$x->id}}, '{{$x->name}}', '{{$x->description}}', {{$x->price}}, {{$x->stock}})">Edit</button></td>
                        <td>{{$x->id}}</td>
                        <td>{{$x->name}}</td>
                        <td>{{$x->description}}</td>
                        <td>{{$x->price}}</td>
                        <td>{{$x->stock}}</td>
                    </tr>   
                    @endforeach
                </tbody>
            </table>
        </div>
            
        <!-- The Modal -->
        <div class="modal" id="modaltambah">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Item</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mt-3">
                            <input type="text" class="form-control" id="name" 
                            placeholder="Enter Name" name="name">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="description" 
                            placeholder="Enter Description" name="description">
                        </div>
                        <div class="mt-3">
                            <input type="number" class="form-control" id="price" 
                            placeholder="Enter Price" name="price">
                        </div>
                        <div class="mt-3">
                            <input type="number" class="form-control" id="stock" 
                            placeholder="Enter Stock" name="stock">
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="save1">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>  


        <!-- The Modal -->
        <div class="modal" id="modalEdit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Item</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" id="item_id">
                        <div class="mt-3">
                            <input type="text" class="form-control" id="name1" 
                            placeholder="Enter Name" name="name">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" id="description1" 
                            placeholder="Enter Description" name="description">
                        </div>
                        <div class="mt-3">
                            <input type="number" class="form-control" id="price1" 
                            placeholder="Enter Price" name="price">
                        </div>
                        <div class="mt-3">
                            <input type="number" class="form-control" id="stock1" 
                            placeholder="Enter Stock" name="stock">
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="update">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="delete">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        </div> 

        <script>
            
        $('#save1').on("click", function() {
                var formData = new FormData();
                console.log("tes");
                // Membuat objek FormData dari elemen form
                let name = $("#name").val();
                let description = $("#description").val();
                let price = $("#price").val();
                let stock = $("#stock").val();
                    

                // Tambah data ekstra jika diperlukan (opsional)
                formData.append('name', name);
                formData.append('description', description);
                formData.append('price', price);
                formData.append('stock', stock);
                // Tambahkan CSRF Token ke dalam FormData 
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                formData.append('_token', csrfToken); 

                $.ajax({
                    type: 'POST',
                    url: 'items', // URL tujuan backend Anda
                    data: formData,
                    processData: false,  // Wajib: cegah jQuery memproses data menjadi string
                    contentType: false,  // Wajib: biarkan browser menentukan header multipart/form-data
                    success: function (response) {
                        console.log('Sukses:', response);
                        alert('Data berhasil dikirim!');
                    },
                    error: function (xhr, status, error) {
                        console.error('Gagal:', error);
                    }
                });
                
            });

            function isiInput(kode, nama, description, price, stock) {
                $('#item_id').val(kode);
                $('#name1').val(nama || '');
                $('#description1').val(description || '');
                $('#price1').val(price || 0);
                $('#stock1').val(stock || 0);
            }

            $("#update").click(function(){
                var formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content')); 
                formData.append('name', $('#name1').val());
                formData.append('description', $('#description1').val());
                formData.append('price', $('#price1').val());
                formData.append('stock', $('#stock1').val());

                $.ajax({
                    type: 'POST',
                    url: 'items/' + $('#item_id').val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Sukses:', response);
                        alert('Data berhasil diupdate!');
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal:', error);
                    }
                });
            });

            $("#delete").click(function(){
        if (confirm("Apa anda yakin ingin menghapus data?")) {
            var formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: 'items/' + $('#item_id').val(),
                    type: 'DELETE',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Sukses:', response);
                        alert('Data berhasil dihappus!');
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal:', error);
                    }
                });
        }
        
        });

        </script>
    </body>
</html>
