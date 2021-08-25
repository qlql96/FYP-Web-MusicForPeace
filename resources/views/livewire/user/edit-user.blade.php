<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    
    @if($user->userPictureSrc)
    <img src="{{ asset('profilePic' . '/' . $user->userPictureSrc)}}" alt="">
    @endif
    <form wire:submit.prevent="update">


        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" wire:model="user.name">
        </div>

        @error('user.name')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
        @enderror

        <div class="form-group">
            <label for="userBio">Bio</label>
            <textarea cols="30" rows="4" class="form-control" wire:model="user.userBio"></textarea>
        </div>

        @error('user.userBio')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
        @enderror

        <div class="form-group">

            <input type="file" wire:model="profilePic">
        </div>

        <div class="form-group">
            @if ($profilePic)
            Photo Preview:
            <img src="{{ $profilePic->temporaryUrl() }}" class="img-thumbnail">
            @endif
        </div>

        @error('profilePic')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
        @enderror


        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>

        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message')}}
        </div>
        @endif

    </form>

</div>
