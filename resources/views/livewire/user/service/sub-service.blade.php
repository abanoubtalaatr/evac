<div>
    @if(isset($subServices))
        @foreach($subServices as $subService)
            <div class="form-group col-md-6">
                <input type="checkbox" id="subService_{{ $subService->id }}" wire:change="changeSubService({{$subService->id}})">
                <label for="subService_{{ $subService->id }}">
                    {{ $subService->name }} - <span>{{ $subService->price }} @lang('site.ryal')</span>
                </label>
            </div>
        @endforeach
    @endif
</div>
