@extends('admin.layouts.master')
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('category.list')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="categoryform" name="categoryform" enctype="multipart/form">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="{{$category->name}}"
                                    class="form-control" placeholder="Name">
                                <p> </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" value="{{$category->slug}}"
                                    class="form-control" placeholder="Slug">
                                <p> </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="col-mb-3">
                                <input type="hidden" id="image_id" name="image_id" value="">
                                <lable for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                            </div>
                            @if(!empty($category->image))
                            <div>
                                <img width="100" src="{{asset('uplodes\category\thumb/'.$category->image)}}" alt="">
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{($category->status == 1)? 'selected' : ''}} value="1">Active</option>
                                    <option {{($category->status == 0)? 'selected' : ''}} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{route('category.list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('coustomjs')

<script>
$("#categoryform").submit(function(e) {
    e.preventDefault();
    var FormData = $(this).serializeArray();
    $.ajax({
        url: '{{route ("category.update",$category->id) }}',
        type: 'put',
        data: FormData,
        dataType: 'json',

        success: function(response) {
            if (response['success']) {
       
                window.location.href = "{{ route('category.list') }}";

                $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                    .html("");
                $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                    .html("");

            } else {

                if (response['notFound'] == true) {
                    window.location.href = "{{ route('category.list') }}";

                }
           
                var errors = response['errors'];
                if (errors && typeof errors === 'object') {
                    if (errors['name']) {
                        $("#name").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback')
                            .html(errors['name']);
                    } else {
                        $("#name").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                    }

                    if (errors['slug']) {
                        $("#slug").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback')
                            .html(errors['slug']);
                    } else {
                        $("#slug").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                    }
                }
            }
        },
        error: function(jqXHR, exception) {

            console.log("Somthing failed");
        }

    });
});

//Dropzone pluging--
Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });
    },
    url: "{{ route('temp-images.create') }}",
    maxFiles: 1,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(file, response) {
        $("#image_id").val(response.image_id);
        //console.log(response)
    }
});
</script>

<script>
$('#name').keyup(function() {

    var abc = $(this).val();
    console.log(abc)
    $('#slug').val(abc);

});
</script>

@endsection