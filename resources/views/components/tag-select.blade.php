<ul class="{{--tags--}} mt-2 mb-0">
    <li class="{{--tagAdd--}} taglist">
        <x-input type="text" name="{{ $name }}" placeholder="برچسب ها" id="search-field" />
    </li>
</ul>
<x-validation-error field="{{ $name }}" />
