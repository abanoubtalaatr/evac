<div
class='row'
    wire:ignore
    x-data
    x-init="
        () => {
            const post = FilePond.create($refs.{{ $attributes->get('ref') ?? 'input' }});
            post.setOptions({
                allowMultiple: true,
                server: {
                    process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    },
                },
                dropValidation:true,
                allowImagePreview: false,
                allowFileTypeValidation: true,
                acceptedFileTypes: ['video/mp4','image/jpg','image/png','image/jpeg'],
                allowFileSizeValidation: true,
                maxFileSize: '101mb',


                labelIdle:'@lang('filepond.drag_and_drop_or') <span class=filepond--label-action> @lang('filepond.browse') </span>',
                labelInvalidField:'@lang('filepond.labelInvalidField')',
                labelFileWaitingForSize:'@lang('filepond.labelFileWaitingForSize')',

                labelFileSizeNotAvailable:'@lang('filepond.labelFileSizeNotAvailable')',
                labelFileLoading:'@lang('filepond.labelFileLoading')',
                labelFileLoadError:'@lang('filepond.labelFileLoadError')',
                labelFileProcessing:'@lang('filepond.labelFileProcessing')',
                labelFileProcessingComplete:'@lang('filepond.labelFileProcessingComplete')',
                labelFileProcessingAborted:'@lang('filepond.labelFileProcessingAborted')',
                labelFileProcessingError:'@lang('filepond.labelFileProcessingError')',
                labelFileRemoveError:'@lang('filepond.labelFileRemoveError')',
                labelTapToCancel:'@lang('filepond.labelTapToCancel')',
                labelTapToRetry:'@lang('filepond.labelTapToRetry')',
                labelTapToUndo:'@lang('filepond.labelTapToUndo')',
                labelButtonRemoveItem:'@lang('filepond.labelButtonRemoveItem')',
                labelButtonAbortItemLoad:'@lang('filepond.labelButtonAbortItemLoad')',
                labelButtonRetryItemLoad:'@lang('filepond.labelButtonRetryItemLoad')',
                labelButtonAbortItemProcessing:'@lang('filepond.labelButtonAbortItemProcessing')',
                labelButtonUndoItemProcessing:'@lang('filepond.labelButtonUndoItemProcessing')',
                labelButtonRetryItemProcessing:'@lang('filepond.labelButtonRetryItemProcessing')',
                labelButtonProcessItem:'@lang('filepond.labelButtonProcessItem')',



            });
        }
    "
>
    <input type="file" x-ref="{{ $attributes->get('ref') ?? 'input' }}" />
</div>

@push('styles')
    @once
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    @endonce
@endpush

@push('scripts')

    @once
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <script>
            function getComponentData(){
                return {
                    accepted_file_types(){
                        let media_type=@entangle('form.media_type');
                        return media_type=='video'? "['video/mp4']": "['image/png', 'image/jpg','image/jpeg']";
                    }
                };
            }
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginImageResize);

        </script>
    @endonce
@endpush
