$(document).ready(function () {
    getTableData('initial');

    $("#image").change(function (event) {
        let reader = new FileReader();
        reader.onload = function () {
            $("#imagePreview").attr("src", reader.result);
            $(".image-container").show();
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    $("#editImage").change(function (event) {
        let reader = new FileReader();
        reader.onload = function () {
            $("#editImagePreview").attr("src", reader.result);
            $(".image-container").show();
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    // on submit
    var formValidator = {
        rules: {
            name: {
                required: true,
            },
            description: {
                required: true,
            },
            image: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Name field is required",
            },
            description: {
                required: "description field is required",
            },
            image: {
                required: "image field is required",
            },
        },
        highlight: function (element) {
            $(element).addClass("validation");
            $(element).next("span").addClass("validation");
        },
        unhighlight: function (element) {
            $(element).removeClass("validation");
            $(element).next("span").removeClass("validation");
        },
        errorPlacement: function (error, element) {
            element.attr("placeholder", error.text()).addClass("validation");
            element.next("span").addClass("validation");
        },
        debug: false,
        submitHandler: function (form) {
            // form.submit();
        },
    };

    $("#addCategoryForm").validate(formValidator);

    $("#addCategoryForm").on("submit", function (e) {
        if ($("#addCategoryForm").valid()) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            var formData = new FormData(document.getElementById("addCategoryForm"));
            $.ajax({
                url: $(this).attr("action"),
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#addOffcanvasRight").offcanvas("hide");
                        $('#editImagePreview').hide();
                        $(".image-container").hide();
                        document.getElementById("addCategoryForm").reset();
                        const table = $("#categoryTable").DataTable();
                        table.clear().draw();
                        table.destroy();

                        getTableData("initial");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                        });
                    }
                },
                error: function (xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = "";

                    if (errors) {
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + "<br>";
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        footer: '<a href="#">Why do I have this issue?</a>',
                    });
                },
            });
        }
    }); 

    // update

    var editFormValidator = {
        rules: {
            editName: {
                required: true,
            },
            editStock: {
                required: true,
            },
            editPrice: {
                required: true,
            },
            editTax: {
                required: true,
            },
            editDescription: {
                required: true,
            },
        
        },
        messages: {
            editName: {
                required: "Name field is required",
            },
            editStock: {
                required: "this field is required",
            },
            editPrice: {
                required: "this field is required",
            },
            editTax: {
                required: "this field is required",
            },
            editDescription: {
                required: "this field is required",
            },
            
        },
        highlight: function (element) {
            $(element).addClass("validation");
            $(element).next("span").addClass("validation");
        },
        unhighlight: function (element) {
            $(element).removeClass("validation");
            $(element).next("span").removeClass("validation");
        },
        errorPlacement: function (error, element) {
            element.attr("placeholder", error.text()).addClass("validation");
            element.next("span").addClass("validation");
        },
        debug: false,
        submitHandler: function (form) {
            // form.submit();
        },
    };

    $("#editCategoryForm").validate(editFormValidator);

    $("#editCategoryForm").on("submit", function (e) {
        if ($("#editCategoryForm").valid()) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
            var formData = new FormData(document.getElementById("editCategoryForm"));


            $.ajax({
                url: $(this).attr("action"),
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#editOffCanvasRight").offcanvas("hide");
                        document.getElementById("editCategoryForm").reset();
                        $('#editImagePreview').hide();
                        const table = $("#categoryTable").DataTable();
                        table.clear().draw();
                        table.destroy();
                        getTableData("update");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                            // footer: '<a href="#">Why do I have this issue?</a>'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = "";

                    if (errors) {
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + "<br>";
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        footer: '<a href="#">Why do I have this issue?</a>',
                    });
                },
            });
        }
    });
});

function getTableData(type) {
    var table = $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        stateSave: type === 'initial' ? false : true,
        ajax: "categories/getdetails", 
        columns: [
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Override the default search behavior
    $('#categoryTable_filter input').unbind(); 
    $('#categoryTable_filter input').on('keypress', function (e) {
        if (e.which === 13) { 
            table.search(this.value).draw(); 
        }
    });
}

// EDIT
window.editData = function(id) {
    $.get( 'categories/' +id + '/edit', function(data) {
        console.log(data)
        
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('editOffCanvasRight'));
        offcanvas.show();
        $('#id').val(data.id);
        $('#editName').val(data.name);
        $('#editDescription').val(data.description);
        $('#currentImage').val(data.image);
        $("#editImagePreview").attr("src", data.image);
        $(".image-container").show();
        
    });
};

// DELETE
window.deleteData = function(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You would like to delete this Category",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#139fc7',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Set the CSRF token for the AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "categories/delete/" + id,
                data: {
                    id: id,
                    _token: $('input[name="_token"]').val() 
                },
                success: function (data) {
                    if (data.status == 200) {
                        Swal.fire({
                            title: "Success",
                            text: "Data Deleted Successfully!",
                            icon: "success",
                            confirmButtonText: "Ok",
                        }).then(() => {

                            const table = $('#categoryTable').DataTable();
                            table.clear().draw();
                            table.destroy();
                            getTableData('update');
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong!",
                            icon: "error",
                            confirmButtonText: "Ok",
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        title: "Error",
                        text: xhr.responseJSON.message || "An error occurred.",
                        icon: "error",
                        confirmButtonText: "Ok",
                    });
                }
            });
        } else {
            Swal.fire({
                title: "Cancelled",
                text: "Your operation has been cancelled",
                icon: "error",
                confirmButtonText: "Ok",
            });
        }
    });
};



