@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" id="app">
                <vue-query-builder
                  :rules="rules"
                  :initial-query="initialQuery"
                  @query-updated="updateQuery">
                </vue-query-builder>

                <input class="btn btn-default" type="button" value="Get Results" @click="getResults">

                <pre>@{{ JSON.stringify(results, null, 2) }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
