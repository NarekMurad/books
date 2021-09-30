@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <form method="post" action="{{ route('registration') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Publisher Name</label>
                        <input type="text" class="form-control" id="name" value="{{ old('name') }}" name="name">
                        @error('name')<div class="alert-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" value="{{ old('email') }}" name="email">
                        @error('email')<div class="alert-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        @error('password')<div class="alert-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
@endsection
