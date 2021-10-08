<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{User, Expenses};
use DB;

class MainController extends Controller
{
    protected $data = array();
    protected $per_page;

    public function __construct()
    {
        parent::__construct();
        array_merge($this->data, parent::get_data());
        $this->data['page_title'] .= " :: Dashboard";
        $this->data['js'] = "Dashboard.js";
    }


    public function index()
    {

        $this->data['expenses']  = DB::table('expense_category')
            ->select("expense_category.category", \DB::raw('SUM(expenses.amount) as total_val'))
            ->join("expenses", "expenses.expense_category", "=", "expense_category.category")
            ->groupBy("expense_category.category")
            ->get();

        $chartData = "";

        foreach ($this->data['expenses'] as $list) {
            $chartData .= "['" . $list->category . "', " . $list->total_val . "]";
        }
        $this->data['chartData'] = rtrim($chartData, ",");

        return view('admin.pages.dashboard',   $this->data);
    }

    public function chartData(Request $request)
    {
        $expenses  = DB::table('expense_category')
            ->select("expense_category.category", \DB::raw('SUM(expenses.amount) as total_val'))
            ->join("expenses", "expenses.expense_category", "=", "expense_category.category")
            ->groupBy("expense_category.category")
            ->get();

        $chartData[] = ["Category", "Total Amount"];

        foreach ($expenses as $key => $value) {

            $chartData[++$key] = [$value->category, $value->total_val];
        }


        return  json_encode($chartData);
    }
}
