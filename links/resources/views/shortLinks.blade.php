@extends('home')

@section('content')
    <div class="container">
        <h1>Генерация сокращенной ссылки</h1>

        <div class="card bg-dark">
            <div class="card-header">
                <form method="post" action="{{ route('generate.link') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="link" id="currentLink" class="form-control" placeholder="Введите ссылку">
                        <div class="input-group-append">
                            <button class="btn btn-warning" id="send">Подтвердить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-success">
                <p>Ссылка создана</p>
            </div>

            <div class="alert alert-danger">
            </div>

            <ul id="group" class="list-group flex">
                @foreach ($links as $link)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $link->current_link }}
                        <span class="badge bg-primary rounded-pill">Исходная ссылка</span>
                    </li>
                    <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">
                        <a href="{{ route('short.link', $link->short_link) }}"
                            target="_blank">{{ route('short.link', $link->short_link) }}</a>
                        <span class="badge bg-primary rounded-pill">Сокращенная ссылка</span>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
    jQuery(document).ready(function($) {
        jQuery('.alert-success').hide();
        jQuery('.alert-danger').hide();
        $("#send").click(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            var formData = {
                link: jQuery('#currentLink').val()
            };

            $.ajax({
                type: 'POST',
                url: `{{ route('generate.link') }}`,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var str =
                        `<li class="list-group-item d-flex justify-content-between align-items-center">` +
                        data.current_link +
                        `<span class="badge bg-primary rounded-pill">Исходная ссылка</span>
                    </li>
                    <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">
                        <a href="` + data.short_link + `"
                            target="_blank">` + window.location.origin + `/` + data.short_link + `</a>
                        <span class="badge bg-primary rounded-pill">Сокращенная ссылка</span>
                    </li>`;

                    jQuery('#group').prepend(str);
                    jQuery('form').trigger('reset')
                    jQuery('.alert-danger').hide();
                    jQuery('.alert-success').show();
                    setTimeout(function() {
                        $('.alert-success').hide();
                    }, 3000);
                },
                error: function(xhr) {
                    var err = JSON.parse(xhr.responseText)
                    jQuery('form').trigger('reset')
                    jQuery('.alert-danger').html('')
                    jQuery('.alert-danger').append(err.errors.link)
                    jQuery('.alert-danger').show();
                }

            })
        })
    })
</script>
