<div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form wire:submit.prevent="upload">
                    <div class="form-group">
                        <label for="title">Music Title:</label>
                        <input type="text" class="form-control" wire:model="music.title">
                    </div>

                    @error('music.title')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror


                    <label for="title">Music Display Picture Upload: (*.jpeg)</label>
                    <div
                        x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <!-- File Input -->
                        <input type="file" wire:model="songPicSrc">

                        <!-- Progress Bar -->
                        <div x-show="isUploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>
                    <br>
                    @error('songPicSrc')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror

                    <label for="title">Music Song Upload: (*.mp3)</label>
                    <div
                        x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <!-- File Input -->
                        <input type="file" wire:model="songSrc">

                        <!-- Progress Bar -->
                        <div x-show="isUploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>
                    <br>

                     @error('songSrc')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror

                    <label for="title">Music Video Upload: (*.mp4)</label>
                    <div
                        x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <!-- File Input -->
                        <input type="file" wire:model="musicFile">

                        <!-- Progress Bar -->
                        <div x-show="isUploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>
                    <br>

                     @error('musicFile')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror

                     <div class="form-group">
                            <label for="genre">Genre:</label>
                            <select wire:model="selectedGenre" class="form-control">
                                <option value="" selected>Choose Genre</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->genreName }}</option>
                                @endforeach
                            </select>
                    </div>

                    @error('selectedGenre')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="form-group">
                            <label for="theme">Theme:</label>
                            <select wire:model="selectedTheme" class="form-control">
                                <option value="" selected>Choose Theme</option>
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->themeName }}</option>
                                @endforeach
                            </select>
                    </div>


                    @error('selectedTheme')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="form-group">
                            <label for="album">Album:</label>
                            <select wire:model="selectedAlbum" class="form-control">
                                <option value="" selected>Choose Album</option>
                                @foreach($albums as $album)
                                    <option value="{{ $album->id }}">{{ $album->albumTitle }}</option>
                                @endforeach
                            </select>
                    </div>


                    <div class="form-group">
                       <label for="year">Music Year:</label>
                       <input type="text" class="form-control" wire:model="music.musicYear">
                   </div>
 
                   @error('music.musicYear')
                   <div class="alert alert-danger">
                       {{ $message }}
                   </div>
                   @enderror
 
 
                   <div class="form-group">
                       <label for="lyrics">Lyrics:</label>
                      <textarea class="form-control" rows="15" wire:model="music.musicLyrics"></textarea>    
                  </div>
 
                   @error('music.musicLyrics')
                   <div class="alert alert-danger">
                       {{ $message }}
                   </div>
                   @enderror
 
 
                   <div class="form-group">
                       <label for="lyricsWriter">Music Lyrics Writer: </label>
                       <input type="text" class="form-control" wire:model="music.musicLyricsWriter">
                   </div>
 
                   @error('music.musicLyricsWriter')
                   <div class="alert alert-danger">
                       {{ $message }}
                   </div>
                   @enderror
 
 
                   <div class="form-group">
                       <label for="instrumental">Music Instrumental Maker:</label>
                       <input type="text" class="form-control" wire:model="music.musicInstrumentalMaker">
                   </div>
 
                   @error('music.musicInstrumentalMaker')
                   <div class="alert alert-danger">
                       {{ $message }}
                   </div>
                   @enderror
 
 
                    <div class="form-group">
                       <label for="producer">Music Producer:</label>
                       <input type="text" class="form-control"  wire:model="music.musicProducer">
                   </div>
 
                   @error('music.musicProducer')
                   <div class="alert alert-danger">
                       {{ $message }}
                   </div>
                   @enderror
 
                    <div class="form-group">
                       <label for="performer">Music Performer:</label>
                       <input type="text" class="form-control"  wire:model="music.musicPerformer">
                   </div>
 
                   @error('music.musicPerformer')
                   <div class="alert alert-danger">
                       {{ $message }}
                   </div>
                   @enderror


                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">Upload</button>
                    </div>

                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message')}}
                    </div>
                    @endif
                    <br><br><br><br>
                </form>
        </div>
    </div>
</div>
</div>

