@extends('user.layout.master')

@section('title', 'Home Page')


@section('content')

<div class="container bg-light mt-3">
    <div>
        <h3>All Posts</h3>
    </div>
    <div class="row">
        @forelse ($users as $user)
            <div class="col-lg-12 mb-3">
                <span>user : {{ $user->name }}</span>
                @if ($user->posts !== null && $user->posts->isNotEmpty())
                    @foreach ($user->posts as $post)
                        <div class="card" style="width: 18rem;">
                            <img src="{{ $post->image }}" class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title">title : {{ $post->title }}</h5>
                                <p class="card-text">description : {{ $post->text }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- No posts for this user -->
                    <div class="alert alert-warning" role="alert">
                        This user has no posts.
                    </div>
                @endif
            </div>
        @empty
            <div class="col-md-12">
                <p>No users found.</p>
            </div>
        @endforelse
    </div>
</div>





@endsection