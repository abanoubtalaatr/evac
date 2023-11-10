<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="edit-c">
            <form wire:submit.prevent='store'>
                <div class="row">
                    <div class="col-6">
                        <input
                            wire:model='form.name_ar'
                            class="@error('form.name_ar') is-invalid @enderror form-control contact-input"
                            type="text" placeholder="@lang('site.name_ar')"/>
                        @error('form.name_ar') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>


                    <div class="col-6">
                        <input
                            wire:model='form.name'
                            class="@error('form.name') is-invalid @enderror form-control contact-input"
                            type="text" placeholder="@lang('site.name')"/>
                        @error('form.name') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>


                </div>

                <div class="form-group" wire:ignore>
                    <label for="">@lang('site.permissions')</label>
                    <br>
                    <select id='permissions' wire:model='form.permissions' style="min-height: 300px" multiple
                            class="@error('permissions') is-invalid @enderror form-control contact-input  my-select-2">
                        @foreach($permissions as $permission)
                            <option
                                value="{{$permission->id}}">{{app()->getLocale()=='ar'?$permission->name_ar:$permission->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('form.permissions') <p class="text-danger">{{$message}}</p> @enderror
                <hr>

                <div class="btns text-center">
                    <button type='submit' class="button btn-red big">@lang('site.save')</button>
                </div>

            </form>
        </div>
    </div>
</main>
@push('scripts')

    <script>

        window.addEventListener('onContentChanged', () => {
            $('select').select2();
        });

        $(document).ready(()=>{
           $('select').select2();
            $('#permissions').change(e=>{
                @this.set('form.permissions', $('#permissions').select2('val'));
            });
        });
    </script>
@endpush

