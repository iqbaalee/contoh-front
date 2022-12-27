@extends('layout.default') @section('content')

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.css"
/>
<link
    rel="stylesheet"
    href="{{
        asset(
            'adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'
        )
    }}"
/>

<style>
    .card-border {
        border: 3px solid #07bc4c;
    }

    #preview_image {
        display: block;
        width: 100%;
    }
</style>
<link rel="stylesheet" href="{{ asset('custom.css') }}" />

<div class="col-12">
    <button class="btn btn-sm btn-primary mb-2" onclick="addProduct()">
        Tambah
    </button>

    <div class="d-flex justify-content-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="row" id="listTable"></div>
    <div class="col">
        <div class="row d-flex justify-content-center align-items-center">
            <nav>
                <ul class="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="mealForm">
                @csrf
                <input type="hidden" name="id" id="id" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"
                                    >Nama Hidangan</label
                                >
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    id="name"
                                />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Harga</label>
                                <input
                                    type="number"
                                    min="1"
                                    name="price"
                                    class="form-control"
                                    id="price"
                                    value=""
                                />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"
                                    >Deskripsi</label
                                >
                                <textarea
                                    name="description"
                                    id="description"
                                    cols="2"
                                    rows="2"
                                    class="form-control"
                                ></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"
                                    >Stok Tersedia</label
                                >
                                <input
                                    type="number"
                                    name="stock"
                                    class="form-control"
                                    id="stock"
                                    min="1"
                                    max="200"
                                />
                            </div>
                            <div class="form-group">
                                <label for="">Gambar</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input
                                            name="image_upload"
                                            type="file"
                                            class="custom-file-input"
                                            id="image_upload"
                                            aria-describedby="inputGroupFileAddon03"
                                        />
                                        <label
                                            class="custom-file-label"
                                            for="inputGroupFile03"
                                            >Pilih Gambar</label
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-md-6 d-flex justify-content-center align-items-center"
                        >
                            <img
                                id="image_cropped"
                                width="100%"
                                src="{{ asset('images/lunch.png') }}"
                                class="rounded float-left mt-2"
                                alt="..."
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary submit-button">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="previewModal"
    tabindex="-1"
    aria-labelledby="previewModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100" style="overflow-x: hidden">
                    <img
                        id="preview_image"
                        class="rounded float-left"
                        alt="..."
                    />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="crop">
                    Pilih
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.js"></script>
<script src="{{
        asset('adminlte/plugins/sweetalert2/sweetalert2.min.js')
    }}"></script>
