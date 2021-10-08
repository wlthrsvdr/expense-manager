<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\{User};
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
        $this->data['js'] = "DashboardUser.js";
    }


    public function index()
    {

        $this->data['expenses'] = DB::table('expense_category')
            ->select("expense_category.category", \DB::raw('SUM(expenses.amount) as total_val'))
            ->join("expenses", "expenses.expense_category", "=", "expense_category.category")
            ->groupBy("expense_category.category")
            ->get();

        return view('users.pages.dashboard', $this->data);
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
            // $chartData .= "['" . $list->category . "', " . $list->total_val . "],";
            $chartData[++$key] = [$value->category, $value->total_val];
        }

        // $pieChartData = rtrim($chartData, ",");

        return  json_encode($chartData);
    }
}
