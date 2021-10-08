<?php

namespace App\Http\Controllers\Admin;

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
        $this->data['js'] = "Expenses.js";
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

        $validator = $request->validate([
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

    public function category(Request $request)
    {

        $this->data['keyword'] = Str::lower($request->get('keyword'));

        $this->data['categories'] = Category::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query->whereRaw("category LIKE  UPPER('{$this->data['keyword']}%')")
                    ->orWhereRaw("LOWER(descriptions)  LIKE  '{$this->data['keyword']}%'");
            }
        })->orderBy('created_at', "DESC")
            ->paginate($this->per_page);

        return view('admin.pages.expenseCategory', $this->data);
    }

    public function store_category(Request $request)
    {

        $validator =  $request->validate([
            'category' => 'required',
            'descriptions' => 'required'
        ]);

        DB::beginTransaction();
        try {

            $category = new Category;
            $category->category = $request->get('category');
            $category->descriptions = $request->get('description');
            $category->save();
            DB::commit();


            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Category Added Successfully.");
            return redirect()->route('admin.goto_category');
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

    public function get_category($id)
    {
        $data  = Category::where('id', $id)->first();

        return  $data;
    }

    public function update_category(Request $request)
    {

        $validator =  $request->validate([
            'category' => 'required',
            'description' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $category = Category::where('id', request('category_id'))->first();
            $category->category =  request('category');
            $category->descriptions = request('description');
            $category->save();
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Update Category Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return $category;
    }

    public function delete_category($id)
    {
        DB::beginTransaction();

        try {
            $category = Category::find($id);

            $category->delete();
            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Category Deleted Successfully.");
        } catch (\Throwable $e) {
            DB::rollback();;
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Errorss: Code #{$e->getMessage()}");
        }

        return   $category;
    }
}
