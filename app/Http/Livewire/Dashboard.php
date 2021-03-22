<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Animal;
use Livewire\WithPagination;
use Livewire\WithFileUploads;


class Dashboard extends Component
{
    use WithFileUploads;
    use withPagination;
    public  $nama,
            $jenis,
            $makanan,
            $file_name,
            $modal = false,
            $animal_id,
            $old_file_name,
            $deleteConfirmation = false,
            $deleteId;

    public function render()
    {
        $animals = Animal::latest()->simplePaginate(10);
        return view('dashboard',[
            'animals' => $animals
        ]);
    }

    public function openModal()
    {
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->resetField();
    }

    public function resetField()
    {
        $this->nama = "";
        $this->makanan = "";
        $this->jenis = "";
        $this->file_name = null;
        $this->deleteId = null;
    }

    public function store()
    {
        $imageValidation = '';
        if($this->file_name != $this->old_file_name){
            $imageValidation = "required|image|mimes:jpg,jpeg,png|max:1024";
        }

        $this->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'makanan' => 'required',
            'file_name' => $imageValidation
        ]);


        if($this->file_name != $this->old_file_name){
            $fileName = $this->file_name->store('animal');
        }else{
            $fileName = $this->file_name;
        }

        $result = Animal::updateOrCreate(['id' => $this->animal_id],[
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'makanan' => $this->makanan,
            'file_name' => $fileName
        ]);
        if ($result != "0") {
            session()->flash('message', 'berhasil mengupdate data');
        }else{
            session()->flash('errMessage', 'gagal mengupdate data');
        }
        $this->closeModal();
        $this->resetField();

    }

    public function edit($id)
    {
        $animal = Animal::find($id);
        $this->nama = $animal->nama;
        $this->jenis = $animal->jenis;
        $this->makanan = $animal->makanan;
        $this->file_name = $animal->file_name;
        $this->old_file_name = $animal->file_name;
        $this->animal_id = $id;
        $this->openModal();
    }

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->deleteConfirmation = true;
    }

    public function closeDeleteModal()
    {
        $this->deleteConfirmation = false;
        $this->resetField();
    }

    public function delete($id)
    {
        $animal = Animal::find($id);
        $result = $animal->delete();
        if($result != "0"){
            session()->flash('message', 'berhasil menghapus data');
        }else{
            session()->flash('errMessage', 'gagal menghapus data');
        }
        $this->closeDeleteModal();
    }
}
