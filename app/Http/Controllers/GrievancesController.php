<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grievance;

class GrievancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');

        $query =  Grievance::where(function ($q) use ($search) {

            $q->orWhere('name','LIKE','%'.$search.'%')

                ->orWhere('email','LIKE','%'.$search.'%')
            
                ->orWhere('phone','LIKE','%'.$search.'%')

                ->orWhere('location','LIKE','%'.$search.'%')

                ->orWhere('for','LIKE','%'.$search.'%');

        });

        $grievances = $query->orderBy('id','desc')->paginate(10);

        return view('admin.grievances.index',compact('grievances'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grievances = Grievance::find($id);
        if($grievances){
            \Storage::delete($grievances->document);
            $grievances->delete();
            return redirect()->route('grievances.index')->with("message","Grievance is deleted successfully.");
        }
        return redirect()->route('grievances.index')->with("error","Something went wrong.");
    }
}
