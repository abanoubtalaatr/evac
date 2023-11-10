<main class="main-content">
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
            <a style='text-align:center' href='{{route('admin.pages.create')}}' class="button btn-red big">@lang('site.create_new')</a>
        </div>


        <div class="table-page-wrap">
            <div class="table-responsive">
                @if(count($records))
                    <table class="table-page table">
                        <thead>
                        <tr>
                            <th>#</th>

                            <th>@lang('site.title_'.app()->getLocale())</th>
                            <th>@lang('site.created_at')</th>
                            <th>@lang('site.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>#{{$loop->index + 1}}</td>

                                <td>{{$record->{"title_".app()->getLocale()} }}</td>
                                <td>{{$record->created_at}}</td>
                                <td>
                                    <div class="actions">
                                        <a class="no-btn" href='{{route('admin.pages.edit',$record)}}'><i class="far fa-edit blue"></i></a>

                                        {{-- Prevent deletion of about us (used in home) --}}
                                        @if($record->id > 15)
                                            <a class="no-btn" href='{{route('admin.pages.delete',$record)}}'><i class="fas fa-trash red"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$records->links()}}
                @else
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                @endif

            </div>
        </div>
    </div>
</main>
