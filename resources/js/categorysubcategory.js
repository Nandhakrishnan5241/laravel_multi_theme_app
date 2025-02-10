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
            categoryName: {
                required: true,
            },
            category: {
                required: true,
            },
            subCategoryName: {
                required: true,
            },
            subCategory: {
                required: true,
            },
            image: {
                required: true,
            },
        },
        messages: {
            categoryName : {
                required: "please enter a category name",
            },
            subCategoryName: {
                required: "please enter a sub category name",
            },
            category: {
                required: "please select a category",
            },
            subCategory: {
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

    $("#addForm").validate(formValidator);

    $("#addForm").on("submit", function (e) {
        if ($("#addForm").valid()) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            var formData = new FormData(
                document.getElementById("addForm")
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
                        document.getElementById("addForm").reset();
                        const table = $("#tableData").DataTable();
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
            editCategoryName: {
                required: true,
            },
            editCategory: {
                required: true,
            },
            editSubCategoryName: {
                required: true,
            },
            editSubCategory: {
                required: true,
            },
        },
        messages: {
            editCategoryName : {
                required: "please enter a category name",
            },
            editSubCategoryName : {
                required: "please enter a sub category name",
            },
            editCategory: {
                required: "please select a category",
            },
            subCategory: {
                required: "please select a category",
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

    $("#editForm").validate(editFormValidator);

    $("#editForm").on("submit", function (e) {
        if ($("#editForm").valid()) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            var formData = new FormData(
                document.getElementById("editForm")
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
                        document.getElementById("editForm").reset();
                        $("#editImagePreview").hide();
                        $(".image-container").hide();
                        const table = $("#tableData").DataTable();
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
    var table = $('#tableData').DataTable({
        processing: true,
        serverSide: true,
        stateSave: type === 'initial' ? false : true,
        ajax: "categorysubcategory/getdetails", 
        columns: [
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'level_name', name: 'level_name' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // filter only apply while click Enter button
    $('#tableData_filter input').unbind(); 
    $('#tableData_filter input').on('keypress', function (e) {
        if (e.which === 13) { 
            table.search(this.value).draw(); 
        }
    });
}

// add new category action
const categorySelect    = document.getElementById('category');
const newCategoryDiv    = document.getElementById('new-category-div');
const subCategoryDiv    = document.getElementById('subCategoryDiv');
const newSubCategoryDiv = document.getElementById('new-sub-category-div');
categorySelect.addEventListener('change', function() {
    if (categorySelect.value === 'c_add') {
        newCategoryDiv.style.display    = 'block';
        subCategoryDiv.style.display    = 'none';
        newSubCategoryDiv.style.display = 'none';
    } else {
        newCategoryDiv.style.display    = 'none';
        subCategoryDiv.style.display    = 'block';
        newSubCategoryDiv.style.display = 'block';
    }
});

// subcategory action
// const subCategorySelect    = document.getElementById('subCategory');
// const newSubCategorySelect = document.getElementById('new-sub-category-div');
// subCategorySelect.addEventListener('change', function() {
//     if (subCategorySelect.value === 'sc_add') {
//         newSubCategorySelect.style.display = 'block';
//     } else {
//         newSubCategorySelect.style.display = 'none';
//     }
// });


// EDIT
window.editData = function(id) {
    $.get( 'categorysubcategory/' +id + '/edit', function(data) {
        document.getElementById("editForm").reset();
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('editOffCanvasRight'));
        offcanvas.show();
        console.log(data);
        
        

        if(data.parent_id == null){
            const editCategory                      = document.getElementById('editCategory');
            editCategory.value                      = data.id;

            const editCategoryNameDiv               = document.getElementById('editCategoryNameDiv');
            const editCategoryName                  = document.getElementById('editCategoryName');

            const editSubCategoryDiv                = document.getElementById('editSubCategoryDiv');
            // const editSubCategory                   = document.getElementById('editSubCategory');
            const editSubCategoryNameDiv            = document.getElementById('editSubCategoryNameDiv');
            const editSubCategoryName               = document.getElementById('editSubCategoryName');
            editCategoryNameDiv.style.display       = 'block';
            editCategoryName.value                  = data.name;

            editSubCategoryDiv.style.display            = 'none';
            editSubCategoryNameDiv.style.display        = 'none';
            
        }
        else{
            const editCategory                      = document.getElementById('editCategory');
            editCategory.value                      = data.parent_id;

            const editCategoryNameDiv               = document.getElementById('editCategoryNameDiv');
            const editCategoryName                  = document.getElementById('editCategoryName');

            editCategoryNameDiv.style.display       = 'none';
            editCategoryName.value                  = '';

            const editSubCategoryDiv                = document.getElementById('editSubCategoryDiv');
            const editSubCategory                   = document.getElementById('editSubCategory');
            const editSubCategoryNameDiv            = document.getElementById('editSubCategoryNameDiv');
            const editSubCategoryName               = document.getElementById('editSubCategoryName');

            editSubCategoryDiv.style.display        = 'block';
            editSubCategoryNameDiv.style.display    = 'block';
            editSubCategory.value                   = data.id;
            editSubCategoryName.value               = data.name;
        }

        
       
        $('#id').val(data.id);
        $('#editDescription').val(data.description);
        $('#currentImage').val(data.image);
        $("#editImagePreview").attr("src", data.image);
        $('#editImagePreview').show();
        $(".image-container").show();
        $(".image-container").css("display", "block");
        
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
                url: "categorysubcategory/delete/" + id,
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
                            const table = $('#tableData').DataTable();
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