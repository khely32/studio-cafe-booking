@extends('layouts.app')
@section('title', $page->meta_title ?: $page->title)

@section('content')
<div class="page-header" style="padding:140px 40px 60px;">
    <h1>{{ $page->title }}</h1>
    <div class="header-accent"></div>
</div>

<div style="max-width:800px;margin:0 auto;padding:0 40px 80px;">
    <div style="background:rgba(255,255,255,0.88);backdrop-filter:blur(20px);border-radius:18px;padding:48px;border:1px solid rgba(201,169,110,0.12);box-shadow:0 8px 32px rgba(44,30,20,0.07);">
        <div style="font-size:15px;line-height:2;color:var(--gray-700);white-space:pre-line;">{!! nl2br(e($page->content)) !!}</div>
    </div>
</div>
@endsection