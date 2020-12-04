<x-master>
    <div class="flex-column">
        <div class="col text-center justify-content-center align-self-center mt-lg-5">
            <h1>Check link for animation:</h1>
        </div>

            <form method="post" action="/">
                @csrf
                <div class="form-group col text-center justify-content-center align-self-center mt-5">
                    <label  for="url">URL
                        <input class="form-control" type="url" name="url" id="url" required autofocus>
                    </label>
                    @error('url')
                    <p class="alert-warning text-sm-center mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col text-center justify-content-center align-self-center">
                    <button type="submit" class="btn-primary rounded">
                        Submit
                    </button>
                </div>
            </form>
    </div>
    <div class="col text-center justify-content-center align-self-center mt-5">
            @if( session('danger'))
            <h1 class="bg-danger text-light">{{ session('danger') }}</h1>

            @elseif ( session('safe'))
            <h1 class="bg-success text-dark">{{ session('safe')}}</h1>
            @endif
    </div>
    <div class="col text-center justify-content-center align-self-center mt-5">
        @if( session('link'))
            <span class="bg-info text-light">{{ session('link') }}</span>
        @endif
    </div>
</x-master>
