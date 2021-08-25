<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form wire:submit.prevent="update">
                    <div class="form-group">
                    <div class="col-md-5">
                            <img src="{{asset("storage/music/" . $music->songPicSrc)}}" class="img-thumbnail" alt="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" wire:model="music.musicTitle">
                    </div>

                    @error('music.musicTitle')
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
                    <div class="form-group">
                            <label for="theme">Theme:</label>
                            <select wire:model="selectedTheme" class="form-control">
                                <option value="" selected>Choose Theme</option>
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->themeName }}</option>
                                @endforeach
                            </select>
                    </div>
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
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message')}}
                    </div>
                    @endif

                </form>

            </div>
        </div>
    </div>

</div>
