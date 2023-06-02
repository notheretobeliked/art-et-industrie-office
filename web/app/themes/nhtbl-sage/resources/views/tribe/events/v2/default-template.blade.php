@php
  use Tribe\Events\Views\V2\Template_Bootstrap;
@endphp

@extends('layouts.app')


@section('content')
  @include('partials.page-header')
  <div class="grid gap-8 md:gap-16 @if ($event_location) md:grid-cols-mapandcontent @endif alignwide">
    @if ($event_location)
      <div class="flex flex-col gap-1">
        <h3 class="font-ui uppercase text-base md:text-lg"> {{ __('Lieu :', 'sage') }} {!! $event_location['title'] !!}</h3>
        <x-map-output size="small" :slug="$event_location['slug']" />
        <div>{!! $event_location['address'] !!}</div>
      </div>
    @endif

    <div>
      @php(the_content())
    </div>
  </div>
@endsection
