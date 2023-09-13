<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Contact as PDContact;

class Contact extends Component
{
    public $name;
    public $email;
    public $phone;
    public $query;
    public $details;

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'phone' => 'required|regex:/[0-9]{9}/',
        'query' => 'required',
        'details' => 'required'
    ];

    // realtime check validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // save the graeanced to database from here
    public function saveContact()
    {
        $validatedData = $this->validate();

        // store data into database.
        PDContact::create($validatedData);

        // show flash messages.
        session()->flash('message', "We will contact you soon!! Thanks you for contact us.");

        // close grievances modal using dispatch.
        $this->reset(); 
    }
    public function render()
    {
        return view('livewire.contact');
    }
}
