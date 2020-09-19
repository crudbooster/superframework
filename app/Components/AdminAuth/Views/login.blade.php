@extends("_layout")
@section("content")
    <div class="row mt-5">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required >
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" required >
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" name="keep_me" type="checkbox">
                                <label class="form-check-label">
                                    Keep me signed in
                                </label>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-block btn-primary">Sign in</button>
                            </div>
                        </div>

                        <div class="mt-2 text-center">
                            <a href="#">Lost your password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-4"></div>
    </div>
@endsection