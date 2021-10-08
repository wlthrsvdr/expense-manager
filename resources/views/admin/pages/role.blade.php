@extends('admin.components.mainlayout')

@section('content')

    <div class="row">
        <div class="col">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Roles</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">User Management > Roles</a></li>
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
                <a data-toggle="modal" data-target="#add_role_modal" class="btn btn-md btn-success mr-2">Add Role</a>
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
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Description</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $index => $value)
                                        <tr>
                                            <td>
                                                <div class="mb5">
                                                    {{ $value->role }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($value->description)
                                                    <div class="mb5">
                                                        {{ $value->description }}
                                                    </div>
                                                @else
                                                    <div class="mb5">{{ '-' }}</div>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="mb5">
                                                    {{ $value->created_at }}
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-primary btn-raised dropdown-toggle"
                                                    data-toggle="dropdown">Actions <span
                                                        class="caret"></span></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">

                                                    <a class="dropdown-item" id="update_modal_show" style="cursor: pointer"
                                                        onclick="getRoleInfo({{ $value->id }})">Update</a>
                                                    <a class="dropdown-item" id="update_modal_show" style="cursor: pointer"
                                                        onclick="deleteRole({{ $value->id }})">Delete</a>
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
                        @if ($roles->total() > 0)
                            <nav class="mt-2">
                                <p>Showing <strong>{{ $roles->firstItem() }}</strong> to
                                    <strong>{{ $roles->lastItem() }}</strong> of
                                    <strong>{{ $roles->total() }}</strong>
                                    entries
                                </p>
                                {!! $roles->appends(request()->query())->render() !!}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add_role_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" method="POST">
                {!! csrf_field() !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Role</label>
                                <input type="text" placeholder="Enter Role Here.." class="form-control" name="role"
                                    value="{{ old('role') }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                    name="description" value="{{ old('description') }}"></textarea>
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

    <div class="modal fade" id="update_role_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="update_role_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Role Informations</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <input type="hidden" class="form-control" name="role_id" value="{{ old('role_id') }}"
                                    id="role_id">
                                <label>Role</label>
                                <input type="text" placeholder="Enter Role Here.." class="form-control" name="role"
                                    value="{{ old('role') }}" id="role_info">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" name="description"
                                    value="{{ old('description') }}" id="description_id"></textarea>
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
