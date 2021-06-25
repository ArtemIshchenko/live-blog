@php
    use App\Models\User;
@endphp
@extends('layouts.moderate_layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Пользователи</h3>
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
                                Имя
                            </th>
                            <th style="width: 5%">
                                Пол
                            </th>
                            <th style="width: 10%">
                                E-mail
                            </th>
                            <th style="width: 5%">
                                Страна
                            </th>
                            <th style="width: 5%">
                                Город
                            </th>
                            <th style="width: 10%">
                                Дата рождения
                            </th>
                            <th style="width: 10%">
                                Дата добавления
                            </th>
                            <th style="width: 5%">
                                Статус
                            </th>
                            <th style="width: 15%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $user->id }}
                                </td>
                                <td>
                                    {{ $user->name }}
                                </td>
                                <td>
                                    {{ User::getGenderList()[$user->gender] }}
                                </td>
                                <td>
                                    {{ $user->email }}
                                </td>
                                <td>
                                    {{ $user->country }}
                                </td>
                                <td>
                                    {{ $user->city }}
                                </td>
                                <td>
                                    {{ (new DateTime($user->birthday))->format('M j, Y') }}
                                </td>
                                <td>
                                    {{ (new DateTime($user->created_at))->format('M j, Y') }}
                                </td>
                                <td>
                                    {{ User::getStatusList()[$user->status] }}
                                </td>
                                <td class="project-actions text-right">
                                    @if ($user->status == User::STATUS['actived'])
                                        <a class="btn btn-info btn-sm" href="{{ route('userModerateChangeStatus', ['id' => $user->id, 'status' => User::STATUS['locked']]) }}">
                                            <i class="fas fa-user-lock">
                                            </i>
                                            Заблокировать
                                        </a>
                                    @else
                                        <a class="btn btn-info btn-sm" href="{{ route('userModerateChangeStatus', ['id' => $user->id, 'status' => User::STATUS['actived']]) }}">
                                            <i class="fas fa-unlock">
                                            </i>
                                            Разблокировать
                                        </a>
                                    @endif
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
