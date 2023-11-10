 <main class="main-content">
          <!--head-->
          <x-admin.head/>
          <!--table-->
          <div class="border-div">
            <div class="b-btm flex-div-2">
              <h4>{{$page_title}}</h4>
            </div>
            <div class="table-page-wrap">

                <div class="row">
                    <div class="form-group col-3">
                        <label for="sender-name">@lang('validation.attributes.sender_name')</label>
                        <input wire:model='sender_name' type="text" class="form-control contact-input">
                    </div>

                    <div class="form-group col-3">
                        <label for="sender-email">@lang('validation.attributes.sender_email')</label>
                        <input wire:model='sender_email' type="text" class="form-control contact-input">
                    </div>

                    <div class="form-group col-3">
                        <label for="status-select">@lang('site.status')</label>
                        <select wire:model='status' id='status-select' class="form-control  contact-input">
                            <option value>@lang('site.status')</option>
                            <option value="replied">@lang('site.replied')</option>
                            <option value="unreplied">@lang('site.not_replied')</option>
                        </select>
                    </div>



                </div>

                @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">@lang('validation.attributes.sender_name')</th>
                        <th class="text-center">@lang('validation.attributes.sender_email')</th>
                        <th class="text-center">@lang('site.sent_at')</th>
                        <th class="text-center">@lang('site.status')</th>
                        <th class="text-center" style="width:10%">@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td>{{$record->sender_name}}</td>
                            <td>{{$record->sender_email}}</td>
                            <td>{{$record->created_at->format('Y-m-d H:i:s')}}</td>
                            <td>
                                <div class="status {{$record->status=='replied'? 'green' : 'yellow'}}">
                                    <span>@lang('site.'.$record->status)</span>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('admin.contact.show',$record->id)}}" class='btn btn-primary btn-sm'>
                                    @if ($record->status=='unreplied')
                                        <i class="fas fa-reply"></i>
                                        @lang('site.reply')
                                    @else
                                        <i class="fas fa-search"></i>
                                        @lang('site.details')
                                    @endif

                                </a>
                            </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>

                {{$records->links()}}
                @else
                    <div class="row" style='margin-top:10px'>
                        <div class="alert alert-warning">@lang('messages.no_data_to_display')</div>
                    </div>
                @endif
            </div>
          </div>
        </main>
