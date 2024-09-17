<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sortby = Request::get('sortby', 'id');
        if(!in_array($sortby, ['id', 'name', 'email'])) {
            $sortby = 'id';
        }
        $sort = Request::get('sort', 'asc');
        if(!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }
        return Inertia::render('Employees/Index', [
            'department_id' => Request::get('department_id'),
            'employees' => Employee::orderBy($sortby, ($sort == 'asc') ? 'ASC' : 'DESC')
                ->with('department')
                ->whereDepartment(Request::get('department_id'))
                ->get()
                ->transform(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'department' => $employee->department->name ?? null,
                        'can' => [
                            'delete' => auth()->user()->can('delete', $employee),
                            'edit' => auth()->user()->can('update', $employee),
                        ],
                    ];
                }),
            'departments' => function () {
                return Department::orderBy('name')->get()
                    ->transform(function ($d) {
                        return [
                            'id' => $d->id,
                            'label' =>  $d->name
                        ];
                    });
            },
            'sortby' => $sortby,
            'sort' => $sort,
            'can' => [
                'create' => auth()->user()->can('create', Employee::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Employees/Create', [
            'departments' => Department::orderBy('name')->get()
                ->transform(function ($d) {
                    return [
                        'id' => $d->id,
                        'label' =>  $d->name
                    ];
                })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        Employee::create($request->all());
        return Redirect::route('employees.index')->with('success', 'Employee created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return Inertia::render('Employees/Edit', [
            'employee' => [
                'id' => $employee->id,
                'department_id' => $employee->department->id,
                'name' => $employee->name,
                'email' => $employee->email,
            ],
            'departments' => Department::orderBy('name')->get()
                ->transform(function ($d) {
                    return [
                        'id' => $d->id,
                        'label' =>  $d->name
                    ];
                })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());
        return Redirect::route('employees.index')->with('success', 'Employee Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return Redirect::back()->with('success', 'Employee deleted.');
    }
}
