
 <main class="main-content">
          <!--head-->
          <x-admin.head/>
          <!--table-->
          <div class="border-div">
            <div class="b-btm flex-div-2">
              <h4>{{$page_title}}</h4>
            </div>
            <div class="table-page-wrap">

                <form  wire:submit.prevent="update" >

                    <div class="row">
                        <table class="table table-striped table-hover table-reponsive" dir='{{ LaravelLocalization::getCurrentLocaleDirection() }}'>
                            <tr>
                                <td class="text-bold">@lang('site.sender_name')</td>
                                <td>{{$record->sender_name}}</td>
                            </tr>

                            <tr>
                                <td class="text-bold">@lang('site.sender_email')</td>
                                <td>{{$record->sender_email}}</td>
                            </tr>

                            <tr>
                                <td class="text-bold">@lang('site.mobile')</td>
                                <td>{{$record->mobile}}</td>
                            </tr>


                            <tr>
                                <td class="text-bold">@lang('site.sent_at')</td>
                                <td>{{$record->created_at}}</td>
                            </tr>


                            <tr>
                                <td class="text-bold">@lang('site.status')</td>
                                <td>
                                    <span>
                                        @lang('site.'.$record->status)
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-bold">@lang('site.message')</td>
                                <td>
                                    {{$record->message}}
                                </td>
                            </tr>

                            @if ($record->status == 'replied')
                                <tr>
                                    <td class="text-bold">@lang('site.replied_at')</td>
                                    <td>
                                        {{$record->replied_at}}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-bold">@lang('site.replied_by')</td>
                                    <td>
                                        {{$record->admin->name}}
                                    </td>
                                </tr>
                            @endif



                            <tr>
                                <td style='width:20%' class="text-bold">@lang('site.reply')</td>
                                <td style='width:80%'>
                                    @if ($record->status=='unreplied')
                                        <textarea wire:model="form.reply" name="reply"  class="form-control"></textarea>
                                        @error('form.reply')<p style='color:red'> {{$message}} </p>@enderror

                                    @else
                                        {{$record->reply}}
                                    @endif

                                </td>
                            </tr>

                            @if($record->status=='unreplied')
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-success" type='submit'>@lang('site.reply')</button>
                                </td>
                            </tr>
                            @endif

                        </table>
                    </div>
                </form>
            </div><!-- table-page-wrap -->
          </div><!-- border-div -->
 </main>
