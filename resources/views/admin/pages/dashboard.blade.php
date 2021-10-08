@extends('admin.components.mainlayout')

@section('content')


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">My Expenses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>



    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Expense Category</h2>
                        </div>
                        <div class="col-sm-6">
                            <h2>Total</h2>
                        </div>
                    </div>
                    @forelse($expenses as $index => $value)
                        <div class="row">
                            <div class="col-sm-6">
                                <span style="font-size: 22px;font-weigth:500">{{ $value->category }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span
                                    style="font-size: 22px;font-weigth:500">₱{{ number_format($value->total_val, 2) }}</span>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
                <div class="col-sm-6">
                    <div class = "col-sm-12" id="piechart" style="width:800px; height: 500px;background-color:transparent"></div>
                </div>
            </div>
        </div>
    </section>



@endsection
