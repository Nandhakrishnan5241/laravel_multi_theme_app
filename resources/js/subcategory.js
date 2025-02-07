$(document).ready(function () {
    getTableData("initial");

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
            category: {
                required: true,
            },

            image: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "please enter a name",
            },
            category: {
                required: "please select a category",
            },
            image: {
                required: "please select a image",
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
            var formData = new FormData(
                document.getElementById("addCategoryForm")
            );
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
                        $("#editImagePreview").hide();
                        $(".image-container").hide();
                        document.getElementById("addCategoryForm").reset();
                        const table = $("#subCategoryTable").DataTable();
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
                required: "please enter a name",
            },
            editStock: {
                required: "please enter a stocks",
            },
            editPrice: {
                required: "please enter a price",
            },
            editTax: {
                required: "please enter a tax",
            },
            editCategory: {
                required: "please select a category",
            },
            editImage: {
                required: "please select a image",
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
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            var formData = new FormData(
                document.getElementById("editCategoryForm")
            );

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
                        $("#editImagePreview").hide();
                        $(".image-container").hide();
                        const table = $("#subCategoryTable").DataTable();
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
    var table = $("#subCategoryTable").DataTable({
        processing: true,
        serverSide: true,
        stateSave: type === "initial" ? false : true,
        ajax: "subcategory/getdetails",
        columns: [
            { data: "name", name: "name" },
            { data: "category_name", name: "category_name" },
            { data: "description", name: "description" },
            {
                data: "image",
                name: "image",
                orderable: false,
                searchable: false,
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
    });

    // filter only apply while click Enter button
    $("#subCategoryTable_filter input").unbind();
    $("#subCategoryTable_filter input").on("keypress", function (e) {
        if (e.which === 13) {
            table.search(this.value).draw();
        }
    });
}

// EDIT
window.editData = function(id) {
    $.get( 'subcategory/' +id + '/edit', function(data) {
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('editOffCanvasRight'));
        offcanvas.show();
        $('#id').val(data.id);
        $('#editName').val(data.name);
        $("#editCategory").val(data.category_id);
        $('#editDescription').val(data.description);
        $('#currentImage').val(data.image);
        $("#editImagePreview").attr("src", data.image);
        $('#editImagePreview').show();
        $(".image-container").show();
        // $(".image-container").css("display", "block");
        
    });
};

// DELETE
window.deleteData = function(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You would like to delete this Product",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#139fc7',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "subcategory/delete/" + id,
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
                            const table = $('#subCategoryTable').DataTable();
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