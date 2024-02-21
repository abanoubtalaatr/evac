@extends('admin.layouts.master')
@extends('admin.admins.style')


@section('content')
<br/>
<div class="card ">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{$page_title}}</h5>
    </div>
    <div class="card-body">
      <div class="text-right">
        <a href="{{ url()->previous() }}" class="btn btn-danger  mt-3">{{trans('messages.back')}}</a>
     </div>

     {!! Form::model($record,['route' => ['ticket.update',$record->id] , 'method' => 'PUT' , 'files' => true ]) !!}

     <table class="table">
         <tr>
             <td class="text-bold">@lang('site.id'):</td>
             <td>{{$record->id}}</td>
         </tr>
         <tr>
            <td class="text-bold">@lang('site.created_at'):</td>
            <td>{{$record->created_at}}</td>
        </tr>

         <tr>
            <td class="text-bold">@lang('site.user'):</td>
            <td>{{$record->user->{"name_".app()->getLocale()} }}</td>
        </tr>

         <tr>
            <td class="text-bold">@lang('site.user_mobile'):</td>
            <td>{{$record->user->mobile_number}}</td>
        </tr>

        <tr>
            <td class="text-bold">@lang('site.status'):</td>
            <td>{{$record->status_text}}</td>
        </tr>

        <tr>
            <td class="text-bold">@lang('site.ticket_category'):</td>
            <td>{{$record->ticketCategory->{"title_".app()->getLocale()} }}</td>
        </tr>





        <tr>
            <td class="text-bold">@lang('site.content'):</td>
            <td>{{$record->content}}</td>
        </tr>

        @if ($record->status=='pending')
            <tr>
                <td class='text-bold'>@lang('site.reply')</td>
                <td>
                    <textarea name="reply" class='form-control'>{{old('reply')}}</textarea>
                    @if($errors->has('reply'))
                        <span class="help-block">  <strong style="color: red;">{{ $errors->first('reply') }}</strong>  </span>
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <td class="text-bold">@lang('site.reply'):</td>
                <td>{{$record->reply}}</td>
            </tr>
            <tr>
                <td class="text-bold">@lang('site.replied_by'):</td>
                <td>{{$record->admin->name}}</td>
            </tr>
            <tr>
                <td class="text-bold">@lang('site.replied_at'):</td>
                <td>{{$record->replied_at}}</td>
            </tr>
            <tr>
                <td class="text-bold">@lang('site.rating'):</td>
                <td>{!! $record->stars !!}</td>
            </tr>
            <tr>
                <td class="text-bold">@lang('site.review'):</td>
                <td>{{$record->review}}</td>
            </tr>
        @endif

     </table>

     @if ($record->status=='pending')
        <div class="text-right">
            <button class="btn btn-primary mt-3" type="submit"> {{ trans('messages.save') }}</button>
        </div>
     @endif


     {!! Form::close() !!}


    </div>

</div>
@endsection
@extends('admin.admins.script')
