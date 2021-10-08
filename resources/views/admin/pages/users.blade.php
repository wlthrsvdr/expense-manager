@extends('admin.components.mainlayout')

@section('content')

    <div class="row">
        <div class="col">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">User Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">User Management</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <span class="btn-group float-right" style="cursor: pointer; color:#eee">
                <a data-toggle="modal" data-target="#add_user_modal" class="btn btn-md btn-success mr-2">Add User</a>
            </span>
        </div>
    </div>

    <div class="col-12 grid-margin stretch-card mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        @if (session()->has('notification-status'))
                            <div class="alert alert-{{ in_array(session()->get('notification-status'), ['failed', 'error', 'danger']) ? 'danger' : session()->get('notification-status') }}"
                                role="alert">
                                {{ session()->get('notification-msg') }}
                            </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>User Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $value)
                                        <tr>
                                            <td>
                                                <div class="mb5">
                                                    {{ $value->name }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($value->email)
                                                    <div class="mb5">
                                                        {{ $value->email }}
                                                    </div>
                                                @else
                                                    <div class="mb5">{{ '-' }}</div>
                                                @endif

                                            </td>
                                            <td>
                                                @if ($value->user_role)
                                                    @if ($value->user_role == "Admin")
                                                        <div class="mb5">{{ 'Admin' }}</div>
                                                    @else
                                                        <div class="mb5">{{ 'User' }}</div>
                                                    @endif
                                                @else
                                                    <div class="mb5">{{ '-' }}</div>
                                                @endif
                                            </td>

                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-primary btn-raised dropdown-toggle"
                                                    data-toggle="dropdown">Actions <span
                                                        class="caret"></span></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">
                                                    <a class="dropdown-item" id="update_modal_show" style="cursor: pointer"
                                                        onclick="getUserInfo({{ $value->id }})">Update</a>
                                                    <a class="dropdown-item" id="update_modal_show" style="cursor: pointer"
                                                        onclick="deleteUser({{ $value->id }})">Delete</a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <p>No record found yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($users->total() > 0)
                            <nav class="mt-2">
                                <p>Showing <strong>{{ $users->firstItem() }}</strong> to
                                    <strong>{{ $users->lastItem() }}</strong> of
                                    <strong>{{ $users->total() }}</strong>
                                    entries
                                </p>
                                {!! $users->appends(request()->query())->render() !!}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" method="POST">
                {!! csrf_field() !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Name</label>
                                <input type="text" placeholder="Enter Name Here.." class="form-control" name="name"
                                    value="{{ old('name') }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label>Email</label>
                                <input type="email" placeholder="Enter Email Here.." class="form-control" name="email"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Password</label>
                                <input type="password" placeholder="Enter Password Here.." class="form-control"
                                    name="password" value="{{ old('password') }}">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Confirm Password</label>
                                <input type="password" placeholder="Enter Confirm Password Here.." class="form-control"
                                    name="confirm_password" value="{{ old('confirm_password') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>User Role</label>
                                <select class="form-control  mb-2 mr-sm-2" id="user_role_id" name="user_role"
                                    value="{{ old('user_role') }}">
                                    <option value=""> - Select User Role - </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="update_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="update_user_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update User Informations</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" class="form-control" name="users_id" value="{{ old('users_id') }}"
                                id="users_id">

                            <div class="col-sm-12 form-group">
                                <label>Name</label>
                                <input type="text" placeholder="Enter Name Here.." class="form-control" name="name"
                                    value="{{ old('name') }}" id="update_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Email</label>
                                <input type="text" placeholder="Enter Email Here.." class="form-control" name="email"
                                    value="{{ old('email') }}" id="update_email">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>User Role</label>
                                <select class="form-control  mb-2 mr-sm-2" id="user_role_update_id" name="user_role"
                                    value="{{ old('user_role') }}">
                                    <option value=""> - Select User Role - </option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="userRole">User Role</label>
                                <select class="form-control" name="user_role" value="{{ old('user_role') }}"
                                    id="update_user_role">
                                    <option>Select User Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                            </div>
                        </div> --}}

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete_prompt_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h4>Are you you want to delte this data?</h4>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-primary" id="yes_delete">Yes</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-danger" id="cancel_delete">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
