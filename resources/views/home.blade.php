@extends('layouts.app')

@section('title', 'Блог')

@section('content')
    <div class="container">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 class="m-0">Статьи</h3>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        @if (!empty($posts))
            @foreach($posts as $post)
                <section class="content">
                    <div class="container-fluid">
                        <div class="card text-left">
                            <div class="card-header">
                                {{$post['authorName']}}
                                <span class="post-date">{{$post['createdAt']}}</span>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{$post['title']}}</h3>
                                <p class="card-text">{!!$post['text']!!}</p>
                                <a href="{{route('show', ['id' => $post['id']])}}" class="btn btn-primary">Подробнее</a>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
            @endforeach
        @endif
    </div>
    <!-- /.content -->
@endsection
