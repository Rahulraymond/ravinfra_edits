<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');

        $query =  Contact::where(function ($q) use ($search) {

            $q->orWhere('name','LIKE','%'.$search.'%')

                ->orWhere('email','LIKE','%'.$search.'%')
            
                ->orWhere('phone','LIKE','%'.$search.'%')

                ->orWhere('query','LIKE','%'.$search.'%')

                ->orWhere('details','LIKE','%'.$search.'%');

        });

        $contacts = $query->orderBy('id','desc')->paginate(10);

        return view('admin.contact.index',compact('contacts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if($contact){
        
            return view('admin.contact.view',compact('contact'));
        
        }
        return redirect()->back()->with('error',"This contact not found in our records.");
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if($contact){
        
            $contact->delete();
        
            return redirect()->route('contacts.index')->with('message','Contact is deleted successfully.');
        
        }
        return redirect()->route('contacts.index')->with('error','Something went wrong.');
    }
}
