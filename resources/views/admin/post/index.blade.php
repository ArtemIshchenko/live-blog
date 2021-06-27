@php
    use App\Models\Post;
@endphp
@extends('layouts.admin_layout')

@section('title', 'Главная')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Все статьи</h1>
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
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 5%">
                                ID
                            </th>
                            <th>
                                Название
                            </th>
                            <th>
                                Дата добавления
                            </th>
                            <th>
                                Статус
                            </th>
                            <th>
                                Причина отклонения
                            </th>
                            <th style="width: 30%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    {{ $post->id }}
                                </td>
                                <td>
                                    {{ $post->title }}
                                </td>
                                <td>
                                    {{ $post->created_at }}
                                </td>
                                <td>
                                    {{ Post::getStatusList()[$post->status] }}
                                </td>
                                <td>
                                    {{ $post->refuse_reason }}
                                </td>
                                <td class="project-actions text-right">
                                    @if (!in_array($post['status'], [Post::STATUS['sendToModerate'], Post::STATUS['approved'], Post::STATUS['locked']]))
                                        <a class="btn btn-success btn-xs" href="{{ route('sendToModerate', $post['id']) }}">
                                            <i class="fa fa-paper-plane fa-xs">
                                            </i>
                                            Отправить на модерацию
                                        </a>
                                    @endif
                                    @if (!in_array($post['status'], [Post::STATUS['sendToModerate'], Post::STATUS['approved'], Post::STATUS['locked']]))
                                        <a class="btn btn-info btn-xs" href="{{ route('post.edit', $post['id']) }}">
                                            <i class="fas fa-pencil-alt fa-xs">
                                            </i>
                                            Редактировать
                                        </a>
                                    @endif
                                    <form action = "{{ route('post.destroy', $post['id']) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs delete-btn" href="#">
                                            <i class="fas fa-trash fa-xs">
                                            </i>
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
