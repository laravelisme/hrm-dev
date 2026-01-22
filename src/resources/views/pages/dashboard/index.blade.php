@extends('layouts.master')

@section('title', 'Test')
@section('meta-tag')
    <meta name="description" content="Test">
@endsection

@section('title', 'Test')
@section('subtitle', 'Test')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Default Layout</h4>
            </div>
            <div class="card-body">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, commodi? Ullam quaerat
                similique iusto
                temporibus, vero aliquam praesentium, odit deserunt eaque nihil saepe hic deleniti? Placeat
                delectus
                quibusdam ratione ullam!
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        console.log('Test');
    </script>
@endsection
