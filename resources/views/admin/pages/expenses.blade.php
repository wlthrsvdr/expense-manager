@extends('admin.components.mainlayout')

@section('content')

    <div class="row">
        <div class="col">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Expense</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Expense Management > Expenses</a></li>
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
                <a data-toggle="modal" data-target="#add_expense_modal" class="btn btn-md btn-success mr-2">Add
                    Expense</a>
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
                                        <th>Expense Category</th>
                                        <th>Amount</th>
                                        <th>Entry Date</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $index => $value)
                                        <tr>
                                            <td>
                                                <div class="mb5">
                                                    {{ $value->expense_category }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($value->amount)
                                                    <div class="mb5">
                                                        {{ number_format($value->amount, 2) }}
                                                    </div>
                                                @else
                                                    <div class="mb5">{{ '-' }}</div>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="mb5">
                                                    {{ $value->date }}
                                                </div>
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
                                                        onclick="getExpenseInfo({{ $value->id }})">Update</a>
                                                    <a class="dropdown-item" style="cursor: pointer"
                                                        onclick="deleteExpense({{ $value->id }})">Delete</a>
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
                        @if ($expenses->total() > 0)
                            <nav class="mt-2">
                                <p>Showing <strong>{{ $expenses->firstItem() }}</strong> to
                                    <strong>{{ $expenses->lastItem() }}</strong> of
                                    <strong>{{ $expenses->total() }}</strong>
                                    entries
                                </p>
                                {!! $expenses->appends(request()->query())->render() !!}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add_expense_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" method="POST">
                {!! csrf_field() !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Expense Category</label>
                                <select class="form-control  mb-2 mr-sm-2" id="expense_category_id" name="expense_category"
                                    value="{{ old('expense_category') }}">
                                    <option value=""> - Select Expense Category - </option>
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Amount</label>
                                <input type="number" placeholder="Enter Amount Here.." class="form-control" name="amount"
                                    value="{{ old('amount') }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Entry Date</label>
                                <input type="date" placeholder="Enter Entry Date Here.." class="form-control" name="date"
                                    value="{{ old('date') }}">
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

    <div class="modal fade" id="update_expense_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="update_expense_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Category Informations</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <input type="hidden" class="form-control" name="expense_id"
                                    value="{{ old('expense_id') }}" id="expense_id">

                                <input type="hidden" class="form-control" name="category_field_hidden"
                                    value="{{ old('category_field_hidden') }}" id="category_field_hidden">

                                <label>Expense Category</label>
                                <select class="form-control  mb-2 mr-sm-2" id="expense_category_update_id"
                                    name="expense_category" value="{{ old('expense_category') }}">
                                    <option value=""> - Select Expense Category - </option>
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Amount</label>
                                <input type="number" placeholder="Enter Amount Here.." class="form-control" name="amount"
                                    value="{{ old('amount') }}" id="amount_id">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Entry Date</label>
                                <input type="date" placeholder="Enter Entry Date Here.." class="form-control" name="date"
                                    value="{{ old('date') }}" id="date_id">
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
