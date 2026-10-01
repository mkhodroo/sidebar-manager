@extends('behin-layouts.app')

@section('title', $title)

@section('content')
    <div class="container-fluid" dir="rtl">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $title }} - تصویر سایدبار</h4>
            </div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-7">
                        <form action="{{ route('sidebar-manager.image.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="image">انتخاب تصویر</label>
                                <input type="file" id="image" name="image" class="form-control-file" required>
                                <small class="form-text text-muted">
                                    فرمت‌های مجاز: {{ implode('، ', config('sidebar-manager.mimes')) }}
                                    — حداکثر حجم: {{ config('sidebar-manager.max_size') }} کیلوبایت
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="title">عنوان (اختیاری)</label>
                                <input type="text" id="title" name="title" class="form-control"
                                       value="{{ old('title', $image->title ?? '') }}">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ $image ? 'جایگزینی تصویر' : 'ذخیره تصویر' }}
                            </button>
                        </form>

                        @if ($image)
                            <hr>
                            <form action="{{ route('sidebar-manager.image.destroy') }}" method="POST"
                                  onsubmit="return confirm('تصویر سایدبار حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">حذف تصویر فعلی</button>
                            </form>
                        @endif
                    </div>

                    <div class="col-md-5">
                        <h6>پیش‌نمایش</h6>
                        @if ($image)
                            <img src="{{ $image->url }}" alt="{{ $image->title ?? 'سایدبار' }}"
                                 class="img-fluid rounded border mb-2" style="max-height:220px">
                            <table class="table table-sm table-bordered mb-0">
                                <tr><th>مسیر</th><td><code>{{ $image->path }}</code></td></tr>
                                <tr><th>دیسک</th><td>{{ $image->disk }}</td></tr>
                                <tr><th>حجم</th><td>{{ $image->size_in_kb }} KB</td></tr>
                                <tr><th>تاریخ</th><td>{{ $image->created_at }}</td></tr>
                            </table>
                        @else
                            <div class="alert alert-secondary mb-0">
                                هنوز تصویری برای سایدبار ذخیره نشده است.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