<script>
    const mealForm = document.querySelector("#mealForm");
    const filterForm = document.querySelector("#filterForm");
    let imageUpload = document.getElementById("image_upload");
    let imageCropped = document.getElementById("image_cropped");
    const previewImage = document.getElementById("preview_image");
    const spinnerBorder = document.querySelector(".spinner-border");
    let cropper;
    let dataImage = null;
    $(function () {
        getMeal();
    });

    imageUpload.addEventListener("change", function (e) {
        let files = e.target.files;
        let done = function (url) {
            previewImage.src = url;
            $("#previewModal").modal("show");
        };
        let reader;
        let file;
        let url;
        if (files && files.length > 0) {
            file = files[0];
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $("#previewModal")
        .on("shown.bs.modal", function () {
            cropper = new Cropper(previewImage, {
                aspectRatio: 4 / 3,
                viewMode: 3,
            });
        })
        .on("hidden.bs.modal", function () {
            cropper.destroy();
            cropper = null;
        });

    function addProduct() {
        $(".modal-title").html("Tambah Hidangan");
        $(".submit-button").html("Simpan");
        $("#exampleModal").modal("show");
    }

    document.getElementById("crop").addEventListener("click", (e) => {
        canvas = cropper.getCroppedCanvas({
            width: 600,
            height: 300,
        });

        canvas.toBlob(function (blob) {
            url = URL.createObjectURL(blob);
            const reader = new FileReader();
            reader.readAsDataURL(blob);

            reader.onloadend = function () {
                dataImage = reader.result;
                imageCropped.src = dataImage;
                //set width
                imageCropped.width = 600;
                imageCropped.height = 300;
            };
            $("#previewModal").hide();
        });
    });

    mealForm.addEventListener("submit", (e) => {
        e.preventDefault();
        let url;
        let method;
        let formData = new FormData(e.currentTarget);
        formData.append("photo", dataImage);

        for (var pair of formData.entries()) {
            console.log(pair[0] + ", " + pair[1]);
        }

        method = "POST";
        if (formData.get("id") == "") {
            url = "{{route('meal.store')}}";
        } else {
            url = "{{route('meal.update', '')}}" + `/${formData.get("id")}`;
            formData.append("_method", "PUT");
        }

        requestJs(url, method, formData)
            .then((res) => {
                if (res.status == true) {
                    $("#exampleModal").modal("hide");
                    location.reload();
                } else if (res.status == "error") {
                    Object.keys(res.message).forEach((key) => {
                        toastrNotif(res.message[key], "Error", "error");
                    });
                }
            })
            .catch((err) => {
                alert(err);
            });
    });

    function getMeal() {
        var url = "{{route('meal.ajax_get_meal')}}";

        requestJs(url, "GET").then((res) => {
            generateList(res);
        });
    }

    function generateList(res) {
        let tempData = "";
        let tempPagination = "";

        spinnerBorder.style.display = "none";

        res.data.links.forEach((element) => {
            newLabel = element.label.split(" ");
            if (newLabel.length > 1) {
                newLabel = newLabel[0] == "&laquo;" ? newLabel[1] : newLabel[0];
            } else {
                newLabel = newLabel[0];
            }
            let pagination = `<li class="page-item ${
                element.active
                    ? "active"
                    : element.url == null
                    ? "disabled"
                    : ""
            }"><a class="page-link" href="javascript:void(0)" onclick="getMealByPage('${
                element.url
            }')">${newLabel}</a></li>`;

            tempPagination += pagination;
        });
        document.querySelector(".pagination").innerHTML = tempPagination;

        res.data?.data?.forEach((element) => {
            let html = `<div class="col-md-3">
                <div class="card" style="width: 18rem;">
                        <img src="${
                            element?.photo != ""
                                ? element.photo
                                : "{{asset('images/lunch.png')}}"
                        }" class="card-img-top w-100 mx-auto" alt="image">
                        <div class="card-body">
                            <h2 class="mb-2 card-title font-weight-bold text-uppercase">${
                                element.name
                            }</h2>
                            <p style="font-size:14px" class="mt-2 card-text text-justify">${
                                element.description
                            }</p>
                            <p class="mt-2">Tersedia : <span class="badge-pill badge-danger">${
                                element.stock
                            }</span></p>
                           <div class="col">
                            <div class="row d-flex justify-content-between">
                                <button type="button" onclick="editMeal(${
                                    element.id
                                })" class="btn btn-primary"><i class="fas fa-pen"></i></button>
                                <button onclick="deleteMeal(
                                    ${element.id}
                                )" type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </div>
                           </div>

                        </div>
                </div>
                </div>`;

            tempData += html;
        });
        document.getElementById("listTable").innerHTML = tempData;
    }
    function getMealByPage(url) {
        newURL = url.split("?");
        var url = "{{route('meal.ajax_get_meal')}}?" + newURL[1];

        requestJs(url, "GET").then((res) => {
            generateList(res);
        });
    }
    const deleteMeal = (id) => {
        swalNotif(
            "Informasi",
            "Apakah anda yakin ingin menghapus data ini?",
            "warning",
            "Ya, Hapus",
            "Tidak"
        ).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append("_method", "DELETE");
                formData.append("_token", "{{csrf_token()}}");
                formData.append("id", id);
                requestJs(
                    `{{route('meal.delete', '')}}/${id}`,
                    "POST",
                    formData
                ).then((res) => {
                    if (res.status) {
                        location.reload();
                        spinnerBorder.style.display = "none";
                    } else {
                        if (res.msg != []) {
                            toastrNotif("", res.msg, "error");
                        } else {
                            Object.keys(res.message).forEach((key) => {
                                toastrNotif("", res.message[key], "error");
                            });
                        }
                    }
                });
            }
        });
    };

    function editMeal(id) {
        requestJs(`{{route('meal.detail', '')}}/${id}`, "GET").then((res) => {
            if (res.status) {
                $(".modal-title").html("Edit Hidangan");
                $(".submit-button").html("Update");
                $("#exampleModal").modal("show");
                document.getElementById("name").value = res.data.name;
                document.getElementById("description").value =
                    res.data.description;
                document.getElementById("stock").value = res.data.stock;
                document.getElementById("price").value = res.data.price;
                document.getElementById("id").value = res.data.id;

                imageCropped.src =
                    res.data.photo != ""
                        ? res.data.photo
                        : "{{asset('images/lunch.png')}}";
            } else {
                Object.keys(res.message).forEach((key) => {
                    toastrNotif(res.message[key], "error");
                });
            }
        });
    }

    detailTable = (product_id, order_id) => {
        console.log(product_id, order_id);
        let url = "{{route('table.detail', '')}}" + `/${product_id}`;
        let param = {
            order_id: order_id ?? "",
        };
        url = order_id != "" ? url + "?" + $.param(param) : url;
        requestJs(url, "GET").then((res) => {
            $(".modal-title").html("Detail Meja");
            $("#id").val(res.data?.id);
            $("#name").val(res.data?.name);
            $("#description").val(res.data?.description);

            $("#exampleModal").modal("show");
        });
    };
    dateFormat = (date = null) => {
        let d = new Date(date);
        let month = "" + (d.getMonth() + 1);
        let day = "" + d.getDate();
        let year = d.getFullYear();

        if (month.length < 2) month = "0" + month;
        if (day.length < 2) day = "0" + day;

        return [day, month, year].join("/");
    };

    $("#exampleModal").on("hidden.bs.modal", function () {
        mealForm.reset();
        imageCropped.src = "{{asset('images/lunch.png')}}";
    });
</script>
@endsection
