@php
    use App\Models\Post;
@endphp

@extends('layouts.admin_layout')

@section('title', 'Добавить статью')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Добавить статью</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>{{ session('success') }}</h4>
            </div>
        @endif
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
        <div class="card card-primary">
            <!-- form start -->
            <form action="{{ route('post.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Название</label>
                  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="exampleInputEmail1" placeholder="Введите название статьи" value="{{ old('title') }}" required>
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            <div class="form-group">
                  <textarea name="text" class="editor">{{ old('text') }}</textarea>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
            </div>
            <div class="form-group">
                <label>Видимость</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_public" id="is_private" value="{{ Post::VISIBILITY['isPrivate'] }}" checked="">
                    <label class="form-check-label" for="is_private">Приватный</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_public" id="is_public" value="{{ Post::VISIBILITY['isPublic'] }}">
                    <label class="form-check-label" for="is_public">Публичный</label>
                </div>
            </div>
            <div class="image-blocks">
                <div class="form-group img-block" data-number="1">
                  <div class="image-block">
                      <label for="feature_image">Изображение</label>
                      <img src="" alt="" class="img-uploaded" style="display: block;">
                      <input type="text" class="form-control feature_image" name="feature_image[]" value="" readonly>
                  </div>
                  <a href="" class="popup_selector" data-inputid="feature_image_1">Выбрать изображение</a>
                </div>
            </div>
                <a href="" class="add_img">Добавить изображение</a>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
            </form>
        </div>
    </div>
    </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
