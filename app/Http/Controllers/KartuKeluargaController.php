<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;

class KartuKeluargaController extends Controller
{
	public function __construct(
								Request $request, 
								KartuKeluarga $kartukeluarga, 
								Penduduk $penduduk
							)
	{
		$this->kartukeluarga 	= $kartukeluarga;
		$this->penduduk 		= $penduduk;
		$this->request 			= $request;
	}

    public function index()
    {
    	$kartukeluarga = $this->kartukeluarga->paginate(10);

    	return view('kartukeluarga.index', compact('kartukeluarga'));
    }

    public function create()
    {
    	return $this->form();
    }

    public function edit($id)
    {
    	return $this->form($id);
    }

    public function form($id = null){
        $cariKartuKeluarga = $this->kartukeluarga->find($id);

        if ($cariKartuKeluarga) {
            session()->flashInput($cariKartuKeluarga->toArray());
            $action = route('kartukeluarga.update',$id);
            $method = 'PUT';
        }else{
            $action = route('kartukeluarga.store');
            $method = 'POST';
        }

       $penduduk = $this->penduduk->where('kk_status', 1)->get();

        return view('kartukeluarga.form',compact(
        	'action', 'method', 'penduduk'
        ));
    }

    public function store(){
        return $this->save();
    }

    public function update($id){
        return $this->save($id);
    }

    public function save($id = null){
        if ($id) {
            $kartukeluarga = $this->kartukeluarga->find($id);
        }else{
            $kartukeluarga = $this->kartukeluarga;
        }

        $input = $this->request->except('_token');
        // return $input;

        // $this->validate(request(),[
        //   'nik'  => 'required',
        //   'nama'  => 'required',
        // ]);

        $kartukeluarga->nomor  		= request('nomor');
        $kartukeluarga->penduduk_id	= request('penduduk_id');
        $kartukeluarga->save();

        return redirect()->route('kartukeluarga.index');
    }

    public function destroy($id)
    {
    	$kartukeluarga = $this->kartukeluarga->find($id);
    	$kartukeluarga->delete();

    	return redirect()->back();
    }
}