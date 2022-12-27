@extends('layout/default') @section('content')

<div class="col-md-6">
    <div class="card">
        <form
            id="profileForm"
            method="POST"
            action="{{ route('auth.update_password') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="PUT" />
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Password Lama</label>
                    <div class="input-group mb-3">
                        <input
                            type="password"
                            class="form-control"
                            name="old_password"
                            id="old_password"
                        />
                        <div class="input-group-append">
                            <span class="input-group-text" id="show-old"
                                ><i class="fas fa-eye"></i
                            ></span>
                        </div>
                    </div>
                </div>
                @if ($errors->has('old_password'))
                <span
                    class="text-danger"
                    >{{ $errors->first('old_password') }}</span
                >
                @endif
                <div class="form-group">
                    <label for="exampleInputEmail1">Password Baru</label>
                    <div class="input-group mb-3">
                        <input
                            type="password"
                            class="form-control"
                            name="new_password"
                            id="new_password"
                        />
                        <div class="input-group-append">
                            <span class="input-group-text" id="show-new"
                                ><i class="fas fa-eye"></i
                            ></span>
                        </div>
                    </div>
                </div>
                @if ($errors->has('new_password'))
                <span
                    class="text-danger"
                    >{{ $errors->first('new_password') }}</span
                >
                @endif
                <div class="form-group">
                    <label for="exampleInputEmail1">Konfirmasi Password</label>
                    <div class="input-group mb-3">
                        <input
                            type="password"
                            class="form-control"
                            name="conf_password"
                            id="conf_password"
                        />
                        <div class="input-group-append">
                            <span class="input-group-text" id="show-conf"
                                ><i class="fas fa-eye"></i
                            ></span>
                        </div>
                    </div>
                </div>
                @if ($errors->has('conf_password'))
                <span
                    class="text-danger"
                    >{{ $errors->first('conf_password') }}</span
                >
                @endif

                <button type="submit" class="btn btn-sm btn-primary">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    show_old = document.querySelector("#show-old");
    show_new = document.querySelector("#show-new");
    show_conf = document.querySelector("#show-conf");
    old_password = document.querySelector("#old_password");
    new_password = document.querySelector("#new_password");
    conf_password = document.querySelector("#conf_password");
    show_old.addEventListener("click", (e) => {
        e.preventDefault();

        //show password
        if (old_password.type === "password") {
            old_password.type = "text";
            show_old.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            old_password.type = "password";
            show_old.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
    show_new.addEventListener("click", (e) => {
        e.preventDefault();

        //show password
        if (new_password.type === "password") {
            new_password.type = "text";
            show_new.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            new_password.type = "password";
            show_new.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
    show_conf.addEventListener("click", (e) => {
        e.preventDefault();

        //show password
        if (conf_password.type === "password") {
            conf_password.type = "text";
            show_conf.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            conf_password.type = "password";
            show_conf.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
</script>
@endsection
