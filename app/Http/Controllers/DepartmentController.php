<?php

namespace App\Http\Controllers;

use App\Models\Department;
//use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DepartmentController extends Controller
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

        return Inertia::render('Departments/Index', [
            'departments' => Department::orderBy($sortby, ($sort == 'asc') ? 'ASC' : 'DESC')->paginate(10)
                ->through(function($department) {
                    return [
                        'id' => $department->id,
                        'name' => $department->name,
                        'phone' => $department->phone,
                        'email' => $department->email,
                        'can' => [
                            'delete' => auth()->user()->can('delete', $department),
                            'edit' => auth()->user()->can('update', $department),
                        ],
                    ];
                }),
            'sortby' => $sortby,
            'sort' => $sort,
            'can' => [
                'create' => auth()->user()->can('create', Department::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Departments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {   
        $department = Department::create($request->all());        
        return Redirect::route('departments.index')->with('success', 'Department Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return Inertia::render('Departments/Edit', [
            'department' => [
                'id' => $department->id,
                'name' => $department->name,
                'email' => $department->email,
                'phone' => $department->phone,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->all());
        return Redirect::route('departments.index')->with('success', 'Department Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return Redirect::route('departments.index')->with('success', 'Department Deleted Successfully.');
    }
}
