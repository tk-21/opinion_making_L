@extends('layouts.index')

@section('title')
    <h2 class="home-ttl">カテゴリー名：{{ $category->name }}</h2>

    <a class="category-edit-btn"
       href="{{ route('categories.edit', ['category' => $category]) }}">カテゴリー名編集</a>
    <a class="category-delete-btn"
       href="{{ route('categories.confirmDelete', ['category' => $category]) }}">削除</a>
@endsection
