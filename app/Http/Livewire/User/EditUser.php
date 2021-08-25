<?php

namespace App\Http\Livewire\User;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use PDO;
use Image;

class EditUser extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $user;
    public $profilePic;

    protected function rules(){

        return [
            'user.name' => 'required|max:255',
            'user.userBio' => 'nullable|max:1000',
            'user.userPictureSrc' => 'nullable',
        ];
    }

    public function mount(User $user){

        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user.edit-user');
    }

    public function update(){

        $this->authorize('update', $this->user);
        $this->validate();
        $this->user->update([
            'name' => $this->user->name,
            'userBio' => $this->user->userBio,
        ]);
        //check if dp is submitted
        if ($this->profilePic) {
            //save the image

            //$profilePic = $this->profilePic->storeAs('profilePic', $this->user->id . '.png');
            //$userPictureSrc = explode('/', $profilePic)[1];
            //resize and convert to png
            $uploadedProfilePic = $this->profilePic->store('public/profilePic/'.$this->user->email);
            $src = explode('/', $uploadedProfilePic)[2] .'/' .explode('/', $uploadedProfilePic)[3];
            
            /*
            $img = Image::make(storage_path() . '/app/'  . $profilePic)
                ->encode('png')
                ->fit(80, 80, function ($constraint) {
                    $constraint->upsize();
                })->save();
            */
            //update file path in the db
            $this->user->update([
                'userPictureSrc' => $src,
            ]);
        }
        
        session()->flash('message', 'Profile updated');
        return redirect()->route('user.edit', ['user' => $this->user->id]);

    }
}
