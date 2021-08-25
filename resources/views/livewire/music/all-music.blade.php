<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($musics->count())

                 @foreach($musics as $music)
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{asset("storage/music/" . $music->songPicSrc)}}" class="img-thumbnail" alt="">
                                </div>
                                <div class="col-md-3">
                                    <h5>{{$music->musicTitle}}</h5>
                                </div>
                                <div class="col-md-2">
                                    {{$music->created_at->format('d/m/Y')}}
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('music.edit' , ['user'=> auth()->user()->id, 'music' => $music->id])}}"
                                    class="btn btn-light btn-sm">Edit</a>
                                    <a wire:click.prevent="delete('{{$music->id}}')"
                                        class="btn btn-danger btn-sm">Delete</a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                <h1>No Music uploaded</h1>
                @endif
            </div>
            {{ $musics->links()}}
        </div>
    </div>
</div>
