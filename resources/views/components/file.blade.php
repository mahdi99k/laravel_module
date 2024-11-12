<div class="file-upload">
    <div class="file-upload$at mt-5">
        <div class="i-file-upload cursor-pointer">
            <span>{{ $placeholder }}</span>
            <input type="file" class="file-upload" id="files" name="{{ $name }}"  {{ $attributes }} />
        </div>
        <span class="filesize"></span>
        @if (isset($value))
            <span class="selectedFiles">
                <p> تصویر فعلی : <strong>{{ $value->filename }}</strong></p>
                <img src="{{ $value->thumb }}" width="120px" alt="" class="mt-3">  {{-- {{ '/storage/' . $value->files['300'] }} --}}
            </span>
        @else
            <span class="selectedFiles">فایلی انتخاب نشده است</span>
        @endif
    </div>
    <x-validation-error field="{{ $name }}" />
</div>
