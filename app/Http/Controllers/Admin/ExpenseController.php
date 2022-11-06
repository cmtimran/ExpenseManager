<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExpenseRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data['expenses'] = DB::table('expenses')
        //     ->join('users', 'expenses.user_id', '=', 'users.id')
        //     ->select('expenses.*', 'users.name as name')
        //     ->get()->toArray();
        // $expenses = Expense::whereUserId(id())->latest()->paginate(100);
        $expenses = Expense::latest()->paginate(100);
        //  echo '<pre>';
        //  print_r($data);
        //  die;
        return view('admin.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense_categories = ExpenseCategory::get()->pluck('name', 'id');
        $expense_users = User::get()->pluck('name', 'id');


        return view('admin.expenses.create', compact('expense_categories', 'expense_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        Expense::create($request->validated() + [
                'user_id' => auth()->id(),
                'currency_id' => auth()->user()->currency_id,
            ]);

        return redirect()->route('admin.expenses.index')->with([
            'message' => 'Success Created !',
            'alert-info' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $expense_categories = ExpenseCategory::get()->pluck('name', 'id')->prepend('Please Select');
        $expense_users = User::get()->pluck('name', 'id');

        return view('admin.expenses.edit', compact('expense', 'expense_categories', 'expense_users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request,Expense $expense)
    {
        $expense->update($request->validated() + [
                'user_id' => auth()->id(),
                'currency_id' => auth()->user()->currency_id,
            ]);

        return redirect()->route('admin.expenses.index')->with([
            'message' => 'Success Updated !',
            'alert-info' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->back()->with([
            'message' => 'Success Deleted !',
            'alert-info' => 'danger'
        ]);
    }
}
