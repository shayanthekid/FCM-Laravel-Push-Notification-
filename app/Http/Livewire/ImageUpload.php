<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
class ImageUpload extends Component
{

    use WithFileUploads;
    public $image;

    public function upload(){
        $this->validate([
            'image' => 'image|max:10240', // 10MB Max
        ]);

        $path =  $this->image->store('images','public');
    // $filename =  $this->image->store('images','public', $image );
    // $request->headers->set('img', $filename->temporaryUrl());
    \Session::put('img',  $path);

    // $request->request->add(['img' => $filename->temporaryUrl()]);
        // dd($filename->temporaryUrl());

        // $this->image->store('images','public');
    }
    public function render()
    {
        return view('livewire.image-upload');
    }
}
