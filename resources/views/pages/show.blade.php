@extends('layouts.app')
@section('title', $page->meta_title ?: $page->title)

@section('content')
<div class="page-header" style="padding:140px 40px 60px;">
    <h1>{{ $page->title }}</h1>
    <div class="header-accent"></div>
</div>

<div style="max-width:800px;margin:0 auto;padding:0 40px 80px;">
    <div style="background:#fff;border-radius:var(--radius-lg);padding:48px;border:1px solid var(--gray-200);box-shadow:var(--shadow-sm);">
        <div style="font-size:15px;line-height:2;color:var(--gray-700);white-space:pre-line;">{!! nl2br(e($page->content)) !!}</div>
    </div>
</div>
@endsection
