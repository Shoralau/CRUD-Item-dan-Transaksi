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
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modaltambah"
            id="tambah">ADD</button>
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
                @foreach ($item as $x)
                    <tr>
                        <td><button type="button" class="btn btn-success" id="edit" data-bs-toggle="modal"
                                data-bs-target="#modalEdit" data-id="{{ $x->id }}"
                                data-name="{{ $x->name }}" data-description="{{ $x->description }}"
                                data-price="{{ $x->price }}" data-stock="{{ $x->stock }}">Edit</button></td>
                        <td>{{ $x->id }}</td>
                        <td>{{ $x->name }}</td>
                        <td>{{ $x->description }}</td>
                        <td>{{ $x->price }}</td>
                        <td>{{ $x->stock }}</td>
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
                        <input type="text" class="form-control" id="name" placeholder="Enter Name"
                            name="name">
                    </div>
                    <div class="mt-3">
                        <input type="text" class="form-control" id="description" placeholder="Enter Description"
                            name="description">
                    </div>
                    <div class="mt-3">
                        <input type="number" class="form-control" id="price" placeholder="Enter Price"
                            name="price">
                    </div>
                    <div class="mt-3">
                        <input type="number" class="form-control" id="stock" placeholder="Enter Stock"
                            name="stock">
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
                        <input type="text" class="form-control" id="name1" placeholder="Enter Name"
                            name="name">
                    </div>
                    <div class="mt-3">
                        <input type="text" class="form-control" id="description1" placeholder="Enter Description"
                            name="description">
                    </div>
                    <div class="mt-3">
                        <input type="number" class="form-control" id="price1" placeholder="Enter Price"
                            name="price">
                    </div>
                    <div class="mt-3">
                        <input type="number" class="form-control" id="stock1" placeholder="Enter Stock"
                            name="stock">
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="update">Update</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                        id="delete">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>


        $('#save1').on("click", function() {
                var formdata = new FormData();
                console.log("tes");
                // Membuat objek FormData dari elemen form
                let name = $("#name").val();
                let description = $("#description").val();
                let price = $("#price").val();
                let stock = $("#stock").val();


                formdata.append('name', name);
                formdata.append('description', description);
                formdata.append('price', price);
                formdata.append('stock', stock);

                $.ajax({
                    type: 'POST',
                    url: 'items', // URL tujuan backend Anda
                    data: formdata,
                    processData: false, // Wajib: cegah jQuery memproses data menjadi string
                    contentType: false, // Wajib: biarkan browser menentukan header multipart/form-data
                    success: function(response) {
                        console.log('Sukses:', response);
                        alert('Data berhasil dikirim!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal:', error);
                    }
                });

            });

            $(document).on('click', '#edit', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let description = $(this).data('description');
                let price = $(this).data('price');
                let stock = $(this).data('stock');

                $('#item_id').val(id);
                $('#name1').val(name);
                $('#description1').val(description);
                $('#price1').val(price);
                $('#stock1').val(stock);
            });

            $("#update").on("click", function() {
                let id = $('#item_id').val();
                let name = $('#name1').val();
                let description = $('#description1').val();
                let price = $('#price1').val();
                let stock = $('#stock1').val();

                var formdata = new FormData();
                formdata.append('id', id);
                formdata.append('name', name);
                formdata.append('description', description);
                formdata.append('price', price);
                formdata.append('stock', stock);
                

                $.ajax({
                    type: 'PUT',
                    url: 'items',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Sukses:', response);
                        alert('Data berhasil diupdate!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal:', error);
                    }
                });
            });

            $("#delete").click(function() {
                if (confirm("Apa anda yakin ingin menghapus data?")) {
                    var formData = new FormData();
                    formData.append('id', $('#item_id').val());

                    $.ajax({
                        url: 'items',
                        type: 'DELETE',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log('Sukses:', response);
                            alert('Data berhasil dihapus!');
                            location.reload();
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
