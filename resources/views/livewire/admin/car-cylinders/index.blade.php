<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
            <a style='text-align:center' href='{{route('admin.create_car_cylinders')}}' class="button btn-red big">@lang('site.create')</a>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded">

                <div class="form-group col-3">
                    <label for="status-select">@lang('validation.attributes.name')</label>
                    <input wire:model='name' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-2">
                    <label for="status-select">@lang('site.status')</label>
                    <select wire:model='status' id='status-select' class="form-control  contact-input">
                        <option value>@lang('site.status')</option>
                        <option value="active">@lang('site.active')</option>
                        <option value="inactive">@lang('site.inactive')</option>
                    </select>
                </div>
                <div class="form-group col-2">
                    <label for="status-select">@lang('site.car_brands')</label>
                    <select wire:model='brand' id='status-select' class="form-control  contact-input">
                        <option value>@lang('site.car_brands')</option>
                        @foreach($carBrands as $carBrand)
                            <option value="{{$carBrand->id}}">{{app()->getLocale() =='ar'? $carBrand->name_ar:$carBrand->name_en}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('site.car_modules')</label>
                    <select wire:model='module' id='status-select' class="form-control  contact-input">
                        <option value>@lang('site.car_modules')</option>
                        @foreach($carModules as $carModule)
                            <option value="{{$carModule->id}}">{{app()->getLocale() =='ar'? $carModule->name_ar:$carModule->name_en}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-2">
                    <button wire:click="resetData()" class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>

            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('validation.attributes.name_ar')</th>
                        <th class="text-center">@lang('validation.attributes.name_en')</th>
                        <th class="text-center">@lang('site.car_brand')</th>
                        <th class="text-center">@lang('site.car_module')</th>
                        <th class="text-center">@lang('validation.attributes.created_at')</th>
                        <th class="text-center">@lang('site.status')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>

                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->name_ar}}</td>
                            <td class='text-center'>{{$record->name_en}}</td>
                            <td class='text-center'>{{app()->getLocale() =='ar' ? $record->carBrand->name_ar:$record->carBrand->name_en}}</td>
                            <td class='text-center'>{{app()->getLocale() =='ar' ? $record->carModule->name_ar:$record->carModule->name_en}}</td>

                            <td class='text-center'>{{$record->created_at}}</td>
                            <td class='text-center'>
                                <div class="status {{$record->status_class}}">
                                    <span>@lang('site.'.$record->status)</span>
                                </div>
                            </td>

                            <td>
                                <div class="actions">
                                    <button
                                        wire:click='toggleStatus({{$record->id}})'
                                        class="no-btn">
                                        <i class="fas @if($record->status=='active') fa-lock red @else fa-unlock green @endif"></i>
                                    </button>
                                    <a href='{{route('admin.edit_car_cylinders',$record->id)}}' class="no-btn"><i
                                            class="far fa-edit blue"></i></a>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>

                {{$records->links()}}
            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
        </div>
    </div>
</main>
