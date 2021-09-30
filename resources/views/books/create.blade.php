@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <label for="name">Book Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <div class="alert-danger mt-1" id="danger-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="authors">Authors</label>
                        <input type="text" class="form-control" id="authors" name="authors">
                        <div class="alert-danger mt-1" id="danger-authors"></div>
                        <small id="emailHelp" class="form-text text-muted">Write author names with `, ` symbol.</small>
                    </div>
                    <button class="btn btn-success" onclick="'{{ Route::currentRouteName() == 'book.edit' }}' == '' ? saveBook(event) : updateBook(event)">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let isUpdatePage = '{{ Route::currentRouteName() == 'book.edit' }}';
        if (isUpdatePage) {
            fetch('/api/books/' + '{{ $book->id ?? null }}', {
                headers: {
                    'Authorization' : 'Bearer ' + '{{ auth()->user()->token ?? '' }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                let authorsArr = data.authors;
                let authorsStr = '';
                for (let i = 0; i < authorsArr.length; ++i) {
                    authorsStr += authorsArr[i].author_name;
                    if (i + 1 != authorsArr.length) {
                        authorsStr += ', ';
                    }
                }
                document.getElementById('name').value = data.name;
                document.getElementById('authors').value = authorsStr;
            })
        }

        function updateBook(e) {
            e.preventDefault();
            let data = {
                name: document.getElementById('name').value,
                authors: document.getElementById('authors').value
            };
            fetch('/api/books/' + '{{ $book->id ?? null }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization' : 'Bearer ' + '{{ auth()->user()->token ?? '' }}'
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status) {
                    location.href = '/';
                    localStorage.setItem('success', 'Book updated!')
                } else {
                    for (const [key, value] of Object.entries(data.errors)) {
                        let el = document.getElementById('danger-'+key);
                        if (el) {
                            el.innerHTML = value;
                        }
                    }
                }
            })
        }

        function saveBook(e) {
            e.preventDefault();
            let errorMessages = document.querySelectorAll('.alert-danger');
            for (let i = 0; i < errorMessages.length; ++i) {
                errorMessages[i].innerHTML = "";
            }
            let formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('authors', document.getElementById('authors').value);
            formData.append('publisher_id', '{{ auth()->user()->publisher->id }}');
            fetch(`/api/books`, {
                method: 'post',
                body: formData,
                headers: {
                    'Authorization' : 'Bearer ' + '{{ auth()->user()->token ?? '' }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status){
                    location.href = '/';
                    localStorage.setItem('success', 'Book saved!');
                } else {
                    for (const [key, value] of Object.entries(data.errors)) {
                        let el = document.getElementById('danger-'+key);
                        if (el) {
                            el.innerHTML = value;
                        }
                    }
                }
            })
        }
    </script>
@endsection
