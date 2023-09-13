<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgressCreate;
use App\Http\Requests\ProgressUpdate;
use App\Models\Progress;
use App\Models\media;
use Illuminate\Http\Request;
use App\Models\Progress as ModelsProgress;
use App\Rules\IsDescAvailable;
class ProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $search = request()->toArray();

        $query =  Progress::where(function ($q) use ($search) {

            if(isset($search['year'])){

                $q->where('year','LIKE','%'.$search['year'].'%');
                
            }

            if(isset($search['month'])){

                $q->where('month','LIKE','%'.$search['month'].'%');

            }

            if(isset($search['week'])){

                $q->where('week','LIKE','%'.$search['week'].'%');

            }

        });

        $progress = $query->orderBy('id','desc')->paginate(10);

        return view("admin.progress.index", compact('progress'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.progress.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'week' => 'required',

            'year' => 'required',
            
            'month' => 'required',
            
            'caption.*' => 'required',

            'media' => 'max:10|required',

            'media.*' => 'mimes:jpeg,jpg,png,mp4,3gp,mov,wmv,mov,gif|max:20000',

            'desc' =>[new IsDescAvailable($request->year, $request->week, $request->month)],
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $messages = $validator->getMessageBag();
            
            return redirect()->back()->with('error', $messages);
        }        

        $progress = Progress::create($request->only('week','year','month','desc'));

        if(isset($request->media)){

            foreach ($request->media as $key => $media) {
                
                $caption = $request->caption;

                $media_image = new media;
                
                $media_image->progress_id = $progress->id;
                
                $media_image->media =  $media->store('media');

                $media_image->caption =  $caption[$key];
                
                $media_image->save();
            }
        }

        if ($progress) {

            return redirect()->route('progress.index')->with("message", 'Progress is added successfully.');
        }
        return redirect()->route('progress.index')->with("error", 'Something went wrong.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Progress  $progress
     * @return \Illuminate\Http\Response
     */
    public function edit(Progress $progress)
    {
        $medias = media::where('progress_id',$progress->id)->orderBy('id','DESC')->get();

        return view('admin.progress.edit',compact('progress','medias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Progress  $progress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Progress $progress)
    {

        $rules = [
            'week' => 'required',

            'year' => 'required',
            
            'month' => 'required',
            
            'media.*' => 'mimes:jpeg,jpg,png,gif,mp4,3gp,mov,wmv,mov|max:20000',

        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $messages = $validator->getMessageBag();
            
            return redirect()->back()->with('error', $messages);
        }        

        $progress->update($request->only('week','year','month','desc'));

        if(isset($request->media)){
            ini_set('max_execution_time', 180);
            foreach ($request->media as $key => $media) {

                $caption = $request->caption;

                $media_image = new media;
                
                $media_image->progress_id = $progress->id;
                
                $media_image->media =  $media->store('media');

                $media_image->caption =  $caption[$key];
                
                $media_image->save();
            }
        }

        if ($progress) {

            return redirect()->route('progress.edit',$progress->id)->with("message", 'Progress is updated successfully.');
        }
        return redirect()->route('progress.edit',$progress->id)->with("error", 'Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Progress  $progress
     * @return \Illuminate\Http\Response
     */
    public function destroy(Progress $progress)
    {
        if ($progress) {
            
            $media_datas = media::where('progress_id',$progress->id)->pluck('media','id')->toArray();

            if(isset($media_datas) && is_array($media_datas)){
            
                foreach($media_datas as $key => $media_file){
            
                    $media = media::find($key);
            
                    if($media){
            
                        \Storage::delete($media_file);
            
                        $media->delete();
                    }
            
                }
            
            }
            
            $progress->delete();
            
            return redirect()->route('progress.index')->with("message", "Progress is deleted successfully.");
        }
        return redirect()->route('progress.index')->with("error", "Something went wrong.");
    }


    public function removeMedia($media_id){

        $media_data = media::find($media_id);

        if($media_data){
            \Storage::delete($media_data->media);

            $media_data->delete();

            return 1;
        }
        return 0;
    }

}
