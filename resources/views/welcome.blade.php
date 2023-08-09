<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <div class="container-sm">
         <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-7 mx-auto">
                <form class="row g-3" id="order-form" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <div class="col-12 row g-2">
                        <div class="col-2">
                            <label for="name" class="form-label">Name</label>
                        </div>
                        <div class="col-10">
                            <input required type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <input required type="text" class="form-control" id="address" name="address" placeholder="Address">
                    </div>
                    <div class="col-12">
                        <label for="country" class="form-label">Country</label>
                        <select data-placeholder="Select your country" data-theme="bootstrap-5"
                            data-ajax--url="{{ route('countries.index') }}" data-ajax--cache="false" data-affects="#state" required
                            id="country" name="country" class="form-select select2">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="state" class="form-label">State</label>
                        <select data-placeholder="Select your state" data-theme="bootstrap-5"
                            data-url="{{ route('states.index', ':id') }}" data-ajax--cache="false" data-depends-on="#country"
                            data-affects="#city" required id="state" name="state" class="form-select select2">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <select data-placeholder="Select your city" data-theme="bootstrap-5"
                            data-url="{{ route('cities.index', ':id') }}" data-ajax--cache="false" data-depends-on="#state" required
                            id="city" name="city" class="form-select select2">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input required type="text" class="form-control" id="zip_code" name="zip_code" placeholder="">
                    </div>

                    <div class="col-12 g-3">
                        <button type="button" class="btn btn-secondary float-end" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            Add Product <i class="fa fa-plus"></i>
                        </button>

                        <table id="productsTable" data-responsive="true" width="100%" data-dom="" data-paging="false"
                            data-searching="false" data-ordering="false" class="display">
                            <thead>
                                <tr>
                                    <th data-name="serial_no" data-data="serial_no">S. no</th>
                                    <th data-name="name" data-data="name">Product Name</th>
                                    <th data-name="price" data-data="price">Price</th>
                                    <th data-name="quantity" data-data="quantity">Qt.</th>
                                    <th data-name="status" data-data="status">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
         </div>

            <!-- Modal -->
            <div id="addProductModal" class="modal fade modal-dialog-centered" tabindex="-1" aria-labelledby="addProductModalLabel"
                aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="productForm">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <label for="serial_no" class="form-label">Serial No.:</label>
                                    <input type="text" class="form-control" name="serial_no" id="serial_no" required>
                                </div>
                                <div class="col-12">
                                    <label for="name" class="form-label">Product Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="col-12">
                                    <label for="price" class="form-label">Price:</label>
                                    <input type="number" min="0" class="form-control" name="price" id="price" required>
                                </div>
                                <div class="col-12">
                                    <label for="quantity" class="form-label">Quantity:</label>
                                    <input type="number" min="0" class="form-control" name="quantity" id="quantity" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

        <script>
            // select2 initialization
            $('.select2').select2({
                ajax: {
                    url: function (params) {
                        return this.data('url').replace(':id', $(this.data('depends-on')).val());
                    },
                    processResults: function (data) {
                        return {
                            results: formatSelectData(data.data)
                        };
                    }
                }
            });

            $('.select2').on('change', function (element) {
                const affects = $(this).data('affects');

                if (affects != null) {
                    $(affects).val(null).trigger('change');
                    $(affects).select2('data', null)
                }
            });

            // jquery.validation initialization
             $.validator.setDefaults({
                errorElement: "div",
                errorPlacement: (error, element) => {
                    error.addClass("invalid-feedback");
                    error.insertAfter(element);
                },
                highlight: (element, errorClass, validClass) => {
                    $(element).addClass("is-invalid");
                },
                unhighlight: (element, errorClass, validClass) => {
                    $(element).removeClass("is-invalid");
                },
            });

            // data table initialization
            let table = $('#productsTable').DataTable({
                "columns": [
                    { className: "editable" },
                    { className: "editable" },
                    { className: "editable number" },
                    { className: "editable number" },
                    null
                ]
            });

            $('#productsTable tbody').on('click', '.edit-btn', function() {
                var row = $(this).closest('tr');
                var rowData = table.row(row).data();
                var keys = Object.keys(rowData);

                // Replace cells with input fields
                row.find('td.editable').each(function(index, element) {
                    const key = keys[index];
                    const type = $(this).hasClass('number') ? 'number' : 'text';
                    $(this).html(`<input name="${key}" class="form-control" type="${type}" value="${rowData[key]}">`);
                });

                // Replace buttons with Save and Cancel buttons
                row.find('.edit-btn').hide();
                row.find('.delete-btn').hide();
                row.find('.save-btn').removeClass('d-none');
            });

            $('#productsTable tbody').on( 'click', '.delete-btn', function () {
                table
                    .row( $(this).parents('tr') )
                    .remove()
                    .draw();
            });

            $('#productsTable tbody').on('click', '.save-btn', function() {
                let row = $(this).closest('tr');
                let rowData = table.row(row).data();
                let keys = Object.keys(rowData);

                // Capture updated data from input fields
                row.find('input').each(function(index) {
                    console.log(keys[index]);
                    rowData[keys[index]] = $(this).val();
                });

                // Update row data and redraw
                table.row(row).data(rowData).draw();

                // Replace buttons with Save and Cancel buttons
                row.find('.edit-btn').show();
                row.find('.delete-btn').show();
                row.find('.save-btn').addClass('d-none');
            });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $("#order-form").validate({
                submitHandler: (form) => {
                    let data = $(form).serializeArray().reduce(function(acc, item) {
                        acc[item.name] = item.value;
                        return acc;
                    }, {})

                    data['products'] = table.rows().data().toArray();

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: JSON.stringify(data),
                        contentType:"application/json; charset=utf-8",
                        dataType:"json",
                        success: function (data) {
                            // show success message
                            showAlertMessage("success", data.message);

                            // clear forms
                            form.reset();
                            $('.select2').val(null).trigger('change');

                            // clear datatable
                            table.clear().draw();
                        },
                        error: function (xhr) {
                            var message = "";
                            switch (xhr.status) {
                                case 400:
                                case 403:
                                case 404:
                                    message = xhr.responseJSON.message;
                                    break;
                                case 419:
                                    message =
                                        "Looks like you stayed too long on the page. Please refresh and try again.";
                                    break;
                                case 422:
                                    message = xhr.responseJSON.message;
                                    showValidationErrorMessages($(form), xhr.responseJSON.errors);
                                    break;
                                default:
                                    message = "Oops... Something went wrong... Try again shortly";
                                    break;
                            }

                            showAlertMessage("error", message);
                        },
                    });
                },
            });

            $("#productForm").validate({
                submitHandler: (form) => {
                    let data = $(form).serializeArray().reduce(function(acc, item) {
                        acc[item.name] = item.value;
                        return acc;
                    }, {});

                    data['status'] = `
                        <button type="button" class="btn btn-sm btn-primary edit-btn">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger delete-btn">Delete</button>
                        <button type="button" class="btn btn-sm btn-success save-btn d-none">Save</button>
                    `;

                    // add data to table
                    table.row.add(data).draw();

                    // clear forms
                    form.reset();

                    $('#addProductModal').modal('hide');
                },
            });

            function formatSelectData(data) {
                return $.map(data, function (obj) {
                    obj.id = obj.uuid || obj.id;
                    obj.text = obj.text || obj.name;

                    return obj;
                })
            }

            function showValidationErrorMessages (form, errors){
                // remove all existing validation messages
                form.find(".error.invalid-feedback").remove();

                $.each(errors, (error, messages) => {
                    const input = form.find(`:input[name^='${error}']`);

                    input.addClass("is-invalid");

                    var errorLabel = $(`#${error}-error`);

                    if (errorLabel.length > 0) {
                        errorLabel.text(messages[0]).show();
                    } else {
                        input
                            .closest("div")
                            .append(
                                `<div id="${error}-error" class="error invalid-feedback" for="${error}">${messages[0]}</div>`
                            );
                    }
                });
            };

            function showAlertMessage (icon, text) {
                Swal.fire({
                    text,
                    icon,
                    buttonsStyling: true,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-primary",
                    },
                });
            };
        </script>
    </body>

</html>
