@php
    use App\Models\User;
@endphp
@extends('layouts.admin_layout')

@section('title', 'Контакты')

@section('content')
<style>
    div.msg {
        background-color: beige;
        padding: 10px;
        margin: 10px;
    }
    div.ans {
        background-color: lightblue;
        padding: 10px;
        margin: 10px;
    }
    .span-time {
        margin-left: 40px;
    }
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid contact-name">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Контакт: {{$contact->user_name}}</h3>
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
    <!-- Main content -->
    <section class="content" style="padding-left: 25px;">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-12">
                    @if ($isFirstQuestion)
                        <div class="ans">
                            Вы: {{$firstMessageFromReader->text}}
                            <span class="span-time">{{(new DateTime($firstMessageFromReader->created_at))->format('M j, Y h:i')}}</span>
                        </div>
                    @endif
                    @if (!$messages->isEmpty())
                        @foreach ($messages as $message)
                            <div class="msg">
                                {{$contact->user_name}}: {{$message->text}}
                                <span class="span-time">{{(new DateTime($message->created_at))->format('M j, Y h:i')}}</span>
                            </div>
                            @if (!empty($message->writer_answer))
                                <div class="ans">
                                    Вы: {{$message->writer_answer}}
                                    <span class="span-time">{{(new DateTime($message->updated_at))->format('M j, Y h:i')}}</span>
                                </div>
                            @endif
                        @endforeach
                        @if (empty($message->writer_answer))
                            <div class="answer">
                                <form>
                                    @csrf
                                    <div class="form-group">
                                        <label>Ответить</label>
                                        <!-- textarea -->
                                        <textarea class="form-control" rows="5" placeholder="Enter ..." name="writer_answer" required></textarea>
                                    </div>
                                    <button class="btn btn-primary send-answer" data-url="{{route('writeAnswer', $messages->last()->id)}}">Submit</button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content-header -->
    <script>
        $(function() {
            $(document).on('click', 'button.send-answer', function (e) {
                e.preventDefault();
                var $this = $(this);
                var url = $this.data('url');
                var textarea = $this.closest('form').find('textarea')
                var writer_answer = textarea.val();
                var csrfToken =  $this.closest('form').find('input[name=_token]').val();
                if (writer_answer !== '') {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {'writer_answer': writer_answer},
                        headers: {
                            'X-CSRF-Token': csrfToken
                        },
                        success: function (json) {
                            if (json.result === 'success') {
                                var successDiv = '<div id="success-update" style="position: absolute; display: block; width: 1276px; text-align: right; top: 30px"><p class="bg-success" style="display: inline-block; width: 850px; height: 50px; font-size: 1.5em; text-align: center; padding-top: 10px">Ответ успешно оправлен</p></div>';
                                $('div.contact-name').append(successDiv);
                                setTimeout(function () {
                                    $('#success-update').remove();
                                }, 2000);
                                if (textarea.hasClass('is-invalid')) {
                                    textarea.removeClass('is-invalid');
                                }
                                $('.answer').replaceWith('<div class="ans"> Вы: ' + writer_answer + '<span class="span-time">' + json.updatedAt + '</span></div>');

                            } else if (json.result === 'error') {
                                var dangerDiv = '<div id="danger-update" style="position: absolute; display: block; width: 1276px; text-align: right; top: 30px"><p class="bg-danger" style="display: inline-block; width: 850px; height: 50px; font-size: 1.5em; text-align: center; padding-top: 10px">Ошибка оправки</p></div>';
                                $('div.contact-name').append(dangerDiv);
                                setTimeout(function () {
                                    $('#danger-update').remove();
                                }, 2000);
                            }
                        },
                        'error': function (e) {
                            alert('Произошла ошибка: ' + e.getMessage + '. Попробуйте еще раз');
                        }
                    });
                } else {
                    if (!textarea.hasClass('is-invalid')) {
                        textarea.addClass('is-invalid');
                        textarea.after('<span id="message-error" class="error invalid-feedback">Введите, пожалуйста, текст</span>');
                    }
                }
            });
        });
    </script>
@endsection
