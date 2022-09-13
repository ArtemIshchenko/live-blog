<?php
use App\Models\Post;
?>
@extends('layouts.moderate_layout')

@section('title', 'Модерировать статью')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Модерировать статью</h1>
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
            <form action="{{ route('postModerateUpdate', $post['id']) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Название</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="exampleInputEmail1" placeholder="Введите название статьи" value="{{ $post['title'] }}" required>
                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <textarea name="text" class="editor @error('text') is-invalid @enderror">{{ $post['text'] }}</textarea>
                        @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Видимость</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_public" id="is_private" value="{{ Post::VISIBILITY['isPrivate'] }}" @if ($post['is_public'] == Post::VISIBILITY['isPrivate']) checked @endif>
                            <label class="form-check-label" for="is_private">Приватный</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_public" id="is_public" value="{{ Post::VISIBILITY['isPublic'] }}" @if ($post['is_public'] == Post::VISIBILITY['isPublic']) checked @endif>
                            <label class="form-check-label" for="is_public">Публичный</label>
                        </div>
                    </div>
                    <div class="image-blocks">
                        @if ($images->isNotEmpty())
                            @foreach($images as $i => $img)
                                @php $number = $i + 1; @endphp
                                <div class="form-group img-block" data-number="{{$number}}">
                                    <div class="image-block">
                                        <label for="feature_image">Изображение</label>
                                        <img src="{{'/' . $img['path']}}" alt="" class="img-uploaded" style="display: block;">
                                        <input type="text" class="form-control feature_image" name="feature_image[]" value="{{$img['path']}}" readonly>
                                    </div>
                                    <a href="" class="popup_selector" data-inputid="feature_image_{{$number}}">Выбрать изображение</a>
                                </div>
                                <a href="" class="add_img">Добавить изображение</a>
                            @endforeach
                        @else
                            <div class="form-group img-block" data-number="1">
                                <div class="image-block">
                                    <label for="feature_image">Изображение</label>
                                    <img src="" alt="" class="img-uploaded" style="display: block;">
                                    <input type="text" class="form-control feature_image" name="feature_image[]" value="" readonly>
                                </div>
                                <a href="" class="popup_selector" data-inputid="feature_image_1">Выбрать изображение</a>
                            </div>
                            <a href="" class="add_img">Добавить изображение</a>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Причина отклонения или блокирования статьи</label>
                        <!-- textarea -->
                        <textarea class="form-control" rows="2" placeholder="Enter ..." name="refuse_reason">{{ $post['refuse_reason'] }}</textarea>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="update" value="1">Обновить</button>
                    <button type="submit" class="btn btn-success" name="approve" value="1">Подтвердить</button>
                    <button type="submit" class="btn btn-secondary" name="refuse" value="1">Отклонить</button>
                    <button type="submit" class="btn btn-warning" name="lock" value="1">Заблокировать</button>
                </div>
            </form>
        </div>
    </div>
    </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
