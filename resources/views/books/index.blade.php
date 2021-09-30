@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3 hidden">
                <div class="alert alert-primary" role="alert" id="successMessage"></div>
            </div>
            <div class="col-md-12 mt-3 hidden">
                <div class="alert alert-danger" role="alert" id="errorMessage"></div>
            </div>
            @auth()
                <div class="col-md-12 mt-3">
                    <a class="btn btn-success" href="{{ route('book.create') }}">Add book</a>
                </div>
            @endauth
            <div id="emptyData"></div>
        </div>
        <div class="row" id="books"></div>
    </div>
@endsection

@section('scripts')
    <script>
        fetch('/api/books', {
            headers: {
                'Authorization' : 'Bearer ' + '{{ auth()->user()->token }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.status && data.message) {
                let el = document.getElementById('emptyData');
                el.innerHTML = `<h1 class="mt-4">${data.message}</h1>`;
            } else {
                let res = '';
                for (let i = 0; i < data.length; i++) {
                    var authors = '';
                    data[i].authors.forEach(el => {
                        authors += `${el.author_name}<br>`;
                    });
                    let isOwner = '';
                    if (data[i].publisher_id == '{{ auth()->user()->publisher->id }}') {
                        isOwner = `<div class="row">
                                            <div class="col-md-2 offset-7">
                                                    <button class="btn btn-danger" onclick="deleteBook(${data[i].id})">Delete</button>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="/books/${data[i].id}/edit" class="btn btn-primary">Update</a>
                                            </div>
                                        </div>`;
                    }
                    res += `<div class="col-md-4 mt-5" id="book${data[i].id}">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h3 class="card-title">${data[i].name}</h3>
                                        <h5>Authors</h5>
                                        <p>${authors}</p>
                                        <h5>Publisher</h5>
                                        <p>${data[i].publisher.name}</p>
                                        ${isOwner}
                                    </div>
                                </div>
                            </div>`;
                }
                document.getElementById('books').innerHTML = res;
            }
        });

        function deleteBook(id) {
            fetch(`/api/books/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization' : 'Bearer ' + '{{ auth()->user()->token }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    let successMess = document.getElementById('successMessage');
                    successMess.innerHTML = data.message;
                    successMess.parentElement.classList.remove('hidden');
                    removeAlert('successMessage');
                }
                document.getElementById(`book${id}`).remove();
            })
        }
    </script>
@endsection
