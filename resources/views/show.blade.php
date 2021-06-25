@extends('layouts.app')

@section('title', 'Блог')

@section('content')
    <div class="container">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ session('success') }}</h4>
                </div>
            @endif
        </div><!-- /.container-fluid -->
        <div class="posts-container px-3 mx-auto my-5">
            <div class="post">
                <h1 class="post-title fw-500">{{$post['title']}}</h1>
                <div class="d-flex align-items-center mb-4 text-muted author-info">
                    <a class="d-flex align-items-center text-muted text-decoration-none" href="https://github.com/mdo" target="_blank" rel="noopener">
                        <span>{{$post['authorName']}}</span>
                    </a>
                    <span class="d-flex align-items-center ml-3" title="Wed, 05 May 2021 09:30:00 +0000">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="mr-2" viewBox="0 0 16 16" role="img" fill="currentColor">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"></path>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"></path>
                        </svg>

                        {{$post['createdAt']}}
                    </span>
                </div>
                {!! $post['text'] !!}
            </div>

            <div class="related">
                <h2>Изображения</h2>
                @if ($images->isNotEmpty())
                    <ul class="related-posts list-unstyled">
                        @foreach($images as $image)
                            <li>
                                <img src="/live-blog/public/{{$image['path']}}" alt="" class="img-thumbnail" style="margin: 5px; float: left;" width="250px" height="220px">
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        @if (!$post['readerIsAuthor'])
            <div class="col-md-12">
                <form method="POST" action="{{route('writeToAuthor', ['id' => $post['id']])}}">
                    @csrf
                    <div class="form-group">
                        <label>Написать автору</label>
                        <!-- textarea -->
                        <textarea class="form-control" rows="5" placeholder="Enter ..." name="text" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        @endif
    </div>
    <!-- /.content -->
@endsection
