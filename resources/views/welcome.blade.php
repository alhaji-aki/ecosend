<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    </head>

    <body>
        <div class="container-fluid">
            <form class="row g-3" id="order-form" method="POST" action="{{ route('orders.store') }}">
                @csrf
                <div class="col-12">
                    <label for="name" class="form-label">Name</label>
                    <input required type="text" class="form-control" id="name" name="name" placeholder="Name">
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
                    <select data-placeholder="Select your country" data-theme="bootstrap-5" data-ajax--url="{{ route('countries.index') }}" data-ajax--cache="false" data-affects="#state" required id="country" name="country" class="form-select select2">
                        <option></option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select data-placeholder="Select your state" data-theme="bootstrap-5" data-url="{{ route('states.index', ':id') }}" data-ajax--cache="false" data-depends-on="#country" data-affects="#city" required id="state" name="state" class="form-select select2">
                        <option></option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <select data-placeholder="Select your city" data-theme="bootstrap-5" data-url="{{ route('cities.index', ':id') }}" data-ajax--cache="false" data-depends-on="#state" required id="city" name="city" class="form-select select2">
                        <option></option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="zip_code" class="form-label">Zip Code</label>
                    <input required type="text" class="form-control" id="zip_code" name="zip_code" placeholder="">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
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

            $("#order-form").validate({
                submitHandler: (form) => {
                    console.log(form.action);

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            // show success message
                            showAlertMessage("success", data.message);

                            // clear forms
                            form.reset();
                            $('.select2').val(null).trigger('change');

                            // TODO: clear datatable

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
