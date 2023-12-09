
<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--dashboard-->
    <section class="dashboard p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="border bg-white rounded p-lg-5 p-3 mb-3">
                    <h5 class="head-term">{{$page_title}}</h5>
                    @include('livewire.admin.shared.reports.actions',['url' => route('admin.print.daily_reports') ,'routeName' => route('admin.test_export'),'className' => 'App\\Http\\Controllers\\Admin\\Reports\\DailyReport\\PrintController'])
                    <hr>
                    @if(!empty($message))
                        <div class="alert alert-danger"> {{$message}}</div>
                    @endif

                    <div class="d-flex gap-5">

                        <button class="btn btn-primary" wire:click="startDay" @if($disabledButtonDayStart) disabled @endif>Start day</button>
                        <button class="btn btn-primary" wire:click="endDay"  @if($disabledButtonDayEnd) disabled @endif>End day</button>
                        <button class="btn btn-primary" wire:click="restartDay" @if($disabledButtonDayRestartDay) disabled @endif>Restart day</button>
                    </div>
                </div>
            </div>

        </div>

    </section>

</main>


