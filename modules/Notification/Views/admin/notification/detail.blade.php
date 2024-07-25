@extends('admin.layouts.app')

@section('content')
    <form
        action="{{ route('notification.admin.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
        method="post" class="dungdt-form">
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{ $row->id ? __('Edit post: ') . $row->title : __('Add new Notication') }}</h1>
                    @if ($row->slug)
                        <p class="item-url-demo">{{ __('Permalink') }}:
                            {{ url((request()->query('lang') ? request()->query('lang') . '/' : '') . config('news.news_route_prefix')) }}/<a
                                href="#" class="open-edit-input" data-name="slug">{{ $row->slug }}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if ($row->slug)
                        <a class="btn btn-primary btn-sm" href="{{ $row->getDetailUrl(request()->query('lang')) }}"
                            target="_blank">{{ __('View Post') }}</a>
                    @endif
                </div>
            </div>
            @include('admin.message')
            @include('Language::admin.navigation')
            <div class="lang-content-box">
                @if ($row->send)
                    <div class="alert alert-success">This notification has been sent out, only updates are allowed, the
                        system will not notify the user again.</div>
                @endif
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Notification content') }}</strong></div>
                            <div class="panel-body">
                                @csrf
                                @include('Notification::admin/notification/form', ['row' => $row])
                                {{-- <div class="form-group">
                                    <label class="control-label">{{__("Gallery")}}</label>
                                    {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery',$row->gallery) !!}
                                </div> --}}
                            </div>
                        </div>
                        {{-- @include('Core::admin/seo-meta/seo-meta') --}}
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Publish') }}</strong></div>
                            <div class="panel-body">
                                @if (is_default_lang())
                                    <div>
                                        <label><input @if (old('status', $row->status) == 'publish') checked @endif type="radio"
                                                name="status" value="publish" class='status'> {{ __('Send Notication') }}
                                        </label>
                                    </div>
                                    <div>
                                        <label><input @if (old('status', $row->status) == 'draft') checked @endif type="radio"
                                                name="status" value="draft" class='status'> {{ __('Draft') }}
                                        </label>
                                    </div>
                                @endif
                                <div class="text-right">
                                    @if ($row->send || old('status', $row->status) == 'draft')
                                        <button class="btn btn-primary btn-action" type="submit"><i class="fa fa-save"></i>
                                            <span>{{ __('Save Changes') }}</span></button>
                                    @else
                                        <button class="btn btn-success btn-action" type="submit"><i class="fa fa-save"></i>
                                            <span>{{ __('Send Notification') }}</span></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{ __('Author Setting') }}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <?php
                                        $user = $row->author;
                                        \App\Helpers\AdminForm::select2(
                                            'author_id',
                                            [
                                                'configs' => [
                                                    'ajax' => [
                                                        'url' => route('user.admin.getForSelect2'),
                                                        'dataType' => 'json',
                                                    ],
                                                    'allowClear' => true,
                                                    'placeholder' => __('-- Select User --'),
                                                ],
                                            ],
                                            !empty($user->id) ? [$user->id, $user->getDisplayName() . ' (#' . $user->id . ')'] : false,
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@if (!$row->send)
    @push('js')
        {!! App\Helpers\MapEngine::scripts() !!}
        <script>
            jQuery(function($) {
                $('.status').click('on', function() {
                    var value = $(this).val();
                    if (value == 'publish') {
                        // alert('test');
                        $('.btn-action span').text("Send Notification");
                        $(".btn-action").removeClass('btn-primary').addClass('btn-success');
                    } else {
                        $('.btn-action span').text("Save Notification");
                        $(".btn-action").removeClass('btn-success').addClass('btn-primary');
                    }
                });
            })
        </script>
    @endpush
@endif
