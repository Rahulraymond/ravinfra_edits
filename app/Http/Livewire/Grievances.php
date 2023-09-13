<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Grievance;
class Grievances extends Component
{
    // use WithFileUplaods file traits.
    use WithFileUploads;

    public $name;
    public $phone;
    public $email;
    public $for;
    public $location;
    public $document;

    // define rules
    protected $rules = [
        'name' => 'required|min:3',
        'location' => 'required',
        'phone' => 'required|regex:/[0-9]{9}/',
        "email" => "email",
        "document" => "required",
        "for" => "required"
    ];

    // realtime check validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
 
    protected $messages = [
        'name.required' => 'Please enter your name.',
        'location.required' => 'Please enter your location.',
        'for.required' => 'Please select reason type.',
    ];
 
    // save the graeanced to database from here
    public function saveGrievances()
    {
        $validatedData = $this->validate();

        $validatedData['document'] = $this->document->store('document');
        
        // store data into database.
        Grievance::create($validatedData);

        // close grievances modal using dispatch.
        $this->dispatchBrowserEvent('closeModal'); 
        $this->reset(); 
    }

    public function render()
    {
        return view('livewire.grievances');
    }
}
