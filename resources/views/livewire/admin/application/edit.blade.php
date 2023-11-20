
<main class="main-content">
    <x-admin.head/>
    @include('livewire.admin.application.popup.blackListPassport')
    @include('livewire.admin.application.popup.expiryPassport')
    @include('livewire.admin.application.popup.passportHasMoreThanOne')
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="row mt-30">

            <div class="col-6">
                <div class="form-group my-2 ">
                    <label class="" for="visaType">Visa Type:</label>
                    <select wire:model="form.visa_type_id" class="form-control" id="visaType">
                        <option value="">Select Visa Type</option>
                        @foreach ($visaTypes as $visaType)
                            <option value="{{ $visaType->id }}">{{ $visaType->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('form.visa_type_id')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6">
                <div class="form-group my-2">
                    <label for="visaProvider">Visa Provider:</label>
                    <div>
                        @foreach ($visaProviders as $visaProvider)
                            <label>
                                <input type="radio" wire:model="form.visa_provider_id" value="{{ $visaProvider->id }}" id="visaProvider{{ $visaProvider->id }}">
                                {{ $visaProvider->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('form.visa_provider_id')<p style="color: red;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="col-6">
                <div class="form-group my-2">
                    <div class="">
                        <label for="travelAgent">Travel Agent:</label>
                        <input class="" type="checkbox" wire:click="chooseTravelAgent">
                        @if ($isChecked)
                            <div class="form-group " wire:ignore style="width: 50%">

                                <select id='agent_id' wire:model='form.travel_agent_id' class="@error('form.agent_id') is-invalid @enderror form-control contact-input">
                                    <option value="" >Select Option</option>

                                    @foreach($travelAgents as $travelAgent)
                                        <option selected value="{{$travelAgent->id}}">{{$travelAgent->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                @error('form.travel_agent_id')<p style="color: red;">{{ $message }}</p>@enderror
            </div>

            <div class="col-6">
                <label for="passport_no" class="">Passport no:</label>
                <input type="text" class="form-control" wire:model.lazy="passportNumber" wire:blur="checkPassportNumber">
                @error('form.passport_no')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
{{--            <div class="col-6 my-2">--}}
{{--                <div class="form-group my-2 ">--}}
{{--                    <label for="title">Title:</label>--}}
{{--                    <select wire:model="form.title" class="form-control" id="title">--}}
{{--                        <option value="">Select Title</option>--}}
{{--                        <option value="Mr">Mr.</option>--}}
{{--                        <option value="Mrs">Mrs.</option>--}}
{{--                        <option value="Ms">Ms.</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                @error('form.title')<p style="color: red;">{{ $message }}</p>@enderror--}}
{{--            </div>--}}


            <div class="col-6">
                <div class="form-group my-2">
                    <label for="first_name" class="">First name:</label>
                    <input type="text" wire:model="form.first_name"  class="form-control">
                    @error('form.first_name')<p style="color: red;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group my-2">
                    <label for="last_name">Last name:</label>
                    <input type="text" wire:model="form.last_name" class="form-control">
                    @error('form.last_name')<p style="color: red;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="col-6">
                <div class="form-group ">
                    <label for="expiry_date"> Expiry date:</label>
                    <div>
                        <input class="form-control" type="date" wire:model="form.expiry_date">
                    </div>
                </div>
                @error('form.expiry_date')<p style="color: red;">{{ $message }}</p>@enderror
            </div>

            <div class="col-6">
                <label for="notes" class="">Note:</label>
                <textarea class="form-control" wire:model="form.notes"></textarea>
                @error('form.notes')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6 my-2 d-flex gap-5">
                <label>Payment method:</label>

                <div class="">
                    <input class="form-check-input" type="radio" id="invoice" wire:model="form.payment_method" checked value="invoice">
                    <label class="form-check-label" for="invoice">Invoice</label>
                </div>

                <div class="">
                    <input class="form-check-input" type="radio" id="cash" wire:model="form.payment_method" value="cash">
                    <label class="form-check-label" for="cash">Cash</label>
                </div>

                @error('form.payment_method') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-6">
                <div class="form-group my-2 ">
                    <label class="" for="visaType">Status : </label>
                    <select wire:model="form.status" class="form-control" id="status">
                        <option value="new">New</option>
                        <option value="appraised">appraised</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>
                @error('form.status')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6" wire:ignore>
                <label for="amount" class="">Amount:</label>
                <div class="input-group">
                    <input class="form-control" disabled wire:model="form.amount" id="amount">
                    <div class="input-group-append">
                    <span class="input-group-text bg-warning" id="editIcon" style="cursor: pointer; height: 38px;" onclick="enableInput()">
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                    </div>
                </div>
                @error('form.amount')<p style="color: red;">{{ $message }}</p>@enderror
            </div>


            <div class="col-12 text-center my-2">
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
        </div>
    </div>
</main>



@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>
        window.addEventListener('onContentChanged', () => {
            $('#agent_id').select2();
        });

        $(document).ready(()=>{
           $('#agent_id').select2();
            $('#agent_id').change(e=>{
                @this.set('form.agent_id', $('#agent_id').select2('val'));
            });

        });

        Livewire.on('openBlackListModal', function () {
            $('#blackListModal').modal('show');
        });

        Livewire.on('showExpiryPopup', function () {
          $('#expiryPassportModal').modal('show');
        });

        Livewire.on('showMultipleApplicationsPopup', function () {
          $('#passportHasMoreThanOneModal').modal('show');
        });
        function enableInput() {
            document.getElementById('amount').removeAttribute('disabled');
        }
    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
@endpush
