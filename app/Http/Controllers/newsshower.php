<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\fire;

class newsshower extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=fire::all();
        return view('newsindex',['fires'=>$data]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addnews');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->subject==NULL){
            return redirect()->route('fires.create')->with('error','Please fill all the fields');
        }
        
        if($request->description==NULL){
            return redirect()->route('fires.create')->with('error','Please fill all the fields');
        }
        $nw= new fire;
        $nw->subject=$request-> subject;
        $nw->description=$request-> description;
        $nw->save();
        return redirect() ->route('fires.index') ->with('success', 'news added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    
        $data=fire::all();
      
        return view('home',['newsez'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $data=fire::find($id);
        $data->delete();
        return redirect() ->route('fires.index') ->with('success', 'news deleted successfully!');
    }
}