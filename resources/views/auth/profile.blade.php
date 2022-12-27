@extends('layout/default') @section('content')

<div class="col-md-6">
    <div class="card">
        <form
            id="profileForm"
            method="POST"
            action="{{ route('auth.update_profile') }}"
        >
            @csrf
            <input type="hidden" name="_method" value="PUT" />
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        id="role_name"
                        value="{{$profile->name}}"
                        required
                    />
                </div>
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <div class="form-group">
                    <label for="exampleInputEmail2">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        id="role_email"
                        value="{{$profile->email}}"
                        required
                    />
                </div>
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <div class="form-group">
                    <label for="exampleInputEmail3">Hak Akses</label>
                    <select name="role_id" id="role_id" class="form-control">
                        <option value="">-Pilih Hak Akses-</option>
                        @foreach($role as $v)
                        <option value="{{$v->id}}" {{ ($v->
                            id == $profile->role_id) ? 'selected' : ''}}>{{$v->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('role_id'))
                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                @endif
                <button type="submit" class="btn btn-sm btn-primary">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
