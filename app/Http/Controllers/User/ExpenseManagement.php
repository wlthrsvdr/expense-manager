<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\{Expenses, Category};
use DB;
use Str;

class ExpenseManagement extends Controller
{

    protected $data;
    protected $per_page;


    public function __construct()
    {
        $this->data['js'] = "ExpenseManagement.js";
        $this->middleware('system.guest', ['except' => "logout"]);
    }

    public function expenses(Request $request)
    {


        $this->data['keyword'] = Str::lower($request->get('keyword'));

        $this->data['expenses'] = Expenses::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query->whereRaw("expense_category LIKE  UPPER('{$this->data['keyword']}%')")
                    ->orWhereRaw("LOWER(amount)  LIKE  '{$this->data['keyword']}%'");
            }
        })->orderBy('created_at', "DESC")
            ->paginate($this->per_page);

        return view('admin.pages.expenses', $this->data);
    }

    public function store(Request $request)
    {
        $validator =   $request->validate([
            'expense_category' => 'required|string',
            'date' => 'required|after:today',
            'amount' => 'required|numeric|min:0|not_in:0',
        ]);


        DB::beginTransaction();
        try {

            $expense = new Expenses;
            $expense->expense_category = $request->get('expense_category');
            $expense->amount = $request->get('amount');
            $expense->date = $request->get('date');
            $expense->save();
            DB::commit();


            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Expense Added Successfully.");
            return redirect()->route('admin.expenses');
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
            return redirect()->back();
        }

        callback:
        session()->flash('notification-status', "failed");
        return redirect()->back();
    }

    public function get_expense($id)
    {
        $data  = Expenses::where('id', $id)->first();

        return  $data;
    }

    public function get_categories()
    {

        $data = Category::all();

        return $data;
    }

    public function update(Request $request)
    {

        $validator =  $request->validate([
            'expense_category' => 'required|string',
            'amount' => 'required|numeric|min:0|not_in:0',
        ]);


        DB::beginTransaction();

        try {
            $expense = Expenses::where('id', request('expense_id'))->first();
            $expense->expense_category = $request->get('expense_category');
            $expense->amount = $request->get('amount');
            $expense->date = $request->get('date');
            $expense->save();
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Update Expense Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return   $expense;
    }

    public function delete_expense($id)
    {
        DB::beginTransaction();

        try {
            $expense = Expenses::find($id);

            $expense->delete();



            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Expense Deleted Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return   $expense;
    }
}
